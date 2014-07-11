<?php
/**
 * PureChat (PC)
 *
 * @file ~./Sources/user.source.php
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

class User extends PureChat
{
	public function load_user_info()
	{
		if (!isset($_SESSION['user_id']))
		{
			$this->make_guest();
			return false;
		}

		if (isset($_SESSION['user']['full_load']))
		{
			PureChat::$globals['user'] = &$_SESSION['user'];
			return true;
		}

		$sql = '
			SELECT id_user, approved, user_group,
				email, first_name, last_name,
				display_name, avatar, total_posts, status
			FROM pc_users
			WHERE id_user = :user_id';
		$parameters = array(
			':user_id' => array($_SESSION['user_id'], 'int')
		);

		$user = $this->db->get_one($sql, $parameters);

		if (!$user)
		{
			$this->make_guest();
			return false;
		}

		$_SESSION['user'] = array(
			'logged' => true,
			'approved' => $user['approved'],
			'group' => $user['user_group'],
			'is_admin' => (int)$user['user_group'] == 1 ? true : false,
			'is_mod' => (int)$user['user_group'] == 2 ? true : false,
			'is_guest' => false,
			'id' => $user['id_user'],
			'email' => $user['email'],
			'first_name' => $user['first_name'],
			'last_name' => $user['last_name'],
			'display_name' => $user['display_name'],
			'avatar' => !empty($user['avatar']) ? $user['avatar'] : null,
			'posts' => (int) $user['total_posts'],
			'status' => (string) $user['status'],
			'full_load' => true
		);
		PureChat::$globals['user'] = &$_SESSION['user'];
	}

	public function get_groups()
	{
		$sql = '
			SELECT grp.id_group, grp.group_name , grp.group_type
			FROM pc_groups AS grp
			ORDER BY grp.id_group ASC';
		$param = array(
		);
		$q = $this->db->get_all($sql, $param);
		if (!$q)
			return false;
		foreach ($q as $key => $value)
		{
			PureChat::$globals['groups'][$value['id_group']] = array(
				'name' => $value['group_name'],
				'type' => $value['group_type']
			);
		}	
	}

	private function make_guest()
	{
		PureChat::$globals['user'] = array(
			'logged' => (bool) false,
			'approved' => (bool) false,
			'group' => 0,
			'is_admin' => false,
			'is_mod' => false,
			'is_guest' => true,
			'id' => (int)-1,
			'email' => '',
			'first_name' => '',
			'last_name' => '',
			'display_name' => PureChat::$lang['guest'],
			'avatar' => '',
			'posts' => '',
			'status' => 'available'
		);
	}
}