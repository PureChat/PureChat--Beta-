<?php
/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/main.template.php
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

class MainTemplate extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function template_top()
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title> PureChat </title>

		<!-- Favicon icons. Yayz -->
		<link rel="shortcut icon" href="', $this->currentthemeurl, '/images/icon.ico" />

		<!-- Better let the page have style, or all hope is lost. -->
		<link rel="stylesheet" type="text/css" href="', $this->currentthemeurl, '/css/main.css" />

		<!-- Load jQuery and main.js. Our main.js file is global, and effects all pages. -->
		<script type="text/javascript" src="', $this->themesurl, '/default/scripts/jQuery.js"></script>
		', self::$globals['user']['logged'] ? '<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/main.js"></script>' : '', '

		<!-- Import the files specific to the page we\'re looking at. -->
		', !empty(self::$globals['import_scripts']) ? self::$globals['import_scripts'] : '', '
		
		<!-- Define some javascript variables for use. -->
		<script type="text/javascript" src="', $this->script, '?action=js_vars&ajax_connection=true"></script>


		<!-- Throw out some random meta tags. -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	</head>

	<body>';
	}

	protected function template_bottom()
	{
		echo '
		<div id="sidebar">';

		// Avatar, or in the case of a guest, a login form.
		if (self::$globals['user']['logged'])
			echo '
			<div id="sidebar_header">
				<div id="sidebar_avatar_container">
					<div id="white_frame">
						', !empty(self::$globals['user']['avatar']) ? '<img src="' . self::$globals['user']['avatar'] . '" alt="" id="sidebar_avatar" />' : '', '
					</div>
				</div>
			</div>';
		else
			echo '
			<div id="sidebar_header_padded">
				<form action="', $this->script, '?action=user&perform=login" method="post">
					<label class="dark_label" for="username_field">', self::$lang['display_name'], '</label>
					<input type="text" class="dark_field" id="username_field" name="login_username" />

					<label class="dark_label" for="password_field">', self::$lang['password'], '</label>
					<input type="password" class="dark_field" id="password_field" name="login_password" />

					<input type="submit" value="', self::$lang['login'], '" class="dark_button" />
				</form>
			</div>';

		// User info and junk.
		echo '
			<div id="user_info">
				<span id="username">', self::$globals['user']['display_name'], '</span>
				', !self::$globals['user']['logged'] ? '<span id="logged_out_adviser">' . self::$lang['please_log_in'] . '</span>' : '', '
				<div id="sidemenu">
					', self::$globals['user']['logged'] ? '<a href="javascript:profile.load_info(' . self::$globals['user']['id'] . ');">' . self::$lang['profile'] . '</a> | ' : '', '
					', self::$globals['user']['is_admin'] ? '<a href="' . $this->script . '?page=admin">' . self::$lang['admin'] . '</a> | ' : '', '
					', self::$globals['user']['logged'] ? '<a href="' . $this->script . '?action=user&amp;perform=logout">' . self::$lang['logout'] . '</a>' : '', '
				</div>
				<div id="status_bar">
					<div id="shine"></div>
				</div>
			</div>';

		// Online list.
		echo '
			<div id="online_list">
				<div id="users_online">
					', self::$globals['user']['logged'] ? '<span id="online_text"></span>' : '', '
					', self::$globals['user']['logged'] ? '<hr class="sidebar_hr" />' : '', '
					<ul>
						<li><!-- This is cleared by the JS. It\'s only here to fool the validator. --></li>
					</ul>
				</div>
			</div>';

		// Footer buttons and such.
		echo '
			<div id="sidebar_bottom">
				<div id="copyright">
					', PC_COPY, ' - ', PC_VERSION, '
				</div>

				<div id="footer_button_container">
					<div class="footer_button">
						<div id="tag_image"></div>
					</div>
					<div class="footer_button">
						<div id="sound_toggle" class="sound_toggle_on"></div>
					</div>
				</div>
			</div>';

		echo '
		</div>';

		echo '
		<div id="footer">';

		if (self::$globals['user']['logged'])
			echo '
			<form action="', $this->script, '" method="post" onsubmit="s.call_waiting(s.post_new); return false;">
				<div> <!-- XHTML Strict requires forum inputs to be encapsulated. -->
					<div id="message_input_container">
						<input type="text" id="message_input" />
					</div>

					<div id="icon_container">
						<a id="smilies_link" title="', self::$lang['smilies_title'], '" class="centertext">
							<img src="', $this->currentthemeurl, '/images/smilies/sm_smile.png" alt="', self::$lang['smilies_title'], '" />
						</a>
						<a id="bbc_link" title="', self::$lang['bbc_title'], '" class="centertext">
							<img src="', $this->currentthemeurl, '/images/T.png" alt="', self::$lang['bbc_title'], '" />
						</a>
					</div>

					<input type="submit" id="message_submit" value="', self::$lang['post'], '" />
				</div>
			</form>';
			
		echo '
		</div>';

		echo '
		<div id="list_smilies">
			<!-- Content inserted with JavaScipt, so we can loop the smilies and bind a click event to each one. -->
		</div>
		<div id="list_bbc">
			<!-- Content inserted with JavaScipt, so we can loop the smilies and bind a click event to each one. -->
		</div>
		<div id="list_irc">
			<!-- Content inserted with JavaScipt, so we can loop the smilies and bind a click event to each one. -->
		</div>
	</body>
</html>';
	}
}
