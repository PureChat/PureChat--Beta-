<?php
/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/Captcha.template.php
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

class CaptchaTemplate extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	public function output()
	{
		echo '
			<div id="captcha_cont">
				<strong>', PureChat::$lang['captcha_title'], '</strong>
				<br />
				<span id="captcha_desc">', PureChat::$lang['captcha_desc'], '</span>
				<div id="captcha">
					<div class="upper_space first_column">';
					$current_item = 0;
					foreach (PureChat::$globals['captcha']['random'] as $key => $value)
					{
						echo '
							<div id="tile_', ($key + 1), '" class="start centertext' , $current_item != 0 ? ' middle' : '', $current_item == 0 && PureChat::$globals['captcha']['box_count'] == 1 ? ' top_left top_right' : ($current_item == 0 ? ' top_left' : ($current_item + 1 == PureChat::$globals['captcha']['box_count'] ? ' top_right' : '')), '">
								<img src="', $this->currentthemeurl, '/images/fugue/24/', $value['url'], '.png" alt="', $value['title'], '" title="', $value['title'], '" />
								', PureChat::$globals['captcha']['use_labels'] == true ? '<span class="label">' . $value['title'] . '</span>' : '', '
							</div>
						';
						++$current_item;
					}
					echo '
						<br class="clear" />
					</div>
					<div class="second_column">';
					$current_item = 0;
					foreach (PureChat::$globals['captcha']['second_row'] as $key => $value)
					{
						echo '
							<div id="landing_', ($key + 1), '" class="landing droppable centertext' , $current_item != 0 ? ' middle' : '', $current_item == 0 && PureChat::$globals['captcha']['box_count'] == 1 ? ' bottom_left bottom_right' : ($current_item == 0 ? ' bottom_left' : ($current_item + 1 == PureChat::$globals['captcha']['box_count'] ? ' bottom_right' : '')), '">
								<img src="', $this->currentthemeurl, '/images/fugue/24/', $value['url'], '.png" alt="', $value['title'], '" title="', $value['title'], '" />
								', PureChat::$globals['captcha']['use_labels'] == true ? '<span class="label">' . $value['title'] . '</span>' : '', '
							</div>
						';
						++$current_item;
					}
					echo '
						<br class="clear" />
					</div>
				</div>
			</div>
		';
	}
}