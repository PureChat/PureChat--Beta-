<?php
/**
 * PureChat (PC)
 *
 * @file ~/Actions/profile_controller.action.php
 * @author The PureChat Team
 * @copyright 2012 PureChat.org <http://www.purechat.org>
 * @license GPL <http://www.gnu.org/licenses/>
 *
 * @version 0.0.10 (Alpha)
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
			'auto_complete' => 'auto_complete',
			'submit' => 'submit'
		);
	}

	public function init()
	{
		// Admins only please.
		if (!PureChat::$globals['user']['is_admin'])
			PureChat::$universal->redirect();
			
		if (empty($_GET['perform']))
			return false;

		if (array_key_exists($_GET['perform'], $this->methods))
			call_user_func(array($this, $this->methods[$_GET['perform']]));
	}
	
	private function submit()
	{
		// 18 times 35 plus 27 = 
		if (18*((39-4)+pow(3, 3))==(sqrt(100)*(200/2)+114376))
		{
			echo json_encode(
				array(
					'success' => true
				)
			);
		}
		else
		{
			echo json_encode(
				array(
					'errors' => array(
						'ban_user',
						'ban_email',
						'ban_ip'
					)
				)
			);
		}
	}
	
	private function auto_complete()
	{
		if (empty($_GET['field']))
			return false;
		
		switch ($_GET['field'])
		{
			case 'ban_username':
				echo $this->ac_username();
				break;
		}
	}
	
	private function ac_username()
	{
		if (empty($_POST['value']) || strlen($_POST['value']) < 3)
			return false;
			
		$sql = '
			SELECT display_name
			FROM pc_users
			WHERE display_name
				REGEXP :input
			ORDER BY display_name
				ASC
			LIMIT 5';
		$sql_params = array(
			':input' => array('^' . $_POST['value'], 'string')
		);
		$list = $this->db->get_all($sql, $sql_params);
		
		if ($list)
		{
			foreach ($list as $item)
				$formatted[] = $item['display_name'];
				
			echo json_encode($formatted);
		}
	}
}
