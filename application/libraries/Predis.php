<?php
namespace Predis;

class PredisException extends \Exception { }
class ClientException extends PredisException { }                   // Client-side errors

class ServerException extends PredisException {                     // Server-side errors
    public function toResponseError() {
        return new ResponseError($this->getMessage());
    }
}

class CommunicationException extends PredisException {              // Communication errors
    private $_connection;

    public function __construct(Connection $connection, $message = null, $code = null) {
        $this->_connection = $connection;
        parent::__construct($message, $code);
    }

    public function getConnection() { return $this->_connection; }
    public function shouldResetConnection() {  return true; }
}

class MalformedServerResponse extends CommunicationException { }    // Unexpected responses

/* ------------------------------------------------------------------------- */

class Client {
    private $_options, $_connection, $_serverProfile, $_responseReader;

    public function __construct($parameters = null, $clientOptions = null) {
    	//echo "Client::__construct ";var_dump($parameters);echo "<br>";
        $this->setupClient($clientOptions ?: new ClientOptions());
        $this->setupConnection($parameters);
    }

    public static function create(/* arguments */) {
        $argv = func_get_args();
        $argc = func_num_args();

        $options = null;
        $lastArg = $argv[$argc-1];
        if ($argc > 0 && !is_string($lastArg) && ($lastArg instanceof ClientOptions ||
            is_subclass_of($lastArg, '\Predis\RedisServerProfile'))) {
            $options = array_pop($argv);
            $argc--;
        }

        if ($argc === 0) {
            throw new ClientException('Missing connection parameters');
        }

        return new Client($argc === 1 ? $argv[0] : $argv, $options);
    }

    private static function filterClientOptions($options) {
        if ($options instanceof ClientOptions) {
            return $options;
        }
        if (is_array($options)) {
            return new ClientOptions($options);
        }
        if ($options instanceof RedisServerProfile) {
            return new ClientOptions(array(
                'profile' => $options
            ));
        }
        if (is_string($options)) {
            return new ClientOptions(array(
                'profile' => RedisServerProfile::get($options)
            ));
        }
        throw new \InvalidArgumentException("Invalid type for client options");
    }

    private function setupClient($options) {
        $this->_responseReader = new ResponseReader();
        $this->_options = self::filterClientOptions($options);

        $this->setProfile($this->_options->profile);
        if ($this->_options->iterable_multibulk === true) {
            $this->_responseReader->setHandler(
                Protocol::PREFIX_MULTI_BULK, 
                new ResponseMultiBulkStreamHandler()
            );
        }
        if ($this->_options->throw_on_error === false) {
            $this->_responseReader->setHandler(
                Protocol::PREFIX_ERROR, 
                new ResponseErrorSilentHandler()
            );
        }
    }

    private function setupConnection($parameters) {
    	//echo"setupConnection: ";var_dump($parameters);echo "<br>";
        if ($parameters !== null && !(is_array($parameters) || is_string($parameters))) {
            throw new ClientException('Invalid parameters type (array or string expected)');
        }

        if (is_array($parameters) && isset($parameters[0])) {
            $cluster = new ConnectionCluster($this->_options->key_distribution);
            foreach ($parameters as $shardParams) {
                $cluster->add($this->createConnection($shardParams));
            }
            $this->setConnection($cluster);
        }
        else {
            $this->setConnection($this->createConnection($parameters));
        }
    }

    private function createConnection($parameters) {
        $params     = new ConnectionParameters($parameters);
        $connection = new Connection($params, $this->_responseReader);

        if ($params->password !== null) {
            $connection->pushInitCommand($this->createCommand(
                'auth', array($params->password)
            ));
        }
        if ($params->database !== null) {
            $connection->pushInitCommand($this->createCommand(
                'select', array($params->database)
            ));
        }

        return $connection;
    }

    private function setConnection(IConnection $connection) {
        $this->_connection = $connection;
    }

    public function setProfile($serverProfile) {
        if (!($serverProfile instanceof RedisServerProfile || is_string($serverProfile))) {
            throw new \InvalidArgumentException(
                "Invalid type for server profile, \Predis\RedisServerProfile or string expected"
            );
        }
        $this->_serverProfile = (is_string($serverProfile) 
            ? RedisServerProfile::get($serverProfile)
            : $serverProfile
        );
    }

    public function getProfile() {
        return $this->_serverProfile;
    }

    public function getResponseReader() {
        return $this->_responseReader;
    }

    public function connect() {
        $this->_connection->connect();
    }

    public function disconnect() {
        $this->_connection->disconnect();
    }

    public function isConnected() {
        return $this->_connection->isConnected();
    }

    public function getConnection($id = null) {
        if (!isset($id)) {
            return $this->_connection;
        }
        else {
            return $this->_connection instanceof ConnectionCluster 
                ? $this->_connection->getConnectionById($id)
                : $this->_connection;
        }
    }

    public function __call($method, $arguments) {
        $command = $this->_serverProfile->createCommand($method, $arguments);
        return $this->_connection->executeCommand($command);
    }

    public function createCommand($method, $arguments = array()) {
        return $this->_serverProfile->createCommand($method, $arguments);
    }

    public function executeCommand(Command $command) {
        return $this->_connection->executeCommand($command);
    }

    public function executeCommandOnShards(Command $command) {
        $replies = array();
        if ($this->_connection instanceof \Predis\ConnectionCluster) {
            foreach($this->_connection as $connection) {
                $replies[] = $connection->executeCommand($command);
            }
        }
        else {
            $replies[] = $this->_connection->executeCommand($command);
        }
        return $replies;
    }

    public function rawCommand($rawCommandData, $closesConnection = false) {
        if ($this->_connection instanceof \Predis\ConnectionCluster) {
            throw new ClientException('Cannot send raw commands when connected to a cluster of Redis servers');
        }
        return $this->_connection->rawCommand($rawCommandData, $closesConnection);
    }

    public function pipeline($pipelineBlock = null) {
        $pipeline = new CommandPipeline($this);
        return $pipelineBlock !== null ? $pipeline->execute($pipelineBlock) : $pipeline;
    }

    public function multiExec($multiExecBlock = null) {
        $multiExec = new MultiExecBlock($this);
        return $multiExecBlock !== null ? $multiExec->execute($multiExecBlock) : $multiExec;
    }

    public function pubSubContext() {
        return new PubSubContext($this);
    }
}

/* ------------------------------------------------------------------------- */

interface IClientOptionsHandler {
    public function validate($option, $value);
    public function getDefault();
}

class ClientOptionsProfile implements IClientOptionsHandler {
    public function validate($option, $value) {
        if ($value instanceof \Predis\RedisServerProfile) {
            return $value;
        }
        if (is_string($value)) {
            return \Predis\RedisServerProfile::get($value);
        }
        throw new \InvalidArgumentException("Invalid value for option $option");
    }

    public function getDefault() {
        return \Predis\RedisServerProfile::getDefault();
    }
}

class ClientOptionsKeyDistribution implements IClientOptionsHandler {
    public function validate($option, $value) {
        if ($value instanceof \Predis\Distribution\IDistributionAlgorithm) {
            return $value;
        }
        if (is_string($value)) {
            $valueReflection = new \ReflectionClass($value);
            if ($valueReflection->isSubclassOf('\Predis\Distribution\IDistributionAlgorithm')) {
                return new $value;
            }
        }
        throw new \InvalidArgumentException("Invalid value for option $option");
    }

    public function getDefault() {
        return new \Predis\Distribution\HashRing();
    }
}

class ClientOptionsIterableMultiBulk implements IClientOptionsHandler {
    public function validate($option, $value) {
        return (bool) $value;
    }

    public function getDefault() {
        return false;
    }
}

class ClientOptionsThrowOnError implements IClientOptionsHandler {
    public function validate($option, $value) {
        return (bool) $value;
    }

    public function getDefault() {
        return true;
    }
}

class ClientOptions {
    private static $_optionsHandlers;
    private $_options;

    public function __construct($options = null) {
        self::initializeOptionsHandlers();
        $this->initializeOptions($options ?: array());
    }

    private static function initializeOptionsHandlers() {
        if (!isset(self::$_optionsHandlers)) {
            self::$_optionsHandlers = self::getOptionsHandlers();
        }
    }

    private static function getOptionsHandlers() {
        return array(
            'profile'    => new \Predis\ClientOptionsProfile(),
            'key_distribution' => new \Predis\ClientOptionsKeyDistribution(),
            'iterable_multibulk' => new \Predis\ClientOptionsIterableMultiBulk(),
            'throw_on_error' => new \Predis\ClientOptionsThrowOnError(),
        );
    }

    private function initializeOptions($options) {
        foreach ($options as $option => $value) {
            if (isset(self::$_optionsHandlers[$option])) {
                $handler = self::$_optionsHandlers[$option];
                $this->_options[$option] = $handler->validate($option, $value);
            }
        }
    }

    public function __get($option) {
        if (!isset($this->_options[$option])) {
            $defaultValue = self::$_optionsHandlers[$option]->getDefault();
            $this->_options[$option] = $defaultValue;
        }
        return $this->_options[$option];
    }

