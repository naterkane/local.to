<?php
require_once(dirname(dirname(__FILE__)) . '/TokyoTyrant/Table.php');

class Net_TokyoTyrant_Query
{

    /* query condition: string is equal to*/
    const QCSTREQ = 0;
    /* query condition: string is included in*/
    const QCSTRINC = 1;
    /* query condition: string begins with*/
    const QCSTRBW = 2;
    /* query condition: string ends with*/
    const QCSTREW = 3;
    /* query condition: string includes all tokens in*/
    const QCSTRAND = 4;
    /* query condition: string includes at least one token in*/
    const QCSTROR = 5;
    /* query condition: string is equal to at least one token in*/
    const QCSTROREQ = 6;
    /* query condition: string matches regular expressions of*/
    const QCSTRRX = 7;
    /* query condition: number is equal to*/
    const QCNUMEQ = 8;
    /* query condition: number is greater than*/
    const QCNUMGT = 9;
    /* query condition: number is greater than or equal to*/
    const QCNUMGE = 10;
    /* query condition: number is less than*/
    const QCNUMLT = 11;
    /* query condition: number is less than or equal to*/
    const QCNUMLE = 12;
    /* query condition: number is between two tokens of*/
    const QCNUMBT = 13;
    /* query condition: number is equal to at least one token in*/
    const QCNUMOREQ = 14;
    /* query condition: full-text search with the phrase of*/
    const QCFTSPH = 15;
    /* query condition: full-text search with all tokens in*/
    const QCFTSAND = 16;
    /* query condition: full-text search with at least one token in*/
    const QCFTSOR = 17;
    /* query condition: full-text search with the compound expression of*/
    const QCFTSEX = 18;
    /* query condition: negation flag*/
    const QCNEGATE = 0x1000000; //1 << 24 umm.. 
    /* query condition: no index flag*/
    const QCNOIDX =  0x2000000; //1 << 24 umm..  
    /* order type: string ascending*/
    const QOSTRASC = 0;
    /* order type: string descending*/
    const QOSTRDESC = 1;
    /* order type: number ascending*/
    const QONUMASC = 2;
    /* order type: number descending*/
    const QONUMDESC = 3;

    private
        $tttable = null,
        $params = array();
    public function __construct(Net_TokyoTyrant_Table $tokyotyrant_table)
    {
        $this->tttable = $tokyotyrant_table;
    }
    
    public function addcond($name , $op, $expr)
    {
        $this->params[$name] = sprintf("addcond\0%s\0%s\0%s", $name, $op, $expr);
    }

    public function setorder($name, $type)
    {
        $this->params['setorder' . $name] = sprintf("setorder\0%s\0%s", $name, $type);
    }

    public function setlimit($max = -1, $skp = -1)
    {
        $this->params['setlimit'] = sprintf("setlimit\0%s\0%s", $max, $skp);
    }

    public function search()
    {
        $params = array_values($this->params);
        $values = $this->tttable->misc('search', $params, 1);
        return $values;
    }

    public function searchget($names = null)
    {
        $params = array_values($this->params);
        if (! is_null($names)) {
            $params[] = sprintf("get\0%s", implode("\0", $names));
        } else {
            $params[] = 'get';
        }

        try {
            $values = $this->tttable->misc('search', $params, 1);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return false;
        }
    
        foreach ($values as $value) {
            $col = explode("\0", $value);
            $col_count = count($col);
            $data = array();
            for ($i = 2; $i < $col_count ; $i+=2) {
                $data[$col[$i]] = $col[$i + 1];
            }
            $item = array();
            $item['key'] = $col[1];
            $item['value']= $data;
            $result[] = $item;
        }
        return $result;
    }

    public function searchcount()
    {
        $params = array_values($this->params);
        $params[] = 'count';
        try {
            $count = $this->tttable->misc('search', $params, 1);
        } catch(Net_TokyoTyrantProtocolException $e) {
            return 0;
        }
        
        return $count !== false ? (int)$count[0] : 0;
    }
}