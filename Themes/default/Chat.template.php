<?php
/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/Chat.template.php
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

class ChatTemplate extends PureChat
{
	public function __construct()
	{
		parent::__construct();

		/*
		 * We have a lot of JavaScript being loaded here.
		 * All the handler scripts should be loaded from the default theme,
		 * because they handle all the AJAX calls and "source work".
		 * The script.ui.js files however, should be loaded per theme,
		 * as they handle the layout and overall feel of the application.
		 */

		// CSS files.
		self::$globals['import_scripts'] .= '
		<link rel="stylesheet" type="text/css" href="' . $this->currentthemeurl . '/css/profile.css" />';

		// Sound Manager script.
		self::$globals['import_scripts'] .= '
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/soundmanager/sound.js"></script>';

		// Profile scripts.
		self::$globals['import_scripts'] .= '
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/profile_handler.js"></script>
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/profile.ui.js"></script>';

		// Work scripts.
		self::$globals['import_scripts'] .= '
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/chat.js"></script>
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/sound.js"></script>
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/IRC.js"></script>';

		// User Interface manipulation objects.
		self::$globals['import_scripts'] .= '
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/chat.ui.js"></script>
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/sound.ui.js"></script>';
	}

	public function content()
	{
		echo '
		<div id="messages">
			<ul>';

		if (self::$globals['messages'])
		{
			foreach (self::$globals['messages'] as $m)
			{
				echo '
				<li id="post_', $m['id'], '" class="chat_post">
					<div class="message">
						<div class="floatright">
							<span id="message_timestamp_cont">
								<em>', $m['time'], '</em>
							</span>
							<span id="remove_', $m['id_poster'], '_', $m['id'], '" onclick="s.remove_post(this.id)" class="remove_message_cont">
								<img src="', $this->currentthemeurl, '/images/fugue/16/cross.png" alt="', self::$lang['remove_msg'], '" title="', self::$lang['remove_msg'], '" />
							</span>
							<br class="clear" />
						</div>
						', $m['text'], '
						<br class="clear" />
					</div>
					<div class="user"><div class="message_pointer"></div>', $m['poster'], !empty($m['avatar']) ? '<img src="' . $m['avatar'] .  '" alt="" class="avatar" />' : '', '</div>
					<br class="clear" />
				</li>';
			}
		}
		
		echo '
			</ul>
		</div>';
	}
}