    public function __isset($option) {
        return isset(self::$_optionsHandlers[$option]);
    }
}

/* ------------------------------------------------------------------------- */

class Protocol {
    const NEWLINE = "\r\n";
    const OK      = 'OK';
    const ERROR   = 'ERR';
    const QUEUED  = 'QUEUED';
    const NULL    = 'nil';

    const PREFIX_STATUS     = '+';
    const PREFIX_ERROR      = '-';
    const PREFIX_INTEGER    = ':';
    const PREFIX_BULK       = '$';
    const PREFIX_MULTI_BULK = '*';
}

abstract class Command {
    private $_arguments, $_hash;

    public abstract function getCommandId();

    public abstract function serializeRequest($command, $arguments);

    public function canBeHashed() {
        return true;
    }

    public function getHash(Distribution\IDistributionAlgorithm $distributor) {
        if (isset($this->_hash)) {
            return $this->_hash;
        }
        else {
            if (isset($this->_arguments[0])) {
                $key = $this->_arguments[0];

                $start = strpos($key, '{');
                $end   = strpos($key, '}');
                if ($start !== false && $end !== false) {
                    $key = substr($key, ++$start, $end - $start);
                }

                $this->_hash = $distributor->generateKey($key);
                return $this->_hash;
            }
        }
        return null;
    }

    public function closesConnection() {
        return false;
    }

    protected function filterArguments(Array $arguments) {
        return $arguments;
    }

    public function setArguments(/* arguments */) {
        $this->_arguments = $this->filterArguments(func_get_args());
        $this->_hash = null;
    }

    public function setArgumentsArray(Array $arguments) {
        $this->_arguments = $this->filterArguments($arguments);
        $this->_hash = null;
    }

    protected function getArguments() {
        // TODO: why getArguments is protected?
        return isset($this->_arguments) ? $this->_arguments : array();
    }

    public function getArgument($index = 0) {
        return isset($this->_arguments[$index]) ? $this->_arguments[$index] : null;
    }

    public function parseResponse($data) {
        return $data;
    }

    public final function __invoke() {
        return $this->serializeRequest($this->getCommandId(), $this->getArguments());
    }
}

abstract class InlineCommand extends Command {
    public function serializeRequest($command, $arguments) {
        if (isset($arguments[0]) && is_array($arguments[0])) {
            $arguments[0] = implode($arguments[0], ' ');
        }
        return $command . ' ' . implode($arguments, ' ') . Protocol::NEWLINE;
    }
}

abstract class BulkCommand extends Command {
    public function serializeRequest($command, $arguments) {
        $data = array_pop($arguments);
        if (is_array($data)) {
            $data = implode($data, ' ');
        }
        return $command . ' ' . implode($arguments, ' ') . ' ' . strlen($data) . 
            Protocol::NEWLINE . $data . Protocol::NEWLINE;
    }
}

abstract class MultiBulkCommand extends Command {
    public function serializeRequest($command, $arguments) {
        $cmd_args = null;
        $argsc    = count($arguments);

        if ($argsc === 1 && is_array($arguments[0])) {
            $cmd_args = $arguments[0];
            $argsc = count($cmd_args);
        }
        else {
            $cmd_args = $arguments;
        }

        $newline = Protocol::NEWLINE;
        $cmdlen  = strlen($command);
        $reqlen  = $argsc + 1;

        $buffer = "*{$reqlen}{$newline}\${$cmdlen}{$newline}{$command}{$newline}";
        foreach ($cmd_args as $argument) {
            $arglen  = strlen($argument);
            $buffer .= "\${$arglen}{$newline}{$argument}{$newline}";
        }

        return $buffer;
    }
}

/* ------------------------------------------------------------------------- */

interface IResponseHandler {
    function handle(Connection $connection, $payload);
}

class ResponseStatusHandler implements IResponseHandler {
    public function handle(Connection $connection, $status) {
        if ($status === Protocol::OK) {
            return true;
        }
        else if ($status === Protocol::QUEUED) {
            return new ResponseQueued();
        }
        return $status;
    }
}

class ResponseErrorHandler implements IResponseHandler {
    public function handle(Connection $connection, $errorMessage) {
        throw new ServerException(substr($errorMessage, 4));
    }
}

class ResponseErrorSilentHandler implements IResponseHandler {
    public function handle(Connection $connection, $errorMessage) {
        return new ResponseError(substr($errorMessage, 4));
    }
}

class ResponseBulkHandler implements IResponseHandler {
    public function handle(Connection $connection, $dataLength) {
        if (!is_numeric($dataLength)) {
            Shared\Utils::onCommunicationException(new MalformedServerResponse(
                $connection, "Cannot parse '$dataLength' as data length"
            ));
        }

        if ($dataLength > 0) {
            $value = $connection->readBytes($dataLength);
            self::discardNewLine($connection);
            return $value;
        }
        else if ($dataLength == 0) {
            self::discardNewLine($connection);
            return '';
        }

        return null;
    }

    private static function discardNewLine(Connection $connection) {
        if ($connection->readBytes(2) !== Protocol::NEWLINE) {
            Shared\Utils::onCommunicationException(new MalformedServerResponse(
                $connection, 'Did not receive a new-line at the end of a bulk response'
            ));
        }
    }
}

class ResponseMultiBulkHandler implements IResponseHandler {
    public function handle(Connection $connection, $rawLength) {
        if (!is_numeric($rawLength)) {
            Shared\Utils::onCommunicationException(new MalformedServerResponse(
                $connection, "Cannot parse '$rawLength' as data length"
            ));
        }

        $listLength = (int) $rawLength;
        if ($listLength === -1) {
            return null;
        }

        $list = array();

        if ($listLength > 0) {
            for ($i = 0; $i < $listLength; $i++) {
                $list[] = $connection->getResponseReader()->read($connection);
            }
        }

        return $list;
    }
}

class ResponseMultiBulkStreamHandler implements IResponseHandler {
    public function handle(Connection $connection, $rawLength) {
        if (!is_numeric($rawLength)) {
            Shared\Utils::onCommunicationException(new MalformedServerResponse(
                $connection, "Cannot parse '$rawLength' as data length"
            ));
        }
        return new Shared\MultiBulkResponseIterator($connection, (int)$rawLength);
    }
}

class ResponseIntegerHandler implements IResponseHandler {
    public function handle(Connection $connection, $number) {
        if (is_numeric($number)) {
            return (int) $number;
        }
        else {
            if ($number !== Protocol::NULL) {
                Shared\Utils::onCommunicationException(new MalformedServerResponse(
                    $connection, "Cannot parse '$number' as numeric response"
                ));
            }
            return null;
        }
    }
}

class ResponseReader {
    private $_prefixHandlers;

    public function __construct() {
        $this->initializePrefixHandlers();
    }

    private function initializePrefixHandlers() {
        $this->_prefixHandlers = array(
            Protocol::PREFIX_STATUS     => new ResponseStatusHandler(), 
            Protocol::PREFIX_ERROR      => new ResponseErrorHandler(), 
            Protocol::PREFIX_INTEGER    => new ResponseIntegerHandler(), 
            Protocol::PREFIX_BULK       => new ResponseBulkHandler(), 
            Protocol::PREFIX_MULTI_BULK => new ResponseMultiBulkHandler(), 
        );
    }

    public function setHandler($prefix, IResponseHandler $handler) {
        $this->_prefixHandlers[$prefix] = $handler;
    }

    public function getHandler($prefix) {
        if (isset($this->_prefixHandlers[$prefix])) {
            return $this->_prefixHandlers[$prefix];
        }
    }

    public function read(Connection $connection) {
        $header = $connection->readLine();
        if ($header === '') {
            Shared\Utils::onCommunicationException(new MalformedServerResponse(
                $connection, 'Unexpected empty header'
            ));
        }

        $prefix  = $header[0];
        $payload = strlen($header) > 1 ? substr($header, 1) : '';

        if (!isset($this->_prefixHandlers[$prefix])) {
            Shared\Utils::onCommunicationException(new MalformedServerResponse(
                $connection, "Unknown prefix '$prefix'"
            ));
        }

        $handler = $this->_prefixHandlers[$prefix];
        return $handler->handle($connection, $payload);
    }
}

class ResponseError {
    private $_message;

    public function __construct($message) {
        $this->_message = $message;
    }

    public function __get($property) {
        if ($property == 'error') {
            return true;
        }
        if ($property == 'message') {
            return $this->_message;
        }
    }

    public function __isset($property) {
        return $property === 'error';
    }

    public function __toString() {
        return $this->_message;
    }
}

class ResponseQueued {
    public $queued = true;

    public function __toString() {
        return Protocol::QUEUED;
    }
}

/* ------------------------------------------------------------------------- */

class CommandPipeline {
    private $_redisClient, $_pipelineBuffer, $_returnValues, $_running;

    public function __construct(Client $redisClient) {
        $this->_redisClient    = $redisClient;
        $this->_pipelineBuffer = array();
        $this->_returnValues   = array();
    }

