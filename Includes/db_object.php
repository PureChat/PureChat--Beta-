<?php
/**
 * PureChat (PC)
 *
 * @file ~./Includes/db_object.php
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

class DatabaseController
{
	private $abstraction;
	public $error = false;
	public $error_message;
	public function __construct($host=null, $name=null, $username=null, $password=null)
	{
		if (empty($host) || empty($name)  || empty($username))
		{
			$this->error = true;
			return false;
		}
		try
		{
			// We may decide to put this all on one line later, but it gets a little hard to read that way.
			$this->abstraction = new PDO(
				'mysql:host=' . $host . ';dbname=' . $name,
				$username,
				$password
			);
		}
		catch (Exception $e)
		{
			$this->error = true;
			$this->error_message = $e;
		}
	}

	public function get_all($sql=null, $parameters=null)
	{
		if (empty($sql) || $this->error)
			return false;

		$statement = $this->abstraction->prepare($sql);

		if (!$statement)
			return false;

		if (is_array($parameters))
		{
			foreach ($parameters as $identifier => $info)
			{
				switch ($info[1])
				{
					case 'int':
						$binder = PDO::PARAM_INT;
					break;

					case 'string':
						$binder = PDO::PARAM_STR;
						break;

					default:
						$binder = PDO::PARAM_STR;
						break;
				}

				$statement->bindParam($identifier, $info[0], $binder);
			}
		}

		$statement->execute();
		$data = $statement->fetchAll();
		$statement->closeCursor();

		return $data ? $data : false;
	}

	public function get_one($sql=null, $parameters=null)
	{
		if (empty($sql) || $this->error)
			return false;

		$statement = $this->abstraction->prepare($sql);

		if (!$statement)
			return false;

		if (is_array($parameters))
		{
			foreach ($parameters as $identifier => $info)
			{
				switch ($info[1])
				{
					case 'int':
						$binder = PDO::PARAM_INT;
					break;

					case 'string':
						$binder = PDO::PARAM_STR;
						break;

					default:
						$binder = PDO::PARAM_STR;
						break;
				}

				$statement->bindParam($identifier, $info[0], $binder);
			}
		}

		$statement->execute();
		$data = $statement->fetch(PDO::FETCH_ASSOC);
		$statement->closeCursor();

		return $data ? $data : false;
	}

	public function query($sql=null, $parameters=null, $strict=true)
	{
		if (empty($sql) || $this->error)
			return false;

		$statement = $this->abstraction->prepare($sql);

		if (!$statement)
			return false;

		if (is_array($parameters))
		{
			foreach ($parameters as $identifier => $info)
			{
				$val = $info[0];
				switch ($info[1])
				{
					case 'int':
						$binder = PDO::PARAM_INT;
					break;

					case 'string':
						$binder = PDO::PARAM_STR;
						if ($strict)
							$val = htmlspecialchars(trim($val), ENT_QUOTES);
						break;

					default:
						$binder = PDO::PARAM_STR;
						if ($strict)
							$val = htmlspecialchars(trim($val), ENT_QUOTES);
						break;
				}

				$statement->bindParam($identifier, $val, $binder);
				unset ($val);
			}
		}

		$statement->execute();
		$statement->closeCursor();

		return true;;
	}

	public function last_insert($name=null)
	{
		if ($this->error)
			return false;
		
		if ($name)
			return $this->abstraction->lastinsertid($name);
		else
			return $this->abstraction->lastinsertid();
	}
}