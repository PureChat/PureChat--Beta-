<?php
class Universal extends PureChat
{
	public function redirect($destination = null, $type = null)
	{
		if (!$destination)
			return false;

		switch ($type)
		{
			case null:
				$prefix = '';
				break;

			case 'page':
				$prefix = $this->script . '?page=';
				break;

			case 'action':
				$prefix = $this->script . '?action=';
				break;

			default:
				$prefix = '';
		}

		header('Location:' . $prefix . $destination);
		exit; // I guess this is the same as returning true, eh?
	}

	// Encrypt (Hash & Salt) Password(s)
	public function encrypt_password($password)
	{
		// Generate two grains of salt.
		$salt = array(
			mt_rand(0, 1000),
			mt_rand(0, 1000)
		);

		// Hash the password.
		$salted['password'] = hash('sha512', $salt[0] . $password . $salt[1]);

		// Imploded Grains of Salt.
		$salted['salt_string'] = $salt[0] . '_' . $salt[1];

		return $salted;
	}

	// Update User Columns
	public function update_user($id_user, $column_updates)
	{
		$parameters = array();
		$start_count = 1;
		$column_count = count($column_updates);
		$sql = 'UPDATE pc_users SET';
		foreach ($column_updates as $key => $value)
		{
			$sql .= ' ' . $key . ' = :' . $key . ($start_count++ != $column_count ? ',' : '');
			$parameters[':' . $key] = array($value[0], $value[1]);
			$user_info[$key] = $value[0];
		}
		$parameters[':id_user'] = array($id_user, 'int');
		$sql .= ' WHERE id_user = :id_user';
		$this->db->query($sql, $parameters);
		return true;
	}

	// Untested and not recommended for use.
	public function post_message($data, $chatbot = false)
	{
		if (!self::$globals['user']['logged'])
			return false;

		$user = self::$globals['user']['id'];
        $post = trim(htmlspecialchars($_POST['post']));

		if (empty($post))
			return false;

		$sql = '
			INSERT INTO pc_messages (poster, text, time)
			VALUES (:user, :message, NOW())';
		$params = array(
			':user' => array($user, 'int'),
			':message' => array($post, 'string')
		);
		$exe = $this->db->query($sql, $params, false);

		if ($exe)
			return true;

		else
		{
			echo 'unkown error';
			return false;
		}
	}
	
	public function load_source($name, $class, &$class_ref, $initial_method = null)
	{
		if (empty($name) || empty($class))
			return false;
	
		if (file_exists($this->sourcesdir . '/' . $name . '.source.php'))
		{
			require_once($this->sourcesdir . '/' . $name . '.source.php');
			
			if (!class_exists($class))
				return false;
			
			$class_ref = new $class;
			
			if ($initial_method && method_exists($class_ref, $initial_method))
				call_user_func(array($class_ref, $initial_method));
		}
		
		return true;	
	}
	
	public function load_template($classes = array(), $methods = array())
	{
		// Reset the array so we don't load two templates accidentally.
		self::$globals['template'] = array();
		
		foreach ($classes as $named => $class)
		{
			$filepath = $this->currentthemedir . '/' . $class['file'] . '.template.php';
			if (file_exists($filepath))
			{
				require_once($filepath);
				self::$globals['template']['classes'][$named] = new $class['class'];
			}
			unset($filepath);
		}
		foreach ($methods as $layer => $value)
		{
			if (array_key_exists($value['class_key'], self::$globals['template']['classes']) && (!isset($value['condition']) || $value['condition'] == true))
			{
				if (method_exists(self::$globals['template']['classes'][$value['class_key']], $value['method']))
				{
					self::$globals['template']['methods'][$layer] = array(
						'object' => self::$globals['template']['classes'][$value['class_key']],
						'method' => $value['method']
					);
				}
			}
		}
	}
	
	public function load_language($file)
	{
		// Query time. Mhm.
		$sql = '
			SELECT value
			FROM pc_settings
			WHERE setting = \'language\'';
		$language = $this->db->get_one($sql);

		// If there is no lanuage setting or specified file, something is amiss.
		if (!$language || empty($file))
			return false;

		// Put the file name together for ease of use in the following condition.
		$file = $this->languagesdir . '/' . $language['value'] . '.' . $file . '.php';
		
		// Finally load the language file.
		if (file_exists($file))
			require_once($file);

		if (!empty($lang))
		{
			foreach ($lang as $index => $value)
				self::$lang[$index] = $value;
			
			return true;
		}
		return false;
	}

	public function format_time($timestamp)
	{
		$timestamp = explode(' ', $timestamp);
		$timestamp = explode(':', $timestamp[1]);
		if ($timestamp[0] > 12)
		{
			$format = 'pm';
			$timestamp[0] -= 12;
		}
		else
			$format = 'am';
		if ($timestamp[0] == '00')
			$timestamp[0] = 12;
		return implode(':', $timestamp) . $format;
	}

	public function convert_time($return_format, $time_params)
	{
		// The only parameter we force.
		if (empty($time_params['year']))
			$time_params['year'] = date('Y');

		// If the rest are all empty, return a false boolean.
		if (empty($time_params['hour']) && empty($time_params['minute']) && empty($time_params['second']) && empty($time_params['month']) && empty($time_params['day']))
			return false;

		// Begin the formatting of our time-stamp.
		$formatted_time = mktime($time_params['hour'], $time_params['minute'], $time_params['second'], $time_params['month'], $time_params['day'], $time_params['year']);
		
		return date($return_format, $formatted_time);
	}
}