    public function __call($method, $arguments) {
        $command = $this->_redisClient->createCommand($method, $arguments);
        $this->recordCommand($command);
        return $this;
    }

    private function recordCommand(Command $command) {
        $this->_pipelineBuffer[] = $command;
    }

    private function getRecordedCommands() {
        return $this->_pipelineBuffer;
    }

    public function flushPipeline() {
        $sizeofPipe = count($this->_pipelineBuffer);
        if ($sizeofPipe === 0) {
            return;
        }

        $connection = $this->_redisClient->getConnection();
        $commands   = &$this->_pipelineBuffer;

        foreach ($commands as $command) {
            $connection->writeCommand($command);
        }
        for ($i = 0; $i < $sizeofPipe; $i++) {
            $response = $connection->readResponse($commands[$i]);
            $this->_returnValues[] = ($response instanceof \Iterator
                ? iterator_to_array($response)
                : $response
            );
            unset($commands[$i]);
        }
        $this->_pipelineBuffer = array();

        return $this;
    }

    private function setRunning($bool) {
        if ($bool == true && $this->_running == true) {
            throw new ClientException("This pipeline is already opened");
        }

        $this->_running = $bool;
    }

    public function execute($block = null) {
        if ($block && !is_callable($block)) {
            throw new \InvalidArgumentException('Argument passed must be a callable object');
        }

        // TODO: do not reuse previously executed pipelines
        $this->setRunning(true);
        $pipelineBlockException = null;

        try {
            if ($block !== null) {
                $block($this);
            }
            $this->flushPipeline();
        }
        catch (\Exception $exception) {
            // TODO: client/server desync on ServerException
            $pipelineBlockException = $exception;
        }

        $this->setRunning(false);

        if ($pipelineBlockException !== null) {
            throw $pipelineBlockException;
        }

        return $this->_returnValues;
    }
}

class MultiExecBlock {
    private $_redisClient, $_commands, $_initialized, $_discarded;

    public function __construct(Client $redisClient) {
        $this->_initialized = false;
        $this->_discarded   = false;
        $this->_redisClient = $redisClient;
        $this->_commands    = array();
    }

    private function initialize() {
        if ($this->_initialized === false) {
            $this->_redisClient->multi();
            $this->_initialized = true;
            $this->_discarded   = false;
        }
    }

    public function __call($method, $arguments) {
        $this->initialize();
        $command  = $this->_redisClient->createCommand($method, $arguments);
        $response = $this->_redisClient->executeCommand($command);
        if (isset($response->queued)) {
            $this->_commands[] = $command;
            return $this;
        }
        else {
            $this->malformedServerResponse('The server did not respond with a QUEUED status reply');
        }
    }

    public function discard() {
        $this->_redisClient->discard();
        $this->_commands    = array();
        $this->_initialized = false;
        $this->_discarded   = true;
    }

    public function execute($block = null) {
        if ($block && !is_callable($block)) {
            throw new \InvalidArgumentException('Argument passed must be a callable object');
        }

        $blockException = null;
        $returnValues   = array();

        try {
            if ($block !== null) {
                $block($this);
            }

            if ($this->_discarded === true) {
                return;
            }

            $execReply = (($reply = $this->_redisClient->exec()) instanceof \Iterator
                ? iterator_to_array($reply)
                : $reply
            );
            $commands  = &$this->_commands;
            $sizeofReplies = count($execReply);

            if ($sizeofReplies !== count($commands)) {
                $this->malformedServerResponse('Unexpected number of responses for a MultiExecBlock');
            }

            for ($i = 0; $i < $sizeofReplies; $i++) {
                $returnValues[] = $commands[$i]->parseResponse($execReply[$i] instanceof \Iterator
                    ? iterator_to_array($execReply[$i])
                    : $execReply[$i]
                );
                unset($commands[$i]);
            }
        }
        catch (\Exception $exception) {
            $blockException = $exception;
        }

        if ($blockException !== null) {
            throw $blockException;
        }

        return $returnValues;
    }

    private function malformedServerResponse($message) {
        // NOTE: a MULTI/EXEC block cannot be initialized on a clustered 
        //       connection, which means that Predis\Client::getConnection 
        //       will always return an instance of Predis\Connection.
        Shared\Utils::onCommunicationException(new MalformedServerResponse(
            $this->_redisClient->getConnection(), $message
        ));
    }
}

class PubSubContext implements \Iterator {
    const SUBSCRIBE    = 'subscribe';
    const UNSUBSCRIBE  = 'unsubscribe';
    const PSUBSCRIBE   = 'psubscribe';
    const PUNSUBSCRIBE = 'punsubscribe';
    const MESSAGE      = 'message';
    const PMESSAGE     = 'pmessage';

    private $_redisClient, $_subscriptions, $_isStillValid, $_position;

    public function __construct(Client $redisClient) {
        $this->_redisClient   = $redisClient;
        $this->_isStillValid  = true;
        $this->_subscriptions = false;
    }

    public function __destruct() {
        if ($this->valid()) {
            $this->_redisClient->unsubscribe();
            $this->_redisClient->punsubscribe();
        }
    }

    public function subscribe(/* arguments */) {
        $this->writeCommand(self::SUBSCRIBE, func_get_args());
        $this->_subscriptions = true;
    }

    public function unsubscribe(/* arguments */) {
        $this->writeCommand(self::UNSUBSCRIBE, func_get_args());
    }

    public function psubscribe(/* arguments */) {
        $this->writeCommand(self::PSUBSCRIBE, func_get_args());
        $this->_subscriptions = true;
    }

    public function punsubscribe(/* arguments */) {
        $this->writeCommand(self::PUNSUBSCRIBE, func_get_args());
    }

    public function closeContext() {
        if ($this->valid()) {
            // TODO: as an optimization, we should not send both 
            //       commands if one of them has not been issued.
            $this->unsubscribe();
            $this->punsubscribe();
        }
    }

    private function writeCommand($method, $arguments) {
        if (count($arguments) === 1 && is_array($arguments[0])) {
            $arguments = $arguments[0];
        }
        $command = $this->_redisClient->createCommand($method, $arguments);
        $this->_redisClient->getConnection()->writeCommand($command);
    }

    public function rewind() {
        // NOOP
    }

    public function current() {
        return $this->getValue();
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        if ($this->_isStillValid) {
            $this->_position++;
        }
        return $this->_position;
    }

    public function valid() {
        return $this->_subscriptions && $this->_isStillValid;
    }

    private function invalidate() {
        $this->_isStillValid = false;
        $this->_subscriptions = false;
    }

    private function getValue() {
        $reader     = $this->_redisClient->getResponseReader();
        $connection = $this->_redisClient->getConnection();
        $response   = $reader->read($connection);

        switch ($response[0]) {
            case self::SUBSCRIBE:
            case self::UNSUBSCRIBE:
            case self::PSUBSCRIBE:
            case self::PUNSUBSCRIBE:
                if ($response[2] === 0) {
                    $this->invalidate();
                }
            case self::MESSAGE:
                return (object) array(
                    'kind'    => $response[0],
                    'channel' => $response[1],
                    'payload' => $response[2],
                );
            case self::PMESSAGE:
                return (object) array(
                    'kind'    => $response[0],
                    'pattern' => $response[1],
                    'channel' => $response[2],
                    'payload' => $response[3],
                );
            default:
                throw new \Predis\ClientException(
                    "Received an unknown message type {$response[0]} inside of a pubsub context"
                );
        }
    }
}

/* ------------------------------------------------------------------------- */

class ConnectionParameters {
    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 6379;
    const DEFAULT_TIMEOUT = 5;
    private $_parameters;

    public function __construct($parameters = null) {
        $parameters = $parameters ?: array();
        //var_dump($parameters);echo "<br>";
        $this->_parameters = is_array($parameters) 
            ? self::filterConnectionParams($parameters) 
            : self::parseURI($parameters);
    }

    private static function parseURI($uri) {
        $parsed = @parse_url($uri);

        if ($parsed == false || $parsed['scheme'] != 'redis' || $parsed['host'] == null) {
            throw new ClientException("Invalid URI: $uri");
        }

        if (array_key_exists('query', $parsed)) {
            $details = array();
            foreach (explode('&', $parsed['query']) as $kv) {
                list($k, $v) = explode('=', $kv);
                switch ($k) {
                    case 'database':
                        $details['database'] = $v;
                        break;
                    case 'password':
                        $details['password'] = $v;
                        break;
                    case 'connection_async':
                        $details['connection_async'] = $v;
                        break;
                    case 'connection_persistent':
                        $details['connection_persistent'] = $v;
                        break;
                    case 'connection_timeout':
                        $details['connection_timeout'] = $v;
                        break;
                    case 'read_write_timeout':
                        $details['read_write_timeout'] = $v;
                        break;
                    case 'alias':
                        $details['alias'] = $v;
                        break;
                    case 'weight':
                        $details['weight'] = $v;
                        break;
                }
            }
            $parsed = array_merge($parsed, $details);
        }

        return self::filterConnectionParams($parsed);
    }

    private static function getParamOrDefault(Array $parameters, $param, $default = null) {
        return array_key_exists($param, $parameters) ? $parameters[$param] : $default;
    }

