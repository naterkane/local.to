<?php
/**
 * @var array
 */
$fields = array(
		/**
		 * Group id
		 * @var integer
		 */
		'id' => null,
		/**
		 * Array of direct message ids sent to group
		 * @var array
		 */
		'inbox' => array(),
		/**
		 * Array of group_invite ids sent to users
		 * @var array
		 */
		'invites' => array(),
		/**
		 * Array of user ids of members
		 * @var array
		 */
		'members' => array(),
		/**
		 * Array of message ids mentioning the group
		 * @var array
		 */
		'mentions' => array(),
		/**
		 * Array of message ids sent to group without DMs
		 * @var array
		 */
		'messages' => array(),
		/**
		 * Array of message ids sent to group without DMs, threaded
		 * @var array
		 */
		'messages_threaded' => array(),			
		/**
		 * Group's name
		 * @var string
		 */
		'name' => null,
		/**
		 * User id of group owner
		 * @var integer
		 */
		'owner_id' => null,
		/**
		 * Is the group public?
		 * @var boolean
		 */
		'public' => true,
		/**
		 * Timezone of group
		 * @var string
		 */
		'time_zone' => null,
		/**
		 * Is the group private of public?
		 * @var string
		 */
		'locked' => false,
		/**
		 * Timestamp of group's creation date
		 * @var integer
		 */
		'created' => null,
		/**
		 * Timestamp of the date when the group's record was last modified
		 * @var integer
		 */
		'fullname' => null,		
		'modified' => null,
		//'sport' => null,
		//'level' => null,
		'gender' => null,
		'location' => null,
		//'home_field' => null,
		//'league' => null,
		//'division' => null,
		//'session_start' => null,
		//'session_end' => null,
		//'recent_news' => null,
		'other_email' => null,
		'url' => null,
		'address' => null,
		'address_line2' => null,
		'state' => null,
		'city' => null,
		'postal_code' => null,
		'country' => null,
		'blacklist' => array(),
		'to_group' => null,
		'group_mentions' => array()		
		);