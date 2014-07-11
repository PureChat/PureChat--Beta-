<?php
/**
 * PureChat (PC)
 *
 * @file ~./Sources/main.source.php
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

class SourceMain extends PureChat
{
	public function __construct()
	{
		parent::__construct();
		require_once($this->includesdir . '/MessageParser.php');
	}

	public function init()
	{
		// Please don't smile at the strangers.
		if (!PureChat::$globals['user']['logged']) {
			return;
		}
		PureChat::$universal->load_language('profile');
		$this->load_smilies();
		$this->load_irc_list();
		$this->load_messages();
		$this->load_template();
	}

	private function load_messages()
	{
		PureChat::$globals['messages'] = array();
		PureChat::$globals['last_message'] = 0;

		if (PureChat::$globals['ajax']) {
			return;
		}

		$messages = $this->db->get_all('
			SELECT msg.id, msg.poster,
				msg.text, msg.time,
                usr.display_name, usr.avatar
			FROM pc_messages AS msg
			LEFT JOIN pc_users AS usr
				ON (msg.poster = usr.id_user)
			ORDER BY msg.id ASC'
		);

		if (!$messages) {
			return;
		}

		$this->update_post_information($messages);
	}

	private function update_post_information($messages)
	{
		$this->format_messages($messages);
		$this->set_last_id();
	}

	private function format_messages($messages)
	{
		$this->parse = new MessageParser();
		foreach ($messages as $message)
		{
			//-- TODO: We may eventually want to make $this->parse->format() with an argument of what to format to reduce lines to call.
			$this->parse->setMessage($message['text'], $message['display_name']);
			$this->parse->formatVulgar();
			$this->parse->formatText();
			$this->parse->formatSmileys();
			$this->parse->formatURLs();
			$message['text'] = $this->parse->getMessage();

			PureChat::$globals['messages'][] = array(
				'id' => (int) $message['id'],
				'poster' => $message['display_name'],
				'id_poster' => $message['poster'],
				'text' => $message['text'],
				'time' => PureChat::$universal->format_time($message['time']),
				'avatar' => $message['avatar'],
			);
		}
	}

	private function set_last_id() {
		$last = end(PureChat::$globals['messages']);
		PureChat::$globals['last_message'] = $last['id'];
	}

	private function load_template()
	{
		$class = array(
			'chat_template' => array(
				'file' => 'Chat',
				'class' => 'ChatTemplate',
			),
		);
		$method = array(
			'content_layer' => array(
				'class_key' => 'chat_template', // The index of the class array.
				'method' => 'content',
			),
		);
		PureChat::$universal->load_template($class, $method);
	}

	private function load_smilies()
	{
		PureChat::$globals['smilies'] = array(
			0 => array(
				'name' => PureChat::$lang['sm_smile'],
				'id' => 'smile',
				'code' => ':)',
				'img' => $this->smiliesurl . '/sm_smile.png',
				'case' => 'i',
				'enabled' => true,
			),
			1 => array(
				'name' => PureChat::$lang['sm_frown'],
				'id' => 'frown',
				'code' => ':(',
				'img' => $this->smiliesurl . '/sm_frown.png',
				'case' => 'i',
				'enabled' => true,
			),
			2 => array(
				'name' => PureChat::$lang['sm_glare'],
				'id' => 'glare',
				'code' => ':mad',
				'img' => $this->smiliesurl . '/sm_glare.png',
				'case' => 'i',
				'enabled' => true,
			),
			3 => array(
				'name' => PureChat::$lang['sm_neutral'],
				'id' => 'neutral',
				'code' => ':|',
				'img' => $this->smiliesurl . '/sm_neutral.png',
				'case' => 'i',
				'enabled' => true,
			),
			4 => array(
				'name' => PureChat::$lang['sm_wink'],
				'id' => 'wink',
				'code' => ';)',
				'img' => $this->smiliesurl . '/sm_wink.png',
				'case' => 'i',
				'enabled' => true,
			),
			5 => array(
				'name' => PureChat::$lang['sm_oh'],
				'id' => 'oh',
				'code' => ':O',
				'img' => $this->smiliesurl . '/sm_oh.png',
				'case' => 'i',
				'enabled' => true,
			),
			6 => array(
				'name' => PureChat::$lang['sm_tongue'],
				'id' => 'tongue',
				'code' => ':P',
				'img' => $this->smiliesurl . '/sm_tongue.png',
				'case' => 'i',
				'enabled' => true,
			),
			7 => array(
				'name' => PureChat::$lang['sm_dead'],
				'id' => 'dead',
				'code' => 'X.X',
				'img' => $this->smiliesurl . '/sm_dead.png',
				'case' => 'i',
				'enabled' => true,
			),
		);
	}

	private function load_irc_list()
	{
		PureChat::$globals['irc_commands'] = array(
			0 => array(
				'name' => PureChat::$lang['available'],
				'id' => 'irc_available',
				'img' => $this->currentthemeurl . '/images/available.png',
				'command' => '/available',
			),
			1 => array(
				'name' => PureChat::$lang['busy'],
				'id' => 'irc_busy',
				'img' => $this->currentthemeurl . '/images/busy.png',
				'command' => '/busy',
			),
			2 => array(
				'name' => PureChat::$lang['away'],
				'id' => 'irc_away',
				'img' => $this->currentthemeurl . '/images/away.png',
				'command' => '/away',
			),
			3 => array(
				'name' => PureChat::$lang['invisible'],
				'id' => 'irc_invisible',
				'img' => $this->currentthemeurl . '/images/invisible.png',
				'command' => '/invisible',
			),
			4 => array(
				'name' => PureChat::$lang['logout'],
				'id' => 'irc_leave',
				'command' => '/quit',
				'img' => $this->currentthemeurl . '/images/leave.png',
			),
		);
	}
}