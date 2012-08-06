<?php
/**
 * PureChat (PC)
 *
 * @file ~./Sources/language_methods.sub.source.php
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

class LanguageMethods extends PureChat
{
	public function __construct()
	{
		parent::__construct();
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
}