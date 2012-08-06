<?php
/**
 * PureChat (PC)
 *
 * @file ~/Actions/profile_controller.action.php
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
	private $methods;

	public function __construct()
	{
		parent::__construct();
		$this->methods = array(
			'load_info' => 'load_info',
			'update' => 'update',
			'save_avatar' => 'save_avatar'
		);
	}

	public function init()
	{
		if (empty($_GET['perform']))
			return false;

		if (array_key_exists($_GET['perform'], $this->methods))
			call_user_func(array($this, $this->methods[$_GET['perform']]));
	}

	private function load_info()
	{
		if (!self::$globals['user']['logged'] || !isset($_POST['user_id']))
			return false;

		$sql = '
			SELECT id_user, first_name, last_name, email, display_name, status, avatar
			FROM pc_users
			WHERE id_user = :user';
		$param = array(
			':user' => array($_POST['user_id'], 'int')
		);
		$info = $this->db->get_one($sql, $param);

		if (!$info)
			return false;

		echo json_encode(
			array(
				'allow_edit' => self::$globals['user']['is_admin'] || self::$globals['user']['id'] == (int)$_POST['user_id'] ? 'true' : 'false',
				'data' => $info
			)
		);
	}

	private function update()
	{
		if (empty($_POST['field']) || empty($_POST['value']) || empty($_POST['id']) || !self::$globals['user']['logged'])
			return false;

		$owned = (int)$_POST['id'] == (int)self::$globals['user']['id'] ? true : false;

		// Permission check.
		if (!self::$globals['user']['is_admin'] && !$owned)
			return false;

		switch ($_POST['field'])
		{
			case 'display_name':
				$field = 'display_name';
				$value = $_POST['value'];
				break;

			case 'first_name':
				$field = 'first_name';
				$value = $_POST['value'];
				break;

			case 'last_name':
				$field = 'last_name';
				$value = $_POST['value'];
				break;

			case 'email':
				$field = 'email';
				$value = $_POST['value'];
				break;

			case 'avatar':
				$field = 'avatar';
				$value = $_POST['value'];
				break;

			default:
				return false;
		}

		$sql = '
			UPDATE pc_users
			SET ' . $field . ' = :value
			WHERE id_user = :user';
		$params = array(
			':value' => array($value, 'string'),
			':user' => array($_POST['id'], 'int')
		);
		$success = $this->db->query($sql, $params);

		if ($success)
		{
			self::$langmethods->load_language('profile');
			
			$return = array(
				'status' => 'success',
				'title' => self::$lang['fieldname_' . $field],
				'data' => htmlspecialchars(trim($value), ENT_QUOTES)
			);

			echo json_encode($return);
		}
		else
			echo json_encode(array('status' => 'unsuccessful_query'));
	}

	private function save_avatar()
	{
		if (empty($_GET['member']))
			return false;

		$member = (int)$_GET['member'];

		// !! Permissionize
		if (!self::$globals['user']['logged'] || ($member != self::$globals['user']['id'] && !self::$globals['user']['is_admin']))
			return false;

		if (!empty($_GET['type']) && $_GET['type'] == 'file')
		{
			$allowed = array(
				'image/gif' => '.gif',
				'image/jpeg' => '.jpeg',
				'image/pjpeg' => '.jpeg',
				'image/png' => '.png'
			);
			if (!array_key_exists($_FILES['avatar']['type'], $allowed) || $_FILES['avatar']['size'] > 256000)
				return false;

			 if ($_FILES['avatar']['error'] > 0)
				return false;

			$filename = md5(date('m/d/Y-H:i:s') . $_FILES['avatar']) . $allowed[$_FILES['avatar']['type']];

			 if (file_exists($this->root . '/Attachments/avatars/' . $filename))
			 {
				$file_version = 0;
				while(file_exists($this->root . '/Attachments/avatars/' . $filename))
				{
					$filename = $file_version . $filename;
					$file_version++;
				}
			 }

			move_uploaded_file($_FILES['avatar']['tmp_name'], $this->root . '/Attachments/avatars/' . $filename);

			$response['type'] = 'file';
			$value = $this->rooturl . 'Attachments/avatars/' . $filename;
		}
		else if (!empty($_GET['type']) && $_GET['type'] == 'url')
		{
			if (!empty($_POST['image_url']))
				return false;

			$response['type'] = 'url';
			$value = $_POST['image_url'];
		}

		$sql = '
			UPDATE pc_users
			SET avatar = :new_url
			WHERE id_user = :user';
		$params = array(
			':new_url' => array($value, 'string'),
			':user' => array($member, 'int')
		);
		$success = $this->db->query($sql, $params);

		if ($success)
		{
			$response['status'] = 'success';
			$response['new_url'] = $value;
			$response['member_id'] = $member;
		}
		else
			$response['status'] = 'fail';

		echo json_encode($response);
	}
}