<?php
/**
 * PureChat (PC)
 *
 * @file ~./Actions/online_list.action.php
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
			'return_list' => 'return_list'
		);
	}
	
	public function init()
	{
		if (empty($_GET['perform']))
			return false;

		if (array_key_exists($_GET['perform'], $this->methods))
			call_user_func(array($this, $_GET['perform']));
	}

	private function return_list()
	{
		if (!self::$globals['user']['logged'])
			return false;

		$sql = '
			SELECT onlist.user_id,
                users.status, users.display_name
			FROM pc_online AS onlist
			INNER JOIN pc_users AS users
				ON (onlist.user_id = users.id_user)
			WHERE time > SUBTIME(NOW(), \'0 0:05:0\')
			ORDER BY display_name';
		$data = $this->db->get_all($sql);
		$response = array();

		if (empty($data))
			return false;

		foreach ($data as $i)
		{
			switch ($i['status'])
			{
				case 'available':
					$s = array(
						'text' => self::$lang['available'],
						'icon' => $this->currentthemeurl . '/images/available.png'
					);
					break;

				case 'busy':
					$s = array(
						'text' => self::$lang['busy'],
						'icon' => $this->currentthemeurl . '/images/busy.png'
					);
					break;

				case 'invisible':
					$s = array(
						'text' => self::$lang['invisible'],
						'icon' => $this->currentthemeurl . '/images/invisible.png'
					);
					break;

				case 'away':
					$s = array(
						'text' => self::$lang['away'],
						'icon' => $this->currentthemeurl . '/images/away.png'
					);
					break;
			}
			if ($i['status'] != 'invisible' || (self::$globals['user']['is_admin'] || self::$globals['user']['is_mod']))
			{
				$response['members'][] = array(
					'id' => $i['user_id'],
					'name' => $i['display_name'],
					'status' => $s
				);
			}
		}
		$response['total'] = count($response['members']);
		
		echo json_encode($response);
	}
}