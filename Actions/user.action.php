<?php
/**
 * PureChat (PC)
 *
 * @file ~./Actions/user.action.php
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
			'login' => 'Login',
			'logout' => 'Logout',
			'register' => 'Register',
			'activate_account' => 'ActivateAccount',
			'status_update' => 'StatusUpdate'
		);
	}

	public function init()
	{
		if (empty($_GET['perform']))
			return false;

		if (array_key_exists($_GET['perform'], $this->methods))
			call_user_func(array($this, $this->methods[$_GET['perform']]));
	}

	private function Login()
	{
		if (empty($_POST['login_username']) || empty($_POST['login_password']))
		{
			header('Location:' . $this->script . '?error=empty_value');
			exit;
		}

		$sql = '
			SELECT id_user, approved, display_name,
				email, password, password_salt
			FROM pc_users
			WHERE display_name = :username OR email = :username';
		$params = array(
			':username' => array(trim(htmlspecialchars($_POST['login_username'])), 'string')
		);
		$_SESSION['user'] = $this->db->get_one($sql, $params);
		$user_info = &$_SESSION['user'];

		if (!$user_info)
		{
			header('Location:' . $this->script . '?error=no_user');
			exit;
		}

		if ($user_info['approved'] != 1)
		{
			header('Location:' . $this->script . '?error=not_activated');
			exit;
		}

		// Re-Encrypt anything that's not already encrypted...
		if (empty($user_info['password_salt']))
		{
			$encrypted_password = PureChat::$universal->encrypt_password($user_info['password']);
			$update_columns = array(
				'password' => array(
					$encrypted_password['password'],
					'string'
				),
				'password_salt' => array(
					$encrypted_password['salt_string'],
					'string'
				)
			);
			PureChat::$universal->update_user($user_info['id_user'], $update_columns);
		}

		$salt = explode('_', $user_info['password_salt']);
		$dbpassword = $user_info['password'];
		$password = hash('sha512', $salt[0] . $_POST['login_password'] . $salt[1]);

		if ($dbpassword == $password)
		{
			$id = $user_info['id_user'];
			$_SESSION['user_id'] = $id;

			header('Location:' . $this->script);
			exit;
		}
		else
		{
			header('Location:' . $this->script . '?error=password');
			exit;
		}
	}

	private function Logout()
	{
		// It's just that easy.
		if (isset($_SESSION['user_id']))
		{
			// Remove from the online list.
			$sql = '
				DELETE FROM pc_online
				WHERE user_id = :id';
			$param = array(
				':id' => array(PureChat::$globals['user']['id'], 'int')
			);
			$this->db->query($sql, $param);

			// Kill the user session variables.
			unset($_SESSION['user_id'], $_SESSION['user']);
			header('Location:' . $this->script);
			exit();
		}
	}

	private function Register()
	{
		if (isset($_POST['username']))
		{
			$registration_mode = 1; // 1 = Email Activaton.
			$reg_approve_var = $registration_mode == 1 ? 0 : 1;
			$errors = array();
	
			// If they've submitted the form in the first place, they must be human.
			// This is the only place we use a session var in registration.
			$_SESSION['no_bot'] = true;
	
			$regFields = array(
				'email',
				'username',
				'password',
				'password2'
			);
	
			foreach ($regFields as $value)
			{
				if (empty($_POST[$value]))
					$errors['empty_' . $value] = true;
			}
	
			if (!empty($_POST['username']) && !empty($_POST['email']))
			{
				$sql = '
				SELECT display_name, email
				FROM pc_users
				WHERE display_name = :name OR email = :email';
				$params = array(
					':name' => array($_POST['username'], 'string'),
					':email' => array($_POST['email'], 'string')
				);
				$exists = $this->db->get_one($sql, $params);
	
				// Existing user errors.
				if (!empty($exists))
				{
					if ($exists['email'] == $_POST['email'])
						$errors['email_exists'] = true;
	
					if ($exists['display_name'] == $_POST['username'])
						$errors['display_name_exists'] = true;
				}
			}
			
			// Validate the email address.
			if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$errors['invalid_email'] = true;
	
			// Make sure the username isn't in the password.
			if (!empty($_POST['password']) && !empty($_POST['password2']))
			{
				if ($_POST['password'] != $_POST['password2'])
					$errors['passwords_no_match'] = true;
			}
	
			// If there are errors at this point, save them and continue.
			if (!empty($errors))
			{
				PureChat::$globals['registration_errors'] = $errors;
				return false;
			}

			// Encrypt our password...
			$encrypted_password = PureChat::$universal->encrypt_password($_POST['password']);

			// Otherwise we're clear for registration.
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
	
			$sql = '
				INSERT INTO pc_users (approved, email, display_name, password, password_salt)
				values (:approved, :email, :name, :pass, :salt)';
			$params = array(
				':approved' => array($reg_approve_var, 'int'),
				':email' => array($email, 'string'),
				':name' => array($username, 'string'),
				':pass' => array($encrypted_password['password'], 'string'),
				':salt' => array($encrypted_password['salt_string'], 'string')
			);
	
			$this->db->query($sql, $params);
			$user_id = $this->db->last_insert();
	
			// Email Activation
			if ($registration_mode == 1)
			{
				require_once($this->includesdir . '/rand_str.php');
				$rand = new rand_str(16, 'alphanumerical');
				$user_rand = $rand->string;
	
				// Send them a nice little email.
				$email_body = str_replace(
					array(
						'{user}',
						'{pass}',
						'{activate_link}'
					),
					array(
						$_POST['username'],
						$this->formatEmailPassword($_POST['password']),
						$this->script . '?action=user&perform=activate_account&key=' . $user_rand
					),
					PureChat::$lang['reg_email_body']
				);
	
				mail($_POST['email'], PureChat::$lang['reg_email_subject'], $email_body);
	
				$approve_sql = '
					INSERT INTO pc_reg_activations (id_user, activation_key)
					values (:id_user, :activation_key)
				';
				$approve_params = array(
					':id_user' => array($user_id, 'int'),
					':activation_key' => array($user_rand, 'string')
				);
				$this->db->query($approve_sql, $approve_params);
			}
			
			PureChat::$universal->redirect($this->script . '?success=true');
		}
	}

	private function ActivateAccount()
	{
		if (empty($_GET['key']) || !isset($_GET['key']))
			header('Location:' . $this->script . '?error=empty_act_key');
		$sql = '
			SELECT id_user, activation_key
			FROM pc_reg_activations
			WHERE activation_key = :activation_key
		';
		$parameters = array(
			':activation_key' => array($_GET['key'], 'string')
		);
		$member = $this->db->get_one($sql, $parameters);
		if (empty($member))
			header('Location:' . $this->script . '?error=invalid_key');
		$sql = '
			UPDATE pc_users
			SET approved = :approved
			WHERE id_user = :this_user
		';
		$parameters = array(
			':approved' => array(1, 'int'),
			':this_user' => array($member['id_user'], 'int')
		);
		$this->db->query($sql, $parameters);
		$sql = '
			DELETE FROM pc_reg_activations
			WHERE id_user = :this_user
		';
		$parameters = array(
			':this_user' => array($member['id_user'], 'int')
		);
		$this->db->query($sql, $parameters);
		header('Location:' . $this->script . '?page=login_register&activated=true');
		exit;
	}

	private function formatEmailPassword($password = '')
	{
		if (empty($password))
			return false;
		$length = strlen($password);
		$final_pass = '';
		if ($length <= 2)
		{
			// If we only have one or two characters, uhh...no help, sorry.
			for ($count = 1; $count <= $length; $count++)
				$final_pass .= '*';
		}
		else
		{
			// Give them the last two characters.
			for ($count = 1; $count <= ($length - 2); $count++)
			{
				$final_pass .= '*';
			}
			$final_pass .= substr($password, -2, 2);
		}
		return $final_pass;
	}

	private function StatusUpdate()
	{
		// Better not be empty!
		if (empty($_POST['status']) || !PureChat::$globals['user']['logged'])
		{
			echo 'nope';
			return false;
		}

		$possibles = array(
			'busy',
			'away',
			'invisible',
			'available',
			'AFK'
		);

		if (in_array($_POST['status'], $possibles))
			$status = $_POST['status'];
		else
			return false;
		
		$uid = PureChat::$globals['user']['id'];

		$sql = '
			UPDATE pc_users
			SET status = :status
			WHERE id_user = :id';
		$params = array(
			':status' => array($status, 'string'),
			':id' => array($uid, 'int')
		);
		$this->db->query($sql, $params);
	}
}