    private static function filterConnectionParams($parameters) {
    	//echo self::getParamOrDefault($parameters, 'host', self::DEFAULT_HOST)."<br>";
        return array(
            'host' => self::getParamOrDefault($parameters, 'host', self::DEFAULT_HOST), 
            'port' => (int) self::getParamOrDefault($parameters, 'port', self::DEFAULT_PORT), 
            'database' => self::getParamOrDefault($parameters, 'database'), 
            'password' => self::getParamOrDefault($parameters, 'password'), 
            'connection_async'   => self::getParamOrDefault($parameters, 'connection_async', false), 
            'connection_persistent' => self::getParamOrDefault($parameters, 'connection_persistent', false), 
            'connection_timeout' => self::getParamOrDefault($parameters, 'connection_timeout', self::DEFAULT_TIMEOUT), 
            'read_write_timeout' => self::getParamOrDefault($parameters, 'read_write_timeout'), 
            'alias'  => self::getParamOrDefault($parameters, 'alias'), 
            'weight' => self::getParamOrDefault($parameters, 'weight'), 
        );
    }

    public function __get($parameter) {
        return $this->_parameters[$parameter];
    }

    public function __isset($parameter) {
        return isset($this->_parameters[$parameter]);
    }
}

interface IConnection {
    public function connect();
    public function disconnect();
    public function isConnected();
    public function writeCommand(Command $command);
    public function readResponse(Command $command);
    public function executeCommand(Command $command);
}

class Connection implements IConnection {
    private $_params, $_socket, $_initCmds, $_reader;

    public function __construct(ConnectionParameters $parameters, ResponseReader $reader = null) {
    	//var_dump($parameters);echo"<br>";
        $this->_params   = $parameters;
        $this->_initCmds = array();
        $this->_reader   = $reader ?: new ResponseReader();
    }

    public function __destruct() {
        if (!$this->_params->connection_persistent) {
            $this->disconnect();
        }
    }

    public function isConnected() {
        return is_resource($this->_socket);
    }

    public function connect() {
        if ($this->isConnected()) {
            throw new ClientException('Connection already estabilished');
        }
        $uri = sprintf('tcp://%s:%d/', $this->_params->host, $this->_params->port);
        $connectFlags = STREAM_CLIENT_CONNECT;
        if ($this->_params->connection_async) {
            $connectFlags |= STREAM_CLIENT_ASYNC_CONNECT;
        }
        if ($this->_params->connection_persistent) {
            $connectFlags |= STREAM_CLIENT_PERSISTENT;
        }
        $this->_socket = @stream_socket_client(
            $uri, $errno, $errstr, $this->_params->connection_timeout, $connectFlags
        );

        if (!$this->_socket) {
            $this->onCommunicationException(trim($errstr), $errno);
        }

        if (isset($this->_params->read_write_timeout)) {
            $timeoutSeconds  = floor($this->_params->read_write_timeout);
            $timeoutUSeconds = ($this->_params->read_write_timeout - $timeoutSeconds) * 1000000;
            stream_set_timeout($this->_socket, $timeoutSeconds, $timeoutUSeconds);
        }

        if (count($this->_initCmds) > 0){
            $this->sendInitializationCommands();
        }
    }

    public function disconnect() {
        if ($this->isConnected()) {
            fclose($this->_socket);
        }
    }

    public function pushInitCommand(Command $command){
        $this->_initCmds[] = $command;
    }

    private function sendInitializationCommands() {
        foreach ($this->_initCmds as $command) {
            $this->writeCommand($command);
        }
        foreach ($this->_initCmds as $command) {
            $this->readResponse($command);
        }
    }

    private function onCommunicationException($message, $code = null) {
        Shared\Utils::onCommunicationException(
            new CommunicationException($this, $message, $code)
        );
    }

    public function writeCommand(Command $command) {
        $this->writeBytes($command());
    }

    public function readResponse(Command $command) {
        $response = $this->_reader->read($this);
        $skipparse = isset($response->queued) || isset($response->error);
        return $skipparse ? $response : $command->parseResponse($response);
    }

    public function executeCommand(Command $command) {
        $this->writeCommand($command);
        if ($command->closesConnection()) {
            return $this->disconnect();
        }
        return $this->readResponse($command);
    }

    public function rawCommand($rawCommandData, $closesConnection = false) {
        $this->writeBytes($rawCommandData);
        if ($closesConnection) {
            $this->disconnect();
            return;
        }
        return $this->_reader->read($this);
    }

    public function writeBytes($value) {
        $socket = $this->getSocket();
        while (($length = strlen($value)) > 0) {
            $written = fwrite($socket, $value);
            if ($length === $written) {
                return true;
            }
            if ($written === false || $written === 0) {
                $this->onCommunicationException('Error while writing bytes to the server');
            }
            $value = substr($value, $written);
        }
        return true;
    }

    public function readBytes($length) {
        if ($length == 0) {
            throw new \InvalidArgumentException('Length parameter must be greater than 0');
        }
        $socket = $this->getSocket();
        $value  = '';
        do {
            $chunk = fread($socket, $length);
            if ($chunk === false || $chunk === '') {
                $this->onCommunicationException('Error while reading bytes from the server');
            }
            $value .= $chunk;
        }
        while (($length -= strlen($chunk)) > 0);
        return $value;
    }

    public function readLine() {
        $socket = $this->getSocket();
        $value  = '';
        do {
            $chunk = fgets($socket);
            if ($chunk === false || strlen($chunk) == 0) {
                $this->onCommunicationException('Error while reading line from the server');
            }
            $value .= $chunk;
        }
        while (substr($value, -2) !== Protocol::NEWLINE);
        return substr($value, 0, -2);
    }

    public function getSocket() {
        if (!$this->isConnected()) {
            $this->connect();
        }
        return $this->_socket;
    }

    public function getResponseReader() {
        return $this->_reader;
    }

    public function getParameters() {
        return $this->_params;
    }

    public function __toString() {
        return sprintf('%s:%d', $this->_params->host, $this->_params->port);
    }
}

class ConnectionCluster implements IConnection, \IteratorAggregate {
    private $_pool, $_distributor;

    public function __construct(Distribution\IDistributionAlgorithm $distributor = null) {
        $this->_pool = array();
        $this->_distributor = $distributor ?: new Distribution\HashRing();
    }

    public function isConnected() {
        foreach ($this->_pool as $connection) {
            if ($connection->isConnected()) {
                return true;
            }
        }
        return false;
    }

    public function connect() {
        foreach ($this->_pool as $connection) {
            $connection->connect();
        }
    }

    public function disconnect() {
        foreach ($this->_pool as $connection) {
            $connection->disconnect();
        }
    }

    public function add(Connection $connection) {
        $parameters = $connection->getParameters();
        if (isset($parameters->alias)) {
            $this->_pool[$parameters->alias] = $connection;
        }
        else {
            $this->_pool[] = $connection;
        }
        $this->_distributor->add($connection, $parameters->weight);
    }

    public function getConnection(Command $command) {
        if ($command->canBeHashed() === false) {
            throw new ClientException(
                sprintf("Cannot send '%s' commands to a cluster of connections.", $command->getCommandId())
            );
        }
        return $this->_distributor->get($command->getHash($this->_distributor));
    }

    public function getConnectionById($id = null) {
        return $this->_pool[$id ?: 0];
    }

    public function getIterator() {
        return new \ArrayIterator($this->_pool);
    }

    public function writeCommand(Command $command) {
        $this->getConnection($command)->writeCommand($command);
    }

    public function readResponse(Command $command) {
        return $this->getConnection($command)->readResponse($command);
    }

    public function executeCommand(Command $command) {
        $connection = $this->getConnection($command);
        $connection->writeCommand($command);
        return $connection->readResponse($command);
    }
}

/* ------------------------------------------------------------------------- */

abstract class RedisServerProfile {
    private static $_serverProfiles;
    private $_registeredCommands;

    public function __construct() {
        $this->_registeredCommands = $this->getSupportedCommands();
    }

    public abstract function getVersion();

    protected abstract function getSupportedCommands();

    public static function getDefault() {
        return self::get('default');
    }

    private static function predisServerProfiles() {
        return array(
            '1.2'     => '\Predis\RedisServer_v1_2',
            '2.0'     => '\Predis\RedisServer_v2_0',
            'default' => '\Predis\RedisServer_v2_0',
            'dev'     => '\Predis\RedisServer_vNext',
        );
    }

    public static function registerProfile($profileClass, $aliases) {
        if (!isset(self::$_serverProfiles)) {
            self::$_serverProfiles = self::predisServerProfiles();
        }

        $profileReflection = new \ReflectionClass($profileClass);

        if (!$profileReflection->isSubclassOf('\Predis\RedisServerProfile')) {
            throw new ClientException("Cannot register '$profileClass' as it is not a valid profile class");
        }

        if (is_array($aliases)) {
            foreach ($aliases as $alias) {
                self::$_serverProfiles[$alias] = $profileClass;
            }
        }
        else {
            self::$_serverProfiles[$aliases] = $profileClass;
        }
    }

