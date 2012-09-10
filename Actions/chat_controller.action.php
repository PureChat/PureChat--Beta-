<?php
/**
 * PureChat (PC)
 *
 * @file ~./Actions/chat_controller.php
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

class Action extends PureChat
{
	private $methods = array();

	public function __construct()
	{
		parent::__construct();
		$this->methods = array(
			'add_post' => 'add_post',
			'remove_post' => 'remove_post',
			'load_new' => 'load_new'
		);
	}
	public function init()
	{
		if (empty($_GET['perform']))
			return false;

		if (array_key_exists($_GET['perform'], $this->methods))
			call_user_func(array($this, $this->methods[$_GET['perform']]));
	}

	private function add_post()
	{
		// We better be logged in, or there's pretty much no point in continuing.
		if (!self::$globals['user']['logged'])
		{
			echo 'not_logged';
			return false;
		}

		// Santize the Post...
		$user = self::$globals['user']['id'];
        $post = trim(htmlspecialchars($_POST['post']));

		// If there was no post to speak of, just forget it.
		if (empty($post))
		{
			echo 'empty_post';
			return false;
		}

		/*
            !! Scotty - You can fix this if you want...but it would require some hacking in.
            if (strlen($_POST['post']) >= 5000)
    		{
    			$post = self::$globals['user']['display_name'] . ' has just attempted to post his life story.';
    			$user = 'ChatBot';
    		}
        */

		$sql = '
			INSERT INTO pc_messages (poster, text, time)
			VALUES (:user, :message, NOW())';
		$params = array(
			':user' => array($user, 'int'),
			':message' => array($post, 'string')			
		);
		$exe = $this->db->query($sql, $params, false);

		if ($exe)
		{
			return true;
		}
		else
		{
			echo 'unkown error';
			return false;
		}
	}

	private function remove_post()
	{
		// Hopefully we're actually logged in.
		if (!self::$globals['user']['logged'])
		{
			echo 'not_logged';
			return false;
		}
		$allow['delete'] = false; // !! Tie this into permissions later.
		$_POST['message_obj'] = explode('_', $_POST['message_obj']);
		$id_user = (int) $_POST['message_obj'][1];
		$id_msg = (int) $_POST['message_obj'][2];
		if (empty($id_user) || empty($id_msg))
		{
			echo 'empty_data';
			return false;
		}
		if (self::$globals['user']['id'] == $id_user || self::$globals['user']['is_admin'])
			$allow['delete'] = true;
		if (!$allow['delete'])
		{
			echo 'invalid_permissions';
			return false;
		}
		$sql = '
			DELETE FROM pc_messages
			WHERE id = :id_msg';
		$params = array(
			':id_msg' => array($id_msg, 'int')
		);
		$this->db->query($sql, $params);
		$return_arr = array(
			'response' => 'msg_deleted',
			'id_user' => $id_user,
			'id_msg' => $id_msg
		);
		echo json_encode($return_arr);
		return true;
	}

	private function load_new()
	{
		if (!self::$globals['user']['logged'])
			return false;

		$last = isset($_POST['last']) ? (int)$_POST['last'] : (int)1;
        require_once($this->includesdir . '/parse.php');
        $parse = new parseObject;
		$sql = '
			SELECT msg.id, msg.poster,
				msg.text, msg.time,
				usr.display_name, usr.avatar
			FROM pc_messages AS msg
			LEFT JOIN pc_users AS usr
				ON (msg.poster = usr.id_user)
			WHERE msg.id > :last
			ORDER BY msg.id DESC';
		$param = array(
			':last' => array($last, 'int')
		);
		$q = $this->db->get_all($sql, $param);

		if (!$q)
			return false;

		$posts = array();
		foreach ($q as $post)
		{
            $post['text'] = $parse->smileys($parse->bbc($post['text'], $post['display_name']), $post['display_name']);
			$posts[] = array(
				'id' => $post['id'],
				'poster' => $post['display_name'],
				'id_poster' => $post['poster'],
				'text' => $post['text'],
				'time' => self::$universal->format_time($post['time']),
				'avatar' => $post['avatar'],
			);
		}
		$posts = array_reverse($posts);

		echo json_encode($posts);

		return true;
	}
}