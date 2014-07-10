<?php
/**
 * PureChat (PC)
 *
 * @file ~./Actions/main.action.php
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

class MainAction extends PureChat
{
	public function init()
	{
		// We don't need to do this every time someone posts a new message or something.
		if (!self::$globals['ajax'])
			$this->remove_older();

		$this->update_online_list();
	}

	private function remove_older()
	{
		$sql = '
			SELECT COUNT(id) as count
			FROM pc_messages';
		$q = $this->db->get_one($sql);

		if (!$q || (int)$q['count'] <= 50)
			return false;

		// Assuming there's more than 50 messages, we should delete some older ones.
		$sql = '
			SELECT id
			FROM pc_messages';
		$q = $this->db->get_all($sql);

		$last = end($q);
		$last_id = $last['id'];

		$delete_before = (int)$last_id - 50;

		$sql = '
			DELETE FROM pc_messages
			WHERE id <= :earliest';
		$params = array(
			':earliest' => array($delete_before, 'int')
		);
		$this->db->query($sql, $params);
	}

	private function update_online_list()
	{
		if (self::$globals['user']['logged'])
		{
			$sql = '
				SELECT id
				FROM pc_online
				WHERE user_id = :user';
			$params = array(
				':user' => array(self::$globals['user']['id'], 'int')
			);
			$data = $this->db->get_one($sql, $params);

			// If the person is not in the online table, insert...
			if (!$data)
			{
				$sql = '
					INSERT INTO pc_online (user_id)
					VALUES (:id)';
				$params = array(
					':id' => array(self::$globals['user']['id'], 'int')
				);
				$data = $this->db->query($sql, $params);
			}
			// Okay, so we are on the list, we better update our status.
			else
			{
				$sql = '
					UPDATE pc_online
					SET time = NOW()
					WHERE user_id = :id';
				$param = array(
					':id' => array(self::$globals['user']['id'], 'int')
				);
				$this->db->query($sql, $param);
			}

			// Finally delete the rows that haven't been updated in the last five minutes.
			$sql = '
				DELETE FROM pc_online
				WHERE time < SUBTIME(NOW(), \'0 0:05:0\')';
			$this->db->query($sql);
		}
	}
}