    public static function get($version) {
        if (!isset(self::$_serverProfiles)) {
            self::$_serverProfiles = self::predisServerProfiles();
        }
        if (!isset(self::$_serverProfiles[$version])) {
            throw new ClientException("Unknown server profile: $version");
        }
        $profile = self::$_serverProfiles[$version];
        return new $profile();
    }

    public function compareWith($version, $operator = null) {
        // one could expect that PHP's version_compare would behave 
        // the same way if invoked with 2 arguments or 3 arguments 
        // with the third being NULL, but it is not like that.
        // TODO: since version_compare considers 1 < 1.0 < 1.0.0, 
        //       we might need to revise the behavior of this method.
        return ($operator === null 
            ? version_compare($this, $version)
            : version_compare($this, $version, $operator)
        );
    }

    public function supportsCommand($command) {
        return isset($this->_registeredCommands[$command]);
    }

    public function createCommand($method, $arguments = array()) {
        if (!isset($this->_registeredCommands[$method])) {
            throw new ClientException("'$method' is not a registered Redis command");
        }
        $commandClass = $this->_registeredCommands[$method];
        $command = new $commandClass();
        $command->setArgumentsArray($arguments);
        return $command;
    }

    public function registerCommands(Array $commands) {
        foreach ($commands as $command => $aliases) {
            $this->registerCommand($command, $aliases);
        }
    }

    public function registerCommand($command, $aliases) {
        $commandReflection = new \ReflectionClass($command);

        if (!$commandReflection->isSubclassOf('\Predis\Command')) {
            throw new ClientException("Cannot register '$command' as it is not a valid Redis command");
        }

        if (is_array($aliases)) {
            foreach ($aliases as $alias) {
                $this->_registeredCommands[$alias] = $command;
            }
        }
        else {
            $this->_registeredCommands[$aliases] = $command;
        }
    }

    public function __toString() {
        return $this->getVersion();
    }
}

class RedisServer_v1_2 extends RedisServerProfile {
    public function getVersion() { return '1.2'; }
    public function getSupportedCommands() {
        return array(
            /* miscellaneous commands */
            'ping'      => '\Predis\Commands\Ping',
            'echo'      => '\Predis\Commands\DoEcho',
            'auth'      => '\Predis\Commands\Auth',

            /* connection handling */
            'quit'      => '\Predis\Commands\Quit',

            /* commands operating on string values */
            'set'                     => '\Predis\Commands\Set',
            'setnx'                   => '\Predis\Commands\SetPreserve',
                'setPreserve'         => '\Predis\Commands\SetPreserve',
            'mset'                    => '\Predis\Commands\SetMultiple',
                'setMultiple'         => '\Predis\Commands\SetMultiple',
            'msetnx'                  => '\Predis\Commands\SetMultiplePreserve',
                'setMultiplePreserve' => '\Predis\Commands\SetMultiplePreserve',
            'get'                     => '\Predis\Commands\Get',
            'mget'                    => '\Predis\Commands\GetMultiple',
                'getMultiple'         => '\Predis\Commands\GetMultiple',
            'getset'                  => '\Predis\Commands\GetSet',
                'getSet'              => '\Predis\Commands\GetSet',
            'incr'                    => '\Predis\Commands\Increment',
                'increment'           => '\Predis\Commands\Increment',
            'incrby'                  => '\Predis\Commands\IncrementBy',
                'incrementBy'         => '\Predis\Commands\IncrementBy',
            'decr'                    => '\Predis\Commands\Decrement',
                'decrement'           => '\Predis\Commands\Decrement',
            'decrby'                  => '\Predis\Commands\DecrementBy',
                'decrementBy'         => '\Predis\Commands\DecrementBy',
            'exists'                  => '\Predis\Commands\Exists',
            'del'                     => '\Predis\Commands\Delete',
                'delete'              => '\Predis\Commands\Delete',
            'type'                    => '\Predis\Commands\Type',

            /* commands operating on the key space */
            'keys'               => '\Predis\Commands\Keys',
            'randomkey'          => '\Predis\Commands\RandomKey',
                'randomKey'      => '\Predis\Commands\RandomKey',
            'rename'             => '\Predis\Commands\Rename',
            'renamenx'           => '\Predis\Commands\RenamePreserve',
                'renamePreserve' => '\Predis\Commands\RenamePreserve',
            'expire'             => '\Predis\Commands\Expire',
            'expireat'           => '\Predis\Commands\ExpireAt',
                'expireAt'       => '\Predis\Commands\ExpireAt',
            'dbsize'             => '\Predis\Commands\DatabaseSize',
                'databaseSize'   => '\Predis\Commands\DatabaseSize',
            'ttl'                => '\Predis\Commands\TimeToLive',
                'timeToLive'     => '\Predis\Commands\TimeToLive',

            /* commands operating on lists */
            'rpush'            => '\Predis\Commands\ListPushTail',
                'pushTail'     => '\Predis\Commands\ListPushTail',
            'lpush'            => '\Predis\Commands\ListPushHead',
                'pushHead'     => '\Predis\Commands\ListPushHead',
            'llen'             => '\Predis\Commands\ListLength',
                'listLength'   => '\Predis\Commands\ListLength',
            'lrange'           => '\Predis\Commands\ListRange',
                'listRange'    => '\Predis\Commands\ListRange',
            'ltrim'            => '\Predis\Commands\ListTrim',
                'listTrim'     => '\Predis\Commands\ListTrim',
            'lindex'           => '\Predis\Commands\ListIndex',
                'listIndex'    => '\Predis\Commands\ListIndex',
            'lset'             => '\Predis\Commands\ListSet',
                'listSet'      => '\Predis\Commands\ListSet',
            'lrem'             => '\Predis\Commands\ListRemove',
                'listRemove'   => '\Predis\Commands\ListRemove',
            'lpop'             => '\Predis\Commands\ListPopFirst',
                'popFirst'     => '\Predis\Commands\ListPopFirst',
            'rpop'             => '\Predis\Commands\ListPopLast',
                'popLast'      => '\Predis\Commands\ListPopLast',
            'rpoplpush'        => '\Predis\Commands\ListPopLastPushHead',
                'listPopLastPushHead'  => '\Predis\Commands\ListPopLastPushHead',

            /* commands operating on sets */
            'sadd'                      => '\Predis\Commands\SetAdd', 
                'setAdd'                => '\Predis\Commands\SetAdd',
            'srem'                      => '\Predis\Commands\SetRemove', 
                'setRemove'             => '\Predis\Commands\SetRemove',
            'spop'                      => '\Predis\Commands\SetPop',
                'setPop'                => '\Predis\Commands\SetPop',
            'smove'                     => '\Predis\Commands\SetMove', 
                'setMove'               => '\Predis\Commands\SetMove',
            'scard'                     => '\Predis\Commands\SetCardinality', 
                'setCardinality'        => '\Predis\Commands\SetCardinality',
            'sismember'                 => '\Predis\Commands\SetIsMember', 
                'setIsMember'           => '\Predis\Commands\SetIsMember',
            'sinter'                    => '\Predis\Commands\SetIntersection', 
                'setIntersection'       => '\Predis\Commands\SetIntersection',
            'sinterstore'               => '\Predis\Commands\SetIntersectionStore', 
                'setIntersectionStore'  => '\Predis\Commands\SetIntersectionStore',
            'sunion'                    => '\Predis\Commands\SetUnion', 
                'setUnion'              => '\Predis\Commands\SetUnion',
            'sunionstore'               => '\Predis\Commands\SetUnionStore', 
                'setUnionStore'         => '\Predis\Commands\SetUnionStore',
            'sdiff'                     => '\Predis\Commands\SetDifference', 
                'setDifference'         => '\Predis\Commands\SetDifference',
            'sdiffstore'                => '\Predis\Commands\SetDifferenceStore', 
                'setDifferenceStore'    => '\Predis\Commands\SetDifferenceStore',
            'smembers'                  => '\Predis\Commands\SetMembers', 
                'setMembers'            => '\Predis\Commands\SetMembers',
            'srandmember'               => '\Predis\Commands\SetRandomMember', 
                'setRandomMember'       => '\Predis\Commands\SetRandomMember',

            /* commands operating on sorted sets */
            'zadd'                          => '\Predis\Commands\ZSetAdd',
                'zsetAdd'                   => '\Predis\Commands\ZSetAdd',
            'zincrby'                       => '\Predis\Commands\ZSetIncrementBy',
                'zsetIncrementBy'           => '\Predis\Commands\ZSetIncrementBy',
            'zrem'                          => '\Predis\Commands\ZSetRemove',
                'zsetRemove'                => '\Predis\Commands\ZSetRemove',
            'zrange'                        => '\Predis\Commands\ZSetRange',
                'zsetRange'                 => '\Predis\Commands\ZSetRange',
            'zrevrange'                     => '\Predis\Commands\ZSetReverseRange',
                'zsetReverseRange'          => '\Predis\Commands\ZSetReverseRange',
            'zrangebyscore'                 => '\Predis\Commands\ZSetRangeByScore',
                'zsetRangeByScore'          => '\Predis\Commands\ZSetRangeByScore',
            'zcard'                         => '\Predis\Commands\ZSetCardinality',
                'zsetCardinality'           => '\Predis\Commands\ZSetCardinality',
            'zscore'                        => '\Predis\Commands\ZSetScore',
                'zsetScore'                 => '\Predis\Commands\ZSetScore',
            'zremrangebyscore'              => '\Predis\Commands\ZSetRemoveRangeByScore',
                'zsetRemoveRangeByScore'    => '\Predis\Commands\ZSetRemoveRangeByScore',

            /* multiple databases handling commands */
            'select'                => '\Predis\Commands\SelectDatabase', 
                'selectDatabase'    => '\Predis\Commands\SelectDatabase',
            'move'                  => '\Predis\Commands\MoveKey', 
                'moveKey'           => '\Predis\Commands\MoveKey',
            'flushdb'               => '\Predis\Commands\FlushDatabase', 
                'flushDatabase'     => '\Predis\Commands\FlushDatabase',
            'flushall'              => '\Predis\Commands\FlushAll', 
                'flushDatabases'    => '\Predis\Commands\FlushAll',

            /* sorting */
            'sort'                  => '\Predis\Commands\Sort',

            /* remote server control commands */
            'info'                  => '\Predis\Commands\Info',
            'slaveof'               => '\Predis\Commands\SlaveOf', 
                'slaveOf'           => '\Predis\Commands\SlaveOf',

            /* persistence control commands */
            'save'                  => '\Predis\Commands\Save',
            'bgsave'                => '\Predis\Commands\BackgroundSave', 
                'backgroundSave'    => '\Predis\Commands\BackgroundSave',
            'lastsave'              => '\Predis\Commands\LastSave', 
                'lastSave'          => '\Predis\Commands\LastSave',
            'shutdown'              => '\Predis\Commands\Shutdown',
            'bgrewriteaof'                      =>  '\Predis\Commands\BackgroundRewriteAppendOnlyFile',
            'backgroundRewriteAppendOnlyFile'   =>  '\Predis\Commands\BackgroundRewriteAppendOnlyFile',
        );
    }
}

