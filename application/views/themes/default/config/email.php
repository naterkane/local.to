<?php
/**
 * Email Strings
 * 
 * subjects (keywords are wrapped in {})
 *
 * @package		Local.to
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link			http://getnomcat.com
 * @version		$Id$
 * @filesource	/config/email.php
 */

/**'
 * The link to accessing a user's email settings
 * @var string 
 */
$settings['email_settings_link'] = "

--
Want to control which emails you receive from Local.to? Go to: ".$settings['base_url']."settings";

/**
 * The subject of a message confirming a follow request
 * @var string 
 */
$settings['subject_confirm'] = "{username} has confirmed your follower request on Local.to";

/**
 * The message confirming a follow request
 * @var string 
 */
$settings['message_confirm'] = "Hey {to},

{username} has confirmed your follow request. You can now follow their public updates on Local.to.

To view {username}'s profile, follow this link:

{link}
";

/**
 * The subject of a follower notification message
 * @var string 
 */
$settings['subject_following'] = "{username} is now following you on Local.to";

/**
 * The follower notification message
 * @var string 
 */
$settings['message_following'] = "Hi {to} ({username}),

{followerrealname} ({followerusername}) is now following you on Local.to.

Check out {followerusername}'s profile here:
{link}
";

/**
 * The subject of an account deletion message
 * @var string 
 */
$settings['subject_deletion'] = "You have deleted your account on Local.to";

/**
 * The account deletion message
 * @var string 
 */
$settings['message_deletion'] = "Hey {to} ({username}),

You have received this message because you have decided to delete your account.

We're sorry to see you go! Please come back soon.
";

/**
 * The subject of a private message message
 * @var string 
 */
$settings['subject_dm'] = "{fromrealname} ({fromusername}) sent you a message on Local.to";

/**
 * The private message message
 * @var string 
 */
$settings['message_dm'] = "{fromrealname} ({fromusername}) sent you a message on Local.to
------------------------------------------------------------------

{message}

------------------------------------------------------------------

To reply to this message, follow the link below: 
{link}
";

/**
 * The subject of an account deletion message
 * @var string 
 */
$settings['subject_dm_group'] = "{fromrealname} ({fromusername}) has sent a message to {group}";

/**
 * The account deletion message
 * @var string 
 */
$settings['message_dm_group'] = "{fromrealname} ({fromusername}) has sent you a private message for {group} on Local.to
------------------------------------------------------------------

{message}

------------------------------------------------------------------

To reply to this message, follow the link below: 
{link}
";

/**
 * The subject of a friend request notification message for private users
 * @var string 
 */
$settings['subject_friend_request'] = "{followerrealname} ({followerusername}) has requested to follow you on Local.to";

/**
 * The friend requestion notification message for private users
 * @var string 
 */
$settings['message_friend_request'] = "Hi {to} ({username}),

{followerrealname} ({followerusername}) has requested to follow you on Local.to. If you approve, they will be able to see all of your public updates.
	
To see more details and confirm this follow request, follow the link below:

{link}
";

/**
 * The subject of a group invitation message
 * @var string 
 */
$settings['subject_group_invite'] = "You have been invited to join {groupname} on Local.to";

/**
 * The group invitation message
 * @var string 
 */
$settings['message_group_invite'] = "Hi{to}, 

You have been invited by {fromfullname} ({fromusername}) to join the Local.to network: {group}.

To see more details and confirm this network invitation, follow the link below:

{link}
";

/**
 * The subject of an account invitation message
 * @var string 
 */
$settings['subject_invite'] = "You have been invited to join Local.to";

/**
 * The account invitation message
 * @var string 
 */
$settings['message_invite'] = "Hi,

We would like to invite you to join Local.to, a new kind of service that makes checking-in to real world locations and the search for something to do, or where to go more efficient.

Click the link below to activate your invite and create an account:
{link}

To find out more about Local.to click here ".$settings['base_url']."about
";

/**
 * The subject of a password recovery message
 * @var string 
 */
$settings['subject_recover_password'] = "You requested to change your Local.to password"; 

/**
 * The password recovery message
 * @var string 
 */
$settings['message_recover_password'] = "Hey {to} ({username}),

No worries about forgetting your password, we are more than happy to help. You can reset your password and create another one. Please follow these instructions:

Click on the link below or copy and paste the URL into your browser:
{link}

Local.to staff will never ask you for your password, so if you have not requested for your password to be changed or believe that you have received this message in error, please feel free to ignore this message.
"; 

/**
 * The subject of a reset password message
 * @var string 
 */
$settings['subject_reset_password'] = "Your password has been changed";

/**
 * The reset password message
 * @var string 
 */
$settings['message_reset_password'] = "Hey {to} ({username}),

This email is to confirm that you have changed you password. 

Local.to staff will never ask you for your password, so if you have not requested for your password to be changed, please visit the password recovery page and change your password to ensure its privacy: 
".$settings['base_url']."recover_password
";

/**
 * The subject of a welcome message
 * @var string 
 */
$settings['subject_welcome'] = "Welcome to Local.to";

/**
 * The welcome message
 * @var string 
 */
$settings['message_welcome'] = "Hi {to} ({username}), welcome to Local.to!

Using Local.to is going to change the way you communicate with and view your friends and surroundings. Local.to lets you view friends and whats happening nearby no matter what geo-location network(s) you prefer to use. Local.to is a service-agnostic platform that makes checking-in to real world locations, the search for something to do, or where to go more efficient.

Thank you for joining!
";

/**
 * The email signature
 * @var string 
 */
$settings['signature'] = "
Thanks,
Your pals Paul & Nater";