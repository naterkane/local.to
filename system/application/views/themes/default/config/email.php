<?php
/**
 * Email Strings
 * 
 * subjects (keywords are wrapped in {})
 *
 * @package		Nomcat
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
Want to control which emails you receive from Nomcat? Go to: ".$settings['base_url']."settings";

/**
 * The subject of a message confirming a follow request
 * @var string 
 */
$settings['subject_confirm'] = "{username} has confirmed your follower request on Nomcat";

/**
 * The message confirming a follow request
 * @var string 
 */
$settings['message_confirm'] = "Hey {to},

{username} has confirmed your follow request. You can now follow their public updates on Nomcat.

To view {username}'s profile, follow this link:

{link}
";

/**
 * The subject of a follower notification message
 * @var string 
 */
$settings['subject_following'] = "{username} is now following you on Nomcat";

/**
 * The follower notification message
 * @var string 
 */
$settings['message_following'] = "Hi {to} ({username}),

{followerrealname} ({followerusername}) is now following you on Nomcat.

Check out {followerusername}'s profile here:
{link}
";

/**
 * The subject of an account deletion message
 * @var string 
 */
$settings['subject_deletion'] = "You have deleted your account on Nomcat";

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
$settings['subject_dm'] = "{fromrealname} ({fromusername}) sent you a message on Nomcat";

/**
 * The private message message
 * @var string 
 */
$settings['message_dm'] = "{fromrealname} ({fromusername}) sent you a message on Nomcat
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
$settings['message_dm_group'] = "{fromrealname} ({fromusername}) has sent you a private message for {group} on Nomcat
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
$settings['subject_friend_request'] = "{followerrealname} ({followerusername}) has requested to follow you on Nomcat";

/**
 * The friend requestion notification message for private users
 * @var string 
 */
$settings['message_friend_request'] = "Hi {to} ({username}),

{followerrealname} ({followerusername}) has requested to follow you on Nomcat. If you approve, they will be able to see all of your public updates.
	
To see more details and confirm this follow request, follow the link below:

{link}
";

/**
 * The subject of a group invitation message
 * @var string 
 */
$settings['subject_group_invite'] = "You have been invited to join {groupname} on Nomcat";

/**
 * The group invitation message
 * @var string 
 */
$settings['message_group_invite'] = "Hi{to}, 

You have been invited by {fromfullname} ({fromusername}) to join the Nomcat network: {group}.

To see more details and confirm this network invitation, follow the link below:

{link}
";

/**
 * The subject of an account invitation message
 * @var string 
 */
$settings['subject_invite'] = "You have been invited to join Nomcat";

/**
 * The account invitation message
 * @var string 
 */
$settings['message_invite'] = "Hi,

We would like to invite you to join Nomcat, a sports tool that makes the communication, management, and promotion of sports teams and its members more efficient.

Click the link below to activate your invite and create an account:
{link}

To find out more about Nomcat click here http://Nomcat.com/about
";

/**
 * The subject of a password recovery message
 * @var string 
 */
$settings['subject_recover_password'] = "You requested to change your Nomcat password"; 

/**
 * The password recovery message
 * @var string 
 */
$settings['message_recover_password'] = "Hey {to} ({username}),

No worries about forgetting your password, we are more than happy to help. You can reset your password and create another one. Please follow these instructions:

Click on the link below or copy and paste the URL into your browser:
{link}

Nomcat staff will never ask you for your password, so if you have not requested for your password to be changed or believe that you have received this message in error, please feel free to ignore this message.
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

Nomcat staff will never ask you for your password, so if you have not requested for your password to be changed, please visit the password recovery page and change your password to ensure its privacy: 
http://Nomcat.com/recover_password
";

/**
 * The subject of a welcome message
 * @var string 
 */
$settings['subject_welcome'] = "Welcome to Nomcat";

/**
 * The welcome message
 * @var string 
 */
$settings['message_welcome'] = "Hi {to} ({username}), welcome to Nomcat!

Using Nomcat is going to change the way you communicate with, manage, and promote your team and its members. Ultimately, Nomcat will become your only destination for sharing and receiving information about the teams and players you care about most.

Thank you for joining and we wish you the best of luck on and off the field!
";

/**
 * The email signature
 * @var string 
 */
$settings['signature'] = "
Thanks,
The Nomcat Team";
?>