class RedisServer_v2_0 extends RedisServer_v1_2 {
    public function getVersion() { return '2.0'; }
    public function getSupportedCommands() {
        return array_merge(parent::getSupportedCommands(), array(
            /* transactions */
            'multi'                     => '\Predis\Commands\Multi',
            'exec'                      => '\Predis\Commands\Exec',
            'discard'                   => '\Predis\Commands\Discard',

            /* commands operating on string values */
            'setex'                     => '\Predis\Commands\SetExpire',
                'setExpire'             => '\Predis\Commands\SetExpire',
            'append'                    => '\Predis\Commands\Append',
            'substr'                    => '\Predis\Commands\Substr',

            /* commands operating on lists */
            'blpop'                     => '\Predis\Commands\ListPopFirstBlocking',
                'popFirstBlocking'      => '\Predis\Commands\ListPopFirstBlocking',
            'brpop'                     => '\Predis\Commands\ListPopLastBlocking',
                'popLastBlocking'       => '\Predis\Commands\ListPopLastBlocking',

            /* commands operating on sorted sets */
            'zunionstore'               => '\Predis\Commands\ZSetUnionStore',
                'zsetUnionStore'        => '\Predis\Commands\ZSetUnionStore',
            'zinterstore'               => '\Predis\Commands\ZSetIntersectionStore',
                'zsetIntersectionStore' => '\Predis\Commands\ZSetIntersectionStore',
            'zcount'                    => '\Predis\Commands\ZSetCount',
                'zsetCount'             => '\Predis\Commands\ZSetCount',
            'zrank'                     => '\Predis\Commands\ZSetRank',
                'zsetRank'              => '\Predis\Commands\ZSetRank',
            'zrevrank'                  => '\Predis\Commands\ZSetReverseRank',
                'zsetReverseRank'       => '\Predis\Commands\ZSetReverseRank',
            'zremrangebyrank'           => '\Predis\Commands\ZSetRemoveRangeByRank',
                'zsetRemoveRangeByRank' => '\Predis\Commands\ZSetRemoveRangeByRank',

            /* commands operating on hashes */
            'hset'                      => '\Predis\Commands\HashSet',
                'hashSet'               => '\Predis\Commands\HashSet',
            'hsetnx'                    => '\Predis\Commands\HashSetPreserve',
                'hashSetPreserve'       => '\Predis\Commands\HashSetPreserve',
            'hmset'                     => '\Predis\Commands\HashSetMultiple',
                'hashSetMultiple'       => '\Predis\Commands\HashSetMultiple',
            'hincrby'                   => '\Predis\Commands\HashIncrementBy',
                'hashIncrementBy'       => '\Predis\Commands\HashIncrementBy',
            'hget'                      => '\Predis\Commands\HashGet',
                'hashGet'               => '\Predis\Commands\HashGet',
            'hmget'                     => '\Predis\Commands\HashGetMultiple',
                'hashGetMultiple'       => '\Predis\Commands\HashGetMultiple',
            'hdel'                      => '\Predis\Commands\HashDelete',
                'hashDelete'            => '\Predis\Commands\HashDelete',
            'hexists'                   => '\Predis\Commands\HashExists',
                'hashExists'            => '\Predis\Commands\HashExists',
            'hlen'                      => '\Predis\Commands\HashLength',
                'hashLength'            => '\Predis\Commands\HashLength',
            'hkeys'                     => '\Predis\Commands\HashKeys',
                'hashKeys'              => '\Predis\Commands\HashKeys',
            'hvals'                     => '\Predis\Commands\HashValues',
                'hashValues'            => '\Predis\Commands\HashValues',
            'hgetall'                   => '\Predis\Commands\HashGetAll',
                'hashGetAll'            => '\Predis\Commands\HashGetAll',

            /* publish - subscribe */
            'subscribe'                 => '\Predis\Commands\Subscribe',
            'unsubscribe'               => '\Predis\Commands\Unsubscribe',
            'psubscribe'                => '\Predis\Commands\SubscribeByPattern',
            'punsubscribe'              => '\Predis\Commands\UnsubscribeByPattern',
            'publish'                   => '\Predis\Commands\Publish',

            /* remote server control commands */
            'config'                    => '\Predis\Commands\Config',
                'configuration'         => '\Predis\Commands\Config',
        ));
    }
}

class RedisServer_vNext extends RedisServer_v2_0 {
    public function getVersion() { return '2.1'; }
}

/* ------------------------------------------------------------------------- */

namespace Predis\Distribution;

interface IDistributionAlgorithm {
    public function add($node, $weight = null);
    public function remove($node);
    public function get($key);
    public function generateKey($value);
}

class EmptyRingException extends \Exception { }

class HashRing implements IDistributionAlgorithm {
    const DEFAULT_REPLICAS = 128;
    const DEFAULT_WEIGHT   = 100;
    private $_nodes, $_ring, $_ringKeys, $_ringKeysCount, $_replicas;

    public function __construct($replicas = self::DEFAULT_REPLICAS) {
        $this->_replicas = $replicas;
        $this->_nodes    = array();
    }

    public function add($node, $weight = null) {
        // NOTE: in case of collisions in the hashes of the nodes, the node added
        //       last wins, thus the order in which nodes are added is significant.
        $this->_nodes[] = array('object' => $node, 'weight' => (int) $weight ?: $this::DEFAULT_WEIGHT);
        $this->reset();
    }

    public function remove($node) {
        // NOTE: a node is removed by resetting the ring so that it's recreated from 
        //       scratch, in order to reassign possible hashes with collisions to the 
        //       right node according to the order in which they were added in the 
        //       first place.
        for ($i = 0; $i < count($this->_nodes); ++$i) {
            if ($this->_nodes[$i]['object'] === $node) {
                array_splice($this->_nodes, $i, 1);
                $this->reset();
                break;
            }
        }
    }

    private function reset() {
        unset($this->_ring);
        unset($this->_ringKeys);
        unset($this->_ringKeysCount);
    }

    private function isInitialized() {
        return isset($this->_ringKeys);
    }

    private function computeTotalWeight() {
        // TODO: array_reduce + lambda for PHP 5.3
        $totalWeight = 0;
        foreach ($this->_nodes as $node) {
            $totalWeight += $node['weight'];
        }
        return $totalWeight;
    }

    private function initialize() {
        if ($this->isInitialized()) {
            return;
        }
        if (count($this->_nodes) === 0) {
            throw new EmptyRingException('Cannot initialize empty hashring');
        }

        $this->_ring = array();
        $totalWeight = $this->computeTotalWeight();
        $nodesCount  = count($this->_nodes);
        foreach ($this->_nodes as $node) {
            $weightRatio = $node['weight'] / $totalWeight;
            $this->addNodeToRing($this->_ring, $node, $nodesCount, $this->_replicas, $weightRatio);
        }
        ksort($this->_ring, SORT_NUMERIC);
        $this->_ringKeys = array_keys($this->_ring);
        $this->_ringKeysCount = count($this->_ringKeys);
    }

