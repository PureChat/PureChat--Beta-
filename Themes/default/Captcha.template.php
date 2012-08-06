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
			<div class="centertext">
				<strong>', self::$lang['captcha_title'], '</strong>
				<span id="captcha_desc">', self::$lang['captcha_desc'], '</span>
				<br />
				<!-- The padding should be 6px + 2 * number of images -->
				<div id="captchaBounds" style="width: 100%;">
					<div id="captchaStart" class="captchaBox captchaRound">';
					foreach (self::$globals['captchaRandom'] as $key => $value)
					{
						echo '
							<div class="captchaTile captchaRound captchaStart centertext" id="tile_', ($key + 1), '">
								<img src="', $this->currentthemeurl, '/images/fugue/24/', $value['url'], '.png" alt="*" />
								', self::$globals['use_labels'] == true ? '<span class="captchaLabel">' . $value['title'] . '</span>' : '', '
							</div>
						';
					}
					echo '
						<br class="clear" />
					</div>
					<br /><br />
					<div id="captchaFinish" class="captchaBox captchaRound">';
					foreach (self::$globals['captchaSecondRow'] as $key => $value)
					{
						echo '
							<div class="captchaTile captchaRound captchaLanding droppable centertext" id="landing_', ($key + 1), '">
								<img src="', $this->currentthemeurl, '/images/fugue/24/', $value['url'], '.png" alt="*" />
								', self::$globals['use_labels'] == true ? '<span class="captchaLabel">' . $value['title'] . '</span>' : '', '
							</div>
						';
					}
					echo '
						<br class="clear" />
					</div>
				</div>
			</div>
		';
	}
}