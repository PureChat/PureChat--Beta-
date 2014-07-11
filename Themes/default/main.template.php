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
		echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title> PureChat </title>
		<meta charset="utf-8" />
		<link rel="shortcut icon" href="', $this->currentthemeurl, '/images/icon.ico" />
		<!-- PureChat Styling -->
		<link rel="stylesheet" type="text/css" href="', $this->currentthemeurl, '/css/main.css" />
		<!-- PureChat Scripts -->
		<script type="text/javascript" src="', $this->themesurl, '/default/scripts/jQuery.js"></script>';
		if (PureChat::$globals['user']['logged']) {
			echo '
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/main.js"></script>';
		}
		if (!empty(PureChat::$globals['import_scripts'])) {
			echo PureChat::$globals['import_scripts'];
		}
		echo '
		<script type="text/javascript" src="', $this->script, '?action=js_vars&ajax_connection=true"></script>
	</head>
	<body>';
	}

	protected function template_bottom()
	{
		echo '
		<div id="sidebar">';

		// Avatar, or in the case of a guest, a login form.
		if (PureChat::$globals['user']['logged'])
		{
			echo '
			<div id="sidebar_header">
				<div id="sidebar_avatar_container">
					<div id="white_frame">
						', !empty(PureChat::$globals['user']['avatar']) ? '<img src="' . PureChat::$globals['user']['avatar'] . '" alt="" id="sidebar_avatar" />' : '', '
					</div>
				</div>
			</div>';
		}
		else
		{
			echo '
			<div id="sidebar_header_padded">
				<form action="', $this->script, '?action=user&perform=login" method="post">
					<label class="dark_label" for="username_field">', PureChat::$lang['display_name'], '</label>
					<input type="text" class="dark_field" id="username_field" name="login_username" />

					<label class="dark_label" for="password_field">', PureChat::$lang['password'], '</label>
					<input type="password" class="dark_field" id="password_field" name="login_password" />

					<input type="submit" value="', PureChat::$lang['login'], '" class="dark_button" />
				</form>
			</div>';
		}

		// User info and junk.
		echo '
			<div id="user_info">
				<span id="username">', PureChat::$globals['user']['display_name'], '</span>
				', !PureChat::$globals['user']['logged'] ? '<span id="logged_out_adviser">' . PureChat::$lang['please_log_in'] . '</span>' : '', '
				<div id="sidemenu">
					', PureChat::$globals['user']['logged'] ? '<a href="javascript:profile.load_info(' . PureChat::$globals['user']['id'] . ');">' . PureChat::$lang['profile'] . '</a> | ' : '', '
					', PureChat::$globals['user']['is_admin'] ? '<a href="' . $this->script . '?page=admin">' . PureChat::$lang['admin'] . '</a> | ' : '', '
					', PureChat::$globals['user']['logged'] ? '<a href="' . $this->script . '?action=user&amp;perform=logout">' . PureChat::$lang['logout'] . '</a>' : '', '
				</div>
				<div id="status_bar">
					<div id="shine"></div>
				</div>
			</div>';

		// Online list.
		echo '
			<div id="online_list">
				<div id="users_online">
					', PureChat::$globals['user']['logged'] ? '<span id="online_text"></span>' : '', '
					', PureChat::$globals['user']['logged'] ? '<hr class="sidebar_hr" />' : '', '
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

		if (PureChat::$globals['user']['logged'])
		{
			echo '
			<form action="', $this->script, '" method="post" onsubmit="s.call_waiting(s.post_new); return false;">
				<div> <!-- XHTML Strict requires forum inputs to be encapsulated. -->
					<div id="message_input_container">
						<input type="text" id="message_input" autocomplete="off" />
					</div>

					<div id="icon_container">
						<a id="smilies_link" title="', PureChat::$lang['smilies_title'], '" class="centertext">
							<img src="', $this->currentthemeurl, '/images/smilies/sm_smile.png" alt="', PureChat::$lang['smilies_title'], '" />
						</a>
					</div>

					<input type="submit" id="message_submit" value="', PureChat::$lang['post'], '" />
				</div>
			</form>';
		}
			
		echo '
		</div>';

		//-- TODO: Shouldn\'t these be dynamically created with JavaScript...
		echo '
		<div id="list_smilies">
			<!-- Content inserted with JavaScipt, so we can loop the smilies and bind a click event to each one. -->
		</div>
		<div id="list_irc">
			<!-- Content inserted with JavaScipt, so we can loop the smilies and bind a click event to each one. -->
		</div>
	</body>
</html>';
	}
}
