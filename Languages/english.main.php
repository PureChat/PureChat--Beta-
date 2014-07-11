<?php
/**
 * PureChat (PC)
 *
 * @file ~./Languages/english.main.php
 * @author The PureChat Team
 * @copyright 2012 PureChat.org <http://www.purechat.org>
 * @license GPL <http://www.gnu.org/licenses/>
 *
 * @version 0.0.9 (Alpha)
 */
/**
 * This file is part of PureChat.

 * PureChat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * PureChat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with PureChat.  If not, see <http://www.gnu.org/licenses/>.
 */

$lang = array(
	// Errors.
	'no_theme' => 'Unable to load the specified theme.',
	'login_empty_val' => 'Error: One of the fields was left empty.',
	'login_no_user' => 'Error: The specified user does not exist.',
	'login_wrong_pass' => 'Error: The wrong password for the specified user was entered.',
	'login_not_activated' => 'Error: It appears that your account has not yet been activated. To activate your account please click the link in the email that you received.',
	'unknown_error' => 'Error: 3749', // People just love these descriptive errors. :D
	'reg_email_subject' => 'PureChat Registration',
	'reg_email_body' => "Thank you for registering!\n\nHere are your account details:\n--------------------------------------------\nUsername: {user}\nPassword: {pass}\n\nBefore you can login you must activate your acocunt to finish the registration process.\n\nTo activate your account, click the following link:\n{activate_link}",

	// Registration Template Errors.
	'empty_email' => 'You must enter a valid email address.',
	'empty_username' => 'You must enter a display name.',
	'empty_password' => 'You must enter a password.',
	'empty_password2' => 'You must retype your password.',
	'email_exists' => 'The email address you entered is already associated with an existing member.',
	'display_name_exists' => 'The display name you entered is already in use.',
	'invalid_email' => 'The email address you entered is not in a valid format.',
	'passwords_no_match' => 'The passwords you entered do not match.',
	'registration_errors_not_found' => 'There were error(s) encountered with your registration but the language strings could not be retrieved. Please contact the chat administrator.',
	'the_bad_news' => 'Sorry, the following error(s) were encountered:',

	// Success
	'account_activated' => 'Your account has successfully been activated! You may now login.',
	'account_registered' => 'Your account has been successfully registered. Please check you email for a verification link.',

	// Login/Register.
	'login' => 'Login',
	'login_form' => 'Login Form',
	'register_form' => 'Register Form',
	'registration_caption' => 'Please fill out the proceeding form and submit it to register an account.',
	'display_name' => 'Display Name',
	'password' => 'Password',
	'password_again' => 'Password Again',
	'email' => 'Email',
	'register' => 'Register',
	'show_login' => 'Show Login',
	'show_register' => 'Show Register',
	'display_name_desc' => 'This is the screen name that others will see, and will precede your chat messages.',
	'email_desc' => 'We use this address to send you a confirmation email.',
	'password_desc' => 'Please enter the password you wish to use, and don\'t forget it!',
	'password2_desc' => 'Enter the same password again. Note that all passwords are encrypted.',

	// Gobal strings.
	'guest' => 'Guest',
	'admin' => 'Admin',
	'mod' => 'Moderator',
	'welcome' => 'Welcome, ',
	'logout' => 'Log Out',
	'post' => 'Send',
	'people_online' => 'people online',
	'person_online' => 'person online',
	'please_log_in' => 'You are currently logged out. Please use the form above to log back in.',

	// Pages
	'page_home' => 'Chat',
	'page_admin' => 'Admin Panel',
	'page_groups' => 'Groups',
	'page_settings' => 'Settings',
	'profile' => 'Profile',
	'groups_fatal' => 'Uh oh, you appear to be an idiot.',

    'smilies_title' => 'Smilies',
    'bbc_source' => 'Source',
	'bbc' => array(
		'bold' => 'Bold',
		'italic' => 'Italic',
		'strike' => 'Strike',
		'underline' => 'Underline',
		'color' => 'Color',
		'font' => 'Font',
		'size' => 'Size',
		'html' => 'HTML',
		'glow' => 'Glow'
	),

	// Smilies
	'sm_smile' => 'Smile',
	'sm_frown' => 'Frown',
	'sm_glare' => 'Glare',
	'sm_neutral' => 'Neutral',
	'sm_wink' => 'Wink',
	'sm_tongue' => 'Tongue',
	'sm_dead' => 'Dead',
	'sm_oh' => 'Oh!',

	// Status
	'available' => 'Available',
	'busy' => 'Busy',
	'invisible' => 'Invisible',
	'away' => 'Away',

	'update' => 'Update',
	'cancel' => 'Cancel',

	'success' => 'Success!',
	'remove_msg' => 'Delete',

	// Admin homepage section titles.
	'velkomen_til_admin' => 'Welcome!',
	'software_details' => 'Software Details',
	'admin_notes' => 'Adminstrator\'s Notes',
	'news_feed' => 'PureChat News',

	'velkomen_text' => '
		Welcome to your administration panel!
		This is the place to come when you want to change the way your PureChat installation operates.
		You can also manage user accounts, groups, and permissions from here.
		If you are experiencing difficulties, please visit our <a href="http://forum.purechat.org/">forum</a> for support.',

	'admins' => 'Administrators',
	'version' => 'Version',
	'latest_version' => 'Latest Version',
	'copyright' => 'Copyright',

	'mini_agreement' => 'If you click "Register" you are also confirming that you have<br />read and agree to abide by both the <a href="#">Terms of Service</a> and <a href="#">Rules</a>.',
	'tos' => 'Terms of Service',
	'rules' => 'Rules',

	'next' => 'Next Step',
	'back' => 'Previous Step'
);