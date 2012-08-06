<?php
/**
 * PureChat (PC)
 *
 * @file ~./Includes/rand_str.php
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

class rand_str extends PureChat
{
	public $string;
	public function __construct($length = 6, $char_set = 'all', $case = 'both')
	{
		$length = (int) $length;
		if (empty($length))
			return false;
		elseif ($length > 30)
			$length = 30;
	
		$rand_str = '';
	
		$allowed_case_types = array(
			'lower', 'upper', 'both'
		);
	
		if (!in_array($case, $allowed_case_types))
			$case = 'both';
	
		$alphabetical_characters = array(
			'lowercase' => array(
				'a', 'b', 'c', 'd', 'e', 'f',
				'g', 'h', 'i', 'j', 'k', 'l',
				'm', 'n', 'o', 'p', 'q', 'r',
				's', 't', 'u', 'v', 'w', 'x',
				'y', 'z'
			),
			'uppercase' => array(
				'A', 'B', 'C', 'D', 'E', 'F',
				'G', 'H', 'I', 'J', 'K', 'L',
				'M', 'N', 'O', 'P', 'Q', 'R',
				'S', 'T', 'U', 'V', 'W', 'X',
				'Y', 'Z'
			)
		);
	
		$numerical_characters = array(
			'1', '2', '3',
			'4', '5', '6',
			'7', '8', '9',
			'0'
		);
	
		$special_characters = array(
			'!', '@', '#', '$',
			'%', '^', '&', '*',
			'(', ')', '-', '_',
			'+', '=', '"', '\'',
			'<', ',', '>', '.',
			'?', '/', '[', ']'
		);
	
		if ($case === 'lower')
			$alphabetical_characters = $alphabetical_characters['lowercase'];
		elseif ($case === 'upper')
			$alphabetical_characters = $alphabetical_characters['uppercase'];
		else
			$alphabetical_characters = array_merge($alphabetical_characters['lowercase'], $alphabetical_characters['uppercase']);
	
		switch ($char_set)
		{
			case 'alphabetical':
				$char_set = &$alphabetical_characters;
				break;
			case 'numerical':
				$char_set = &$numerical_characters;
				break;
			case 'alphanumerical':
				$char_set = array_merge($alphabetical_characters, $numerical_characters);
				break;
			case 'special_chars':
				$char_set = &$special_characters;
				break;
			default:
				$char_set = array_merge($alphabetical_characters, $numerical_characters, $special_characters);
				break;
		}
	
		$temp_arr = array_keys($char_set);
		for ($i = 1; $i <= $length; $i++)
		{
			$rand_select = mt_rand(reset($temp_arr), end($temp_arr));
			$rand_str .= $char_set[$rand_select];
		}

		$this->string = $rand_str;
	}
}