    protected function addNodeToRing(&$ring, $node, $totalNodes, $replicas, $weightRatio) {
        $nodeObject = $node['object'];
        $nodeHash = (string) $nodeObject;
        $replicas = (int) round($weightRatio * $totalNodes * $replicas);
        for ($i = 0; $i < $replicas; $i++) {
            $key = crc32("$nodeHash:$i");
            $ring[$key] = $nodeObject;
        }
    }

    public function generateKey($value) {
        return crc32($value);
    }

    public function get($key) {
        return $this->_ring[$this->getNodeKey($key)];
    }

    private function getNodeKey($key) {
        $this->initialize();
        $ringKeys = $this->_ringKeys;
        $upper = $this->_ringKeysCount - 1;
        $lower = 0;

        while ($lower <= $upper) {
            $index = ($lower + $upper) >> 1;
            $item  = $ringKeys[$index];
            if ($item > $key) {
                $upper = $index - 1;
            }
            else if ($item < $key) {
                $lower = $index + 1;
            }
            else {
                return $item;
            }
        }
        return $ringKeys[$this->wrapAroundStrategy($upper, $lower, $this->_ringKeysCount)];
    }

    protected function wrapAroundStrategy($upper, $lower, $ringKeysCount) {
        // NOTE: binary search for the last item in _ringkeys with a value 
        //       less or equal to the key. If no such item exists, return the 
        //       last item.
        return $upper >= 0 ? $upper : $ringKeysCount - 1;
    }
}

class KetamaPureRing extends HashRing {
    const DEFAULT_REPLICAS = 160;

    public function __construct() {
        parent::__construct($this::DEFAULT_REPLICAS);
    }

    protected function addNodeToRing(&$ring, $node, $totalNodes, $replicas, $weightRatio) {
        $nodeObject = $node['object'];
        $nodeHash = (string) $nodeObject;
        $replicas = (int) floor($weightRatio * $totalNodes * ($replicas / 4));
        for ($i = 0; $i < $replicas; $i++) {
            $unpackedDigest = unpack('V4', md5("$nodeHash-$i", true));
            foreach ($unpackedDigest as $key) {
                $ring[$key] = $nodeObject;
            }
        }
    }

    public function generateKey($value) {
        $hash = unpack('V', md5($value, true));
        return $hash[1];
    }

    protected function wrapAroundStrategy($upper, $lower, $ringKeysCount) {
        // NOTE: binary search for the first item in _ringkeys with a value 
        //       greater or equal to the key. If no such item exists, return the 
        //       first item.
        return $lower < $ringKeysCount ? $lower : 0;
    }
}

/* ------------------------------------------------------------------------- */

namespace Predis\Shared;

class Utils {
    public static function onCommunicationException(\Predis\CommunicationException $exception) {
        if ($exception->shouldResetConnection()) {
            $connection = $exception->getConnection();
            if ($connection->isConnected()) {
                $connection->disconnect();
            }
        }
        throw $exception;
    }
}

abstract class MultiBulkResponseIteratorBase implements \Iterator, \Countable {
    protected $_position, $_current, $_replySize;

    public function rewind() {
        // NOOP
    }

    public function current() {
        return $this->_current;
    }

    public function key() {
        return $this->_position;
    }

    public function next() {
        if (++$this->_position < $this->_replySize) {
            $this->_current = $this->getValue();
        }
        return $this->_position;
    }

    public function valid() {
        return $this->_position < $this->_replySize;
    }

    public function count() {
        // NOTE: use count if you want to get the size of the current multi-bulk 
        //       response without using iterator_count (which actually consumes 
        //       our iterator to calculate the size, and we cannot perform a rewind)
        return $this->_replySize;
    }

    protected abstract function getValue();
}

class MultiBulkResponseIterator extends MultiBulkResponseIteratorBase {
    private $_connection;

    public function __construct(\Predis\Connection $connection, $size) {
        $this->_connection = $connection;
        $this->_reader     = $connection->getResponseReader();
        $this->_position   = 0;
        $this->_current    = $size > 0 ? $this->getValue() : null;
        $this->_replySize  = $size;
    }

    public function __destruct() {
        // when the iterator is garbage-collected (e.g. it goes out of the
        // scope of a foreach) but it has not reached its end, we must sync
        // the client with the queued elements that have not been read from
        // the connection with the server.
        $this->sync();
    }

    public function sync() {
        while ($this->valid()) {
            $this->next();
        }
    }

    protected function getValue() {
        return $this->_reader->read($this->_connection);
    }
}

class MultiBulkResponseKVIterator extends MultiBulkResponseIteratorBase {
    private $_iterator;

    public function __construct(MultiBulkResponseIterator $iterator) {
        $virtualSize = count($iterator) / 2;

        $this->_iterator   = $iterator;
        $this->_position   = 0;
        $this->_current    = $virtualSize > 0 ? $this->getValue() : null;
        $this->_replySize  = $virtualSize;
    }

    public function __destruct() {
        $this->_iterator->sync();
    }

    protected function getValue() {
        $k = $this->_iterator->current();
        $this->_iterator->next();
        $v = $this->_iterator->current();
        $this->_iterator->next();
        return array($k, $v);
    }
}

/* ------------------------------------------------------------------------- */

namespace Predis\Commands;

/* miscellaneous commands */
class Ping extends  \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'PING'; }
    public function parseResponse($data) {
        return $data === 'PONG' ? true : false;
    }
}

class DoEcho extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'ECHO'; }
}

class Auth extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'AUTH'; }
}

/* connection handling */
class Quit extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'QUIT'; }
    public function closesConnection() { return true; }
}

/* commands operating on string values */
class Set extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SET'; }
}

class SetExpire extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SETEX'; }
}

class SetPreserve extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SETNX'; }
    public function parseResponse($data) { return (bool) $data; }
}

class SetMultiple extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MSET'; }
    public function filterArguments(Array $arguments) {
        if (count($arguments) === 1 && is_array($arguments[0])) {
            $flattenedKVs = array();
            $args = &$arguments[0];
            foreach ($args as $k => $v) {
                $flattenedKVs[] = $k;
                $flattenedKVs[] = $v;
            }
            return $flattenedKVs;
        }
        return $arguments;
    }
}

class SetMultiplePreserve extends \Predis\Commands\SetMultiple {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MSETNX'; }
    public function parseResponse($data) { return (bool) $data; }
}

class Get extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'GET'; }
}

class GetMultiple extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MGET'; }
}

class GetSet extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'GETSET'; }
}

class Increment extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'INCR'; }
}

class IncrementBy extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'INCRBY'; }
}

class Decrement extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'DECR'; }
}

class DecrementBy extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'DECRBY'; }
}

class Exists extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'EXISTS'; }
    public function parseResponse($data) { return (bool) $data; }
}

class Delete extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'DEL'; }
}

class Type extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'TYPE'; }
}

class Append extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'APPEND'; }
}

class Substr extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SUBSTR'; }
}

/* commands operating on the key space */
class Keys extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'KEYS'; }
    public function parseResponse($data) { 
        // TODO: is this behaviour correct?
        if (is_array($data) || $data instanceof \Iterator) {
            return $data;
        }
        return strlen($data) > 0 ? explode(' ', $data) : array();
    }
}

class RandomKey extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'RANDOMKEY'; }
    public function parseResponse($data) { return $data !== '' ? $data : null; }
}

class Rename extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'RENAME'; }
}

class RenamePreserve extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'RENAMENX'; }
    public function parseResponse($data) { return (bool) $data; }
}

class Expire extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'EXPIRE'; }
    public function parseResponse($data) { return (bool) $data; }
}

class ExpireAt extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'EXPIREAT'; }
    public function parseResponse($data) { return (bool) $data; }
}

class DatabaseSize extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'DBSIZE'; }
}

class TimeToLive extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'TTL'; }
}

/* commands operating on lists */
class ListPushTail extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'RPUSH'; }
}

class ListPushHead extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LPUSH'; }
}

class ListLength extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LLEN'; }
}

class ListRange extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LRANGE'; }
}

class ListTrim extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LTRIM'; }
}

class ListIndex extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LINDEX'; }
}

class ListSet extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LSET'; }
}

class ListRemove extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LREM'; }
}

class ListPopLastPushHead extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'RPOPLPUSH'; }
}

class ListPopLastPushHeadBulk extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'RPOPLPUSH'; }
}

class ListPopFirst extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'LPOP'; }
}

class ListPopLast extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'RPOP'; }
}

class ListPopFirstBlocking extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'BLPOP'; }
}

class ListPopLastBlocking extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'BRPOP'; }
}

/* commands operating on sets */
class SetAdd extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SADD'; }
    public function parseResponse($data) { return (bool) $data; }
}

class SetRemove extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SREM'; }
    public function parseResponse($data) { return (bool) $data; }
}

class SetPop  extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SPOP'; }
}

