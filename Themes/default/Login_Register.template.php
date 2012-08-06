<?php
/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/Login_Register.template.php
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

class LoginRegister extends PureChat
{
	protected $reg_captcha;
	public function __construct()
	{
		parent::__construct();
		if (!isset($_SESSION['no_bot']))
		{
			require_once($this->includesdir . '/captcha.php');
			$this->reg_captcha = new CaptchaObject;
			$this->reg_captcha->initalizeCaptcha($this->script . '?action=user&perform=register', 'reg_form', 'registration_submit');
		}
	
		self::$globals['script_vars'] .= '
			var no_bot = ' . !isset($_SESSION['no_bot']) ? 'false' : 'true' . ';
		';

		self::$globals['import_scripts'] .= '
			<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/login_register.js"></script>
		';
	}

	public function content()
	{

		// Content, yo.
		echo '
			<div class="content">
				<h3 class="page_title">', self::$lang['register_form'], '</h3>
				<h5 class="page_description">', self::$lang['registration_caption'], '</h5>
				<form action="', isset($_SESSION['no_bot']) ? $this->script . '?action=user&perform=register' : '', '" method="post" id="reg_form">';


		// Open the form container!
		echo '<div id="registration_form">';

		// Make the errors human readable.
		if (!empty(self::$globals['registration_errors']))
		{
			echo '
				<div class="error">
					', self::$lang['the_bad_news'], '
					<ul>';
					foreach (self::$globals['registration_errors'] as $er => $true)
					{
						echo '<li>', isset(self::$lang[$er]) ? self::$lang[$er] : self::$lang['registration_errors_not_found'], '</li>';
					}
					echo '</ul>
				</div>
			';
		}
		elseif (isset($_GET['success']))
			echo '<div class="success">', self::$lang['account_registered'], '</div>';


		// Woah, we actually get a form? Amazing, innit!
		echo '
						<div class="form_information">
							<input class="form_input" type="text" name="username" id="registration_username"', !empty($_POST['username']) ? ' value="' . $_POST['username'] . '"' : '', ' />
							<label class="form_label" for="registration_username">', self::$lang['display_name'], '</label>
							<span class="form_desc">', self::$lang['display_name_desc'], '</span>
							<hr />
						</div>

						<div class="form_information">
							<input class="form_input" type="text" name="email" id="registration_email"', !empty($_POST['email']) ? ' value="' . $_POST['email'] . '"' : '', ' />
							<label class="form_label" for="registration_username">', self::$lang['email'], '</label>
							<span class="form_desc">', self::$lang['email_desc'], '</span>
							<hr />
						</div>

						<div class="form_information">
							<input class="form_input" type="password" name="password" id="registration_password" />
							<label class="form_label" for="registration_username">', self::$lang['password'], '</label>
							<span class="form_desc">', self::$lang['password_desc'], '</span>
							<hr />
						</div>

						<div class="form_information">
							<input class="form_input" type="password" name="password2" id="registration_password2" />
							<label class="form_label" for="registration_username">', self::$lang['password_again'], '</label>
							<span class="form_desc">', self::$lang['password2_desc'], '</span>
							<hr />
						</div>

						<div class="form_information">
							', !isset($_SESSION['no_bot']) ? '
								<input class="form_button" id="next1" type="button" value="' . self::$lang['next'] . '" />' : '
								<input class="form_button" type="submit" value="' . self::$lang['register'] . '" />'
							, '
						</div>
					</div>';

		// You! Are you human?
		// If you dont pass, we'll hunt you down like the evil bot you are!
		if (!isset($_SESSION['no_bot']))
			echo '
					<div id="user_verification">
						', $this->reg_captcha->template->output(), '
						<div class="form_information" id="submission">
							<input class="form_button" type="submit" id="registration_submit" value="', self::$lang['register'], '" disabled />
							<input id="back1" class="form_button" type="button" value="', self::$lang['back'], '" />
							<span class="noblock form_desc"><em>', self::$lang['mini_agreement'], '</em></span>
						</div>
					</div>
			';

		// Better close the container to avoid stupid side effects.
		echo '
				</form>
			</div>';

	}
}