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
	}

	public function init()
	{
		// Please don't smile at the strangers.
		if (self::$globals['user']['logged'])
		{
			// This is important if we want the profile to work.
			self::$langmethods->load_language('profile');

            // Before load_messages, callable by AJAX.
            $this->load_smilies();
			$this->load_bbc_list();
			$this->load_irc_list();

			// This should speed things up a little.
			// And yes, I'd like to keep two conditions so we can load things on ajax requests later.
			if (!self::$globals['ajax'])
			{
				$this->load_messages();
			}

			$class = array(
			'chat_template' => array(
				'file' => 'Chat',
				'class' => 'ChatTemplate'
			)
			);
			$method = array(
				'content_layer' => array(
					'class_key' => 'chat_template', // The index of the class array.
					'method' => 'content'
				),
			);
			self::$universal->load_template($class, $method);
		}
	}

	private function load_messages()
	{
        require_once($this->includesdir . '/parse.php');
        $parse = new parseObject;
		$sql = '
			SELECT msg.id, msg.poster,
				msg.text, msg.time,
                usr.display_name, usr.avatar
			FROM pc_messages AS msg
			LEFT JOIN pc_users AS usr
				ON (msg.poster = usr.id_user)
			ORDER BY msg.id DESC';
		$q = $this->db->get_all($sql);

		if (!$q)
		{
			// If there's no real messages, still define the varables just to kill errors.
			self::$globals['messages'] = array();

			$last = (int)0; // Not boolean (that's important)
			self::$globals['last_message'] = $last;

			return false;
		}

		foreach ($q as $message)
		{
            $message['text'] = $parse->smileys($parse->bbc($message['text'], $message['display_name']), $message['display_name']);
			self::$globals['messages'][] = array(
				'id' => $message['id'],
				'poster' => $message['display_name'],
				'id_poster' => $message['poster'],
				'text' => $message['text'],
				'time' => self::$universal->format_time($message['time']),
				'avatar' => $message['avatar']
			);
		}

		// Change it up.
		self::$globals['messages'] = array_reverse(self::$globals['messages']);

		// Get the id of the last message.
		$last = end(self::$globals['messages']);
		$last = $last['id'];
		self::$globals['last_message'] = (int)$last;
	}

	private function load_smilies()
	{
		$smilies = array(
			0 => array(
				'name' => self::$lang['sm_smile'],
				'id' => 'smile',
				'code' => ':)',
				'img' => $this->smiliesurl . '/sm_smile.png',
                'case' => 'i',
                'enabled' => true
			),
			1 => array(
				'name' => PureChat::$lang['sm_frown'],
				'id' => 'frown',
				'code' => ':(',
				'img' => $this->smiliesurl . '/sm_frown.png',
                'case' => 'i',
                'enabled' => true
			),
			2 => array(
				'name' => self::$lang['sm_glare'],
				'id' => 'glare',
				'code' => ':mad',
				'img' => $this->smiliesurl . '/sm_glare.png',
                'case' => 'i',
                'enabled' => true
			),
			3 => array(
				'name' => self::$lang['sm_neutral'],
				'id' => 'neutral',
				'code' => ':|',
				'img' => $this->smiliesurl . '/sm_neutral.png',
                'case' => 'i',
                'enabled' => true
			),
			4 => array(
				'name' => self::$lang['sm_wink'],
				'id' => 'wink',
				'code' => ';)',
				'img' => $this->smiliesurl . '/sm_wink.png',
                'case' => 'i',
                'enabled' => true
			),
			5 => array(
				'name' => self::$lang['sm_oh'],
				'id' => 'oh',
				'code' => ':O',
				'img' => $this->smiliesurl . '/sm_oh.png',
                'case' => 'i',
                'enabled' => true
			),
			6 => array(
				'name' => self::$lang['sm_tongue'],
				'id' => 'tongue',
				'code' => ':P',
				'img' => $this->smiliesurl . '/sm_tongue.png',
                'case' => 'i',
                'enabled' => true
			),
			7 => array(
				'name' => self::$lang['sm_dead'],
				'id' => 'dead',
				'code' => 'X.X',
				'img' => $this->smiliesurl . '/sm_dead.png',
                'case' => 'i',
                'enabled' => true
			)
		);
        self::$globals['smilies'] = $smilies;
	}

	private function load_bbc_list()
	{
		self::$globals['bbc_list'] = array(
			0 => array(
				'name' => self::$lang['bbc']['bold'],
				'id' => 'bold',
				'code' => '[b] {text} [/b]'
			),
			1 => array(
				'name' => self::$lang['bbc']['italic'],
				'id' => 'italic',
				'code' => '[i] {text} [/i]'
			),
			2 => array(
				'name' => self::$lang['bbc']['strike'],
				'id' => 'strike',
				'code' => '[s] {text} [/s]'
			),
			3 => array(
				'name' => self::$lang['bbc']['underline'],
				'id' => 'underline',
				'code' => '[u] {text} [/u]'
			),
			4 => array(
				'name' => self::$lang['bbc']['color'],
				'id' => 'color',
				'code' => '[color={input}] {text} [/color]'
			),
			5 => array(
				'name' => self::$lang['bbc']['font'],
				'id' => 'font',
				'code' => '[font={input}] {text} [/font]'
			),
			6 => array(
				'name' => self::$lang['bbc']['size'],
				'id' => 'size',
				'code' => '[size={input}] {text} [size]'
			),
			7 => array(
				'name' => self::$lang['bbc']['html'],
				'id' => 'html',
				'code' => '[html] {text} [/html]'
			),
			8 => array(
				'name' => self::$lang['bbc']['glow'],
				'id' => 'glow',
				'code' => '[glow color={input}] {text} [/glow]'
			)
		);
	}

	private function load_irc_list()
	{
		$irc = array(
			0 => array(
				'name' => self::$lang['available'],
				'id' => 'irc_available',
				'img' => $this->currentthemeurl . '/images/available.png',
				'command' => '/available'
			),
			1 => array(
				'name' => self::$lang['busy'],
				'id' => 'irc_busy',
				'img' => $this->currentthemeurl . '/images/busy.png',
				'command' => '/busy'
			),
			2 => array(
				'name' => self::$lang['away'],
				'id' => 'irc_away',
				'img' => $this->currentthemeurl . '/images/away.png',
				'command' => '/away'
			),
			3 => array(
				'name' => self::$lang['invisible'],
				'id' => 'irc_invisible',
				'img' => $this->currentthemeurl . '/images/invisible.png',
				'command' => '/invisible'
			),
			4 => array(
				'name' => self::$lang['logout'],
				'id' => 'irc_leave',
				'command' => '/quit',
				'img' => $this->currentthemeurl . '/images/leave.png',
			)
		);

		self::$globals['irc_commands'] = $irc;
	}
}