<?php
/**
 * @var array
 */
$fields = array(

		/**
		 * Is the user activeated?
		 * @var boolean
		 */
	    'activated' => true,
		/**
		 * User's cell phone carrier
		 * @var string
		 */	
		'carrier' => null,
		/**
		 * Timestamp user's record was created
		 * @var integer
		 */
	    'created' => null,
	    /**
	     * Does this user receive device updates
	     * @var boolean
	     */
		'device_updates' => false,
		/**
		 * User's email
		 * @var string
		 */
		'email' => null,
		/**
		 * Array of message ids of user's favorite posts
		 * @var array
		 */
		'favorites' => array(),
		/**
		 * Array of user ids of users that user follows
		 * @var array
		 */
		'followers' => array(),
		/**
		 * Array of user ids of users that follow user
		 * @var array
		 */
		'following' => array(),
		/**
		 * Array of user ids indicating pending follow requests
		 * @var array
		 */
		'friend_requests' => array(),
		/**
		 * Groups of which user is a member
		 * @var array
		 */
		'groups' => array(),
		/**
		 * Unique ID of the user
		 * @var integer
		 */
		'id' => null,
		/**
		 * Array of message ids of direct messages sent to user
		 * @var array
		 */
		'inbox' => array(),
		/**
		 * Is account private?
		 * @var boolean
		 */
	    'locked' => false,
	    /**
	     * Key used for sign up
	     * @var boolean
	     */
	    'key' => null,
	    /**
	     * Array of message ids for messages that content the user's username {@link $fields[username]}
	     * @var array
	     */
		'mentions' => array(),
		/**
		 * Timestamp of last time user's record was modified
		 * @var integer
		 */
	    'modified' => null,
	    /**
	     * Status or userlevel of user. May be admin, premium, or member. Defaults to member
	     * @var string
	     */
		'permission' => 'member',
		/**
		 * Hashed user's password
		 * @var string
		 */
		'password' => null,
		/**
		 * User's password confirmation, non-hashed, and in full text. 
		 * Should be set to null after validation, if not, may be used for manual password recovery
		 * without having to do a password reset
		 * @var string
		 */
		'passwordconfirm' => null,
		/**
		 * User's phone number
		 * @var integer
		 */
		'phone' => null,
		/**
		 * Array of message ids for user's home page
		 * @var array
		 */
		'private' => array(),
		/**
		 * Array of messag eids for user's public page
		 * @var array
		 */
		'public' => array(),
		/**
		 * User's real or full name
		 * @var string
		 */
	    'realname' => null,
	    /**
	     * Array of message ids of direct messages sent by user
	     * @var array
	     */
		'sent' => array(),
		/**
		 * Has the user's sms/phone number been activated?
		 * @var boolean
		 */
		'sms_activated' => false,
		/**
		 * Is the user viewing reply messages in a threaded format?
		 * @var boolean
		 */
		'threading' => true,
		/**
		 * Time zone of the user
		 * @var string
		 */
	    'time_zone' => null,
	    /**
	     * User's username, handle, screenname
	     * @var string
	     */
	    'username' => null
	);
?>