class SetMove extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SMOVE'; }
    public function parseResponse($data) { return (bool) $data; }
}

class SetCardinality extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SCARD'; }
}

class SetIsMember extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SISMEMBER'; }
    public function parseResponse($data) { return (bool) $data; }
}

class SetIntersection extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SINTER'; }
}

class SetIntersectionStore extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SINTERSTORE'; }
}

class SetUnion extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SUNION'; }
}

class SetUnionStore extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SUNIONSTORE'; }
}

class SetDifference extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SDIFF'; }
}

class SetDifferenceStore extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SDIFFSTORE'; }
}

class SetMembers extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SMEMBERS'; }
}

class SetRandomMember extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SRANDMEMBER'; }
}

/* commands operating on sorted sets */
class ZSetAdd extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZADD'; }
    public function parseResponse($data) { return (bool) $data; }
}

class ZSetIncrementBy extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZINCRBY'; }
}

class ZSetRemove extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZREM'; }
    public function parseResponse($data) { return (bool) $data; }
}

class ZSetUnionStore extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZUNIONSTORE'; }
    public function filterArguments(Array $arguments) {
        $options = array();
        $argc    = count($arguments);
        if ($argc > 1 && is_array($arguments[$argc - 1])) {
            $options = $this->prepareOptions(array_pop($arguments));
        }
        $args = is_array($arguments[0]) ? $arguments[0] : $arguments;
        return  array_merge($args, $options);
    }
    private function prepareOptions($options) {
        $opts = array_change_key_case($options, CASE_UPPER);
        $finalizedOpts = array();
        if (isset($opts['WEIGHTS']) && is_array($opts['WEIGHTS'])) {
            $finalizedOpts[] = 'WEIGHTS';
            $finalizedOpts[] = $opts['WEIGHTS'][0];
            $finalizedOpts[] = $opts['WEIGHTS'][1];
        }
        if (isset($opts['AGGREGATE'])) {
            $finalizedOpts[] = 'AGGREGATE';
            $finalizedOpts[] = $opts['AGGREGATE'];
        }
        return $finalizedOpts;
    }
}

class ZSetIntersectionStore extends \Predis\Commands\ZSetUnionStore {
    public function getCommandId() { return 'ZINTERSTORE'; }
}

class ZSetRange extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZRANGE'; }
    public function parseResponse($data) {
        $arguments = $this->getArguments();
        if (count($arguments) === 4) {
            if (strtolower($arguments[3]) === 'withscores') {
                if ($data instanceof \Iterator) {
                    return new \Predis\Shared\MultiBulkResponseKVIterator($data);
                }
                $result = array();
                for ($i = 0; $i < count($data); $i++) {
                    $result[] = array($data[$i], $data[++$i]);
                }
                return $result;
            }
        }
        return $data;
    }
}

class ZSetReverseRange extends \Predis\Commands\ZSetRange {
    public function getCommandId() { return 'ZREVRANGE'; }
}

class ZSetRangeByScore extends \Predis\Commands\ZSetRange {
    public function getCommandId() { return 'ZRANGEBYSCORE'; }
}

class ZSetCount extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZCOUNT'; }
}

class ZSetCardinality extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZCARD'; }
}

class ZSetScore extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZSCORE'; }
}

class ZSetRemoveRangeByScore extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZREMRANGEBYSCORE'; }
}

class ZSetRank extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZRANK'; }
}

class ZSetReverseRank extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZREVRANK'; }
}

class ZSetRemoveRangeByRank extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'ZREMRANGEBYRANK'; }
}

/* commands operating on hashes */
class HashSet extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HSET'; }
    public function parseResponse($data) { return (bool) $data; }
}

class HashSetPreserve extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HSETNX'; }
    public function parseResponse($data) { return (bool) $data; }
}

class HashSetMultiple extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HMSET'; }
    public function filterArguments(Array $arguments) {
        if (count($arguments) === 2 && is_array($arguments[1])) {
            $flattenedKVs = array($arguments[0]);
            $args = &$arguments[1];
            foreach ($args as $k => $v) {
                $flattenedKVs[] = $k;
                $flattenedKVs[] = $v;
            }
            return $flattenedKVs;
        }
        return $arguments;
    }
}

class HashIncrementBy extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HINCRBY'; }
}

class HashGet extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HGET'; }
}

class HashGetMultiple extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HMGET'; }
    public function filterArguments(Array $arguments) {
        if (count($arguments) === 2 && is_array($arguments[1])) {
            $flattenedKVs = array($arguments[0]);
            $args = &$arguments[1];
            foreach ($args as $v) {
                $flattenedKVs[] = $v;
            }
            return $flattenedKVs;
        }
        return $arguments;
    }
}

class HashDelete extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HDEL'; }
    public function parseResponse($data) { return (bool) $data; }
}

class HashExists extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HEXISTS'; }
    public function parseResponse($data) { return (bool) $data; }
}

class HashLength extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HLEN'; }
}

class HashKeys extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HKEYS'; }
}

class HashValues extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HVALS'; }
}

class HashGetAll extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'HGETALL'; }
    public function parseResponse($data) {
        if ($data instanceof \Iterator) {
            return new \Predis\Shared\MultiBulkResponseKVIterator($data);
        }
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $result[$data[$i]] = $data[++$i];
        }
        return $result;
    }
}

/* multiple databases handling commands */
class SelectDatabase extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SELECT'; }
}

class MoveKey extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MOVE'; }
    public function parseResponse($data) { return (bool) $data; }
}

class FlushDatabase extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'FLUSHDB'; }
}

class FlushAll extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'FLUSHALL'; }
}

/* sorting */
class Sort extends \Predis\MultiBulkCommand {
    public function getCommandId() { return 'SORT'; }
    public function filterArguments(Array $arguments) {
        if (count($arguments) === 1) {
            return $arguments;
        }

        // TODO: add more parameters checks
        $query = array($arguments[0]);
        $sortParams = $arguments[1];

        if (isset($sortParams['by'])) {
            $query[] = 'BY';
            $query[] = $sortParams['by'];
        }
        if (isset($sortParams['get'])) {
            $getargs = $sortParams['get'];
            if (is_array($getargs)) {
                foreach ($getargs as $getarg) {
                    $query[] = 'GET';
                    $query[] = $getarg;
                }
            }
            else {
                $query[] = 'GET';
                $query[] = $getargs;
            }
        }
        if (isset($sortParams['limit']) && is_array($sortParams['limit'])) {
            $query[] = 'LIMIT';
            $query[] = $sortParams['limit'][0];
            $query[] = $sortParams['limit'][1];
        }
        if (isset($sortParams['sort'])) {
            $query[] = strtoupper($sortParams['sort']);
        }
        if (isset($sortParams['alpha']) && $sortParams['alpha'] == true) {
            $query[] = 'ALPHA';
        }
        if (isset($sortParams['store']) && $sortParams['store'] == true) {
            $query[] = 'STORE';
            $query[] = $sortParams['store'];
        }

        return $query;
    }
}

/* transactions */
class Multi extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MULTI'; }
}

class Exec extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'EXEC'; }
}

class Discard extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'DISCARD'; }
}

/* publish/subscribe */
class Subscribe extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SUBSCRIBE'; }
}

class Unsubscribe extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'UNSUBSCRIBE'; }
}

class SubscribeByPattern extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'PSUBSCRIBE'; }
}

class UnsubscribeByPattern extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'PUNSUBSCRIBE'; }
}

class Publish extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'PUBLISH'; }
}

/* persistence control commands */
class Save extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SAVE'; }
}

class BackgroundSave extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'BGSAVE'; }
    public function parseResponse($data) {
        if ($data == 'Background saving started') {
            return true;
        }
        return $data;
    }
}

class BackgroundRewriteAppendOnlyFile extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'BGREWRITEAOF'; }
    public function parseResponse($data) {
        return $data == 'Background append only file rewriting started';
    }
}

class LastSave extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'LASTSAVE'; }
}

class Shutdown extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SHUTDOWN'; }
    public function closesConnection() { return true; }
}

/* remote server control commands */
class Info extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'INFO'; }
    public function parseResponse($data) {
        $info      = array();
        $infoLines = explode("\r\n", $data, -1);
        foreach ($infoLines as $row) {
            list($k, $v) = explode(':', $row);
            if (!preg_match('/^db\d+$/', $k)) {
                $info[$k] = $v;
            }
            else {
                $db = array();
                foreach (explode(',', $v) as $dbvar) {
                    list($dbvk, $dbvv) = explode('=', $dbvar);
                    $db[trim($dbvk)] = $dbvv;
                }
                $info[$k] = $db;
            }
        }
        return $info;
    }
}

class SlaveOf extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SLAVEOF'; }
    public function filterArguments(Array $arguments) {
        if (count($arguments) === 0 || $arguments[0] === 'NO ONE') {
            return array('NO', 'ONE');
        }
        return $arguments;
    }
}

class Config extends \Predis\MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'CONFIG'; }
}