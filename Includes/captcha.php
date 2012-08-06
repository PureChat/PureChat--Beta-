<?php
/**
 * PureChat (PC)
 *
 * @file ~./Includes/captcha.php
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

class CaptchaObject extends PureChat
{
	public $form_action, $form_id, $submit_id;
	public $boxes, $use_labels, $images;
	public $template, $vars;
	public $randomCaptcha, $captchaSecondRow;
	public function __construct()
	{
		parent::__construct();
		self::$langmethods->load_language('captcha');
	}
	public function initalizeCaptcha($form_action, $form_id, $submit_id, $boxes = 4, $use_labels = false)
	{
		$this->form_action = $form_action;
		$this->form_id = $form_id;
		$this->submit_id = $submit_id;
		$this->boxes = $boxes;
		$this->use_labels = $use_labels;
		$this->images = array(
			array(
				'url' => 'address-book',
				'title' => self::$lang['captcha_address-book']
			),
			array(
				'url' => 'address-book-blue',
				'title' => self::$lang['captcha_address-book-blue']
			),
			array(
				'url' => 'alarm-clock',
				'title' => self::$lang['captcha_alarm-clock']
			),
			array(
				'url' => 'alarm-clock-blue',
				'title' => self::$lang['captcha_alarm-clock-blue']
			),
			array(
				'url' => 'application',
				'title' => self::$lang['captcha_application']
			),
			array(
				'url' => 'application-blue',
				'title' => self::$lang['captcha_application-blue']
			),
			array(
				'url' => 'arrow',
				'title' => self::$lang['captcha_arrow']
			),
			array(
				'url' => 'arrow-090',
				'title' => self::$lang['captcha_arrow-090']
			),
			array(
				'url' => 'arrow-180',
				'title' => self::$lang['captcha_arrow-180']
			),
			array(
				'url' => 'arrow-270',
				'title' => self::$lang['captcha_arrow-270']
			),
			array(
				'url' => 'balloon',
				'title' => self::$lang['captcha_balloon']
			),
			array(
				'url' => 'balloon-facebook',
				'title' => self::$lang['captcha_balloon-facebook']
			),
			array(
				'url' => 'balloon-twitter',
				'title' => self::$lang['captcha_balloon-twitter']
			),
			array(
				'url' => 'battery-charge',
				'title' => self::$lang['captcha_battery-charge']
			),
			array(
				'url' => 'battery-empty',
				'title' => self::$lang['captcha_battery-empty']
			),
			array(
				'url' => 'battery-full',
				'title' => self::$lang['captcha_battery-full']
			),
			array(
				'url' => 'battery-low',
				'title' => self::$lang['captcha_battery-low']
			),
			array(
				'url' => 'battery-plug',
				'title' => self::$lang['captcha_battery-plug']
			),
			array(
				'url' => 'bell',
				'title' => self::$lang['captcha_bell']
			),
			array(
				'url' => 'bin',
				'title' => self::$lang['captcha_bin']
			),
			array(
				'url' => 'bin-metal',
				'title' => self::$lang['captcha_bin-metal']
			),
			array(
				'url' => 'blue-document-text',
				'title' => self::$lang['captcha_blue-document-text']
			),
			array(
				'url' => 'blue-folder',
				'title' => self::$lang['captcha_blue-folder']
			),
			array(
				'url' => 'book',
				'title' => self::$lang['captcha_book']
			),
			array(
				'url' => 'book-brown',
				'title' => self::$lang['captcha_book-brown']
			),
			array(
				'url' => 'bookmark',
				'title' => self::$lang['captcha_bookmark']
			),
			array(
				'url' => 'box',
				'title' => self::$lang['captcha_box']
			),
			array(
				'url' => 'box-label',
				'title' => self::$lang['captcha_box-label']
			),
			array(
				'url' => 'briefcase',
				'title' => self::$lang['captcha_briefcase']
			),
			array(
				'url' => 'calculator',
				'title' => self::$lang['captcha_calculator']
			),
			array(
				'url' => 'calendar-day',
				'title' => self::$lang['captcha_calendar-day']
			),
			array(
				'url' => 'calendar-month',
				'title' => self::$lang['captcha_calendar-month']
			),
			array(
				'url' => 'camera',
				'title' => self::$lang['captcha_camera']
			),
			array(
				'url' => 'camera-lens',
				'title' => self::$lang['captcha_camera-lens']
			),
			array(
				'url' => 'card-address',
				'title' => self::$lang['captcha_card-address']
			),
			array(
				'url' => 'clock',
				'title' => self::$lang['captcha_clock']
			),
			array(
				'url' => 'color',
				'title' => self::$lang['captcha_color']
			),
			array(
				'url' => 'color-swatch',
				'title' => self::$lang['captcha_color-swatch']
			),
			array(
				'url' => 'credit-card',
				'title' => self::$lang['captcha_credit-card']
			),
			array(
				'url' => 'credit-card-green',
				'title' => self::$lang['captcha_credit-card-green']
			),
			array(
				'url' => 'cross',
				'title' => self::$lang['captcha_cross']
			),
			array(
				'url' => 'database',
				'title' => self::$lang['captcha_database']
			),
			array(
				'url' => 'disc',
				'title' => self::$lang['captcha_disc']
			),
			array(
				'url' => 'disc-blue',
				'title' => self::$lang['captcha_disc-blue']
			),
			array(
				'url' => 'disk',
				'title' => self::$lang['captcha_disk']
			),
			array(
				'url' => 'disk-black',
				'title' => self::$lang['captcha_disk-black']
			),
			array(
				'url' => 'document-text',
				'title' => self::$lang['captcha_document-text']
			),
			array(
				'url' => 'drive',
				'title' => self::$lang['captcha_drive']
			),
			array(
				'url' => 'edit',
				'title' => self::$lang['captcha_edit']
			),
			array(
				'url' => 'equalizer',
				'title' => self::$lang['captcha_equalizer']
			),
			array(
				'url' => 'eraser',
				'title' => self::$lang['captcha_eraser']
			),
			array(
				'url' => 'exclamation',
				'title' => self::$lang['captcha_exclamation']
			),
			array(
				'url' => 'feed',
				'title' => self::$lang['captcha_feed']
			),
			array(
				'url' => 'film',
				'title' => self::$lang['captcha_film']
			),
			array(
				'url' => 'fire',
				'title' => self::$lang['captcha_fire']
			),
			array(
				'url' => 'folder',
				'title' => self::$lang['captcha_folder']
			),
			array(
				'url' => 'globe-green',
				'title' => self::$lang['captcha_globe-green']
			),
			array(
				'url' => 'home',
				'title' => self::$lang['captcha_home']
			),
			array(
				'url' => 'image','title'  => 'Image'
			),
			array(
				'url' => 'image-sunset',
				'title' => self::$lang['captcha_image-sunset']
			),
			array(
				'url' => 'inbox',
				'title' => self::$lang['captcha_inbox']
			),
			array(
				'url' => 'information',
				'title' => self::$lang['captcha_information']
			),
			array(
				'url' => 'jar',
				'title' => self::$lang['captcha_jar']
			),
			array(
				'url' => 'jar-label',
				'title' => self::$lang['captcha_jar-label']
			),
			array(
				'url' => 'keyboard',
				'title' => self::$lang['captcha_keyboard']
			),
			array(
				'url' => 'layer',
				'title' => self::$lang['captcha_layer']
			),
			array(
				'url' => 'lifebuoy',
				'title' => self::$lang['captcha_lifebuoy']
			),
			array(
				'url' => 'light-bulb',
				'title' => self::$lang['captcha_light-bulb']
			),
			array(
				'url' => 'light-bulb-off',
				'title' => self::$lang['captcha_light-bulb-off']
			),
			array(
				'url' => 'magnet',
				'title' => self::$lang['captcha_magnet']
			),
			array(
				'url' => 'magnifier',
				'title' => self::$lang['captcha_magnifier']
			),
			array(
				'url' => 'mail',
				'title' => self::$lang['captcha_mail']
			),
			array(
				'url' => 'mail-open',
				'title' => self::$lang['captcha_mail-open']
			),
			array(
				'url' => 'map',
				'title' => self::$lang['captcha_map']
			),
			array(
				'url' => 'marker',
				'title' => self::$lang['captcha_marker']
			),
			array(
				'url' => 'media-player',
				'title' => self::$lang['captcha_media-player']
			),
			array(
				'url' => 'media-player-black',
				'title' => self::$lang['captcha_media-player-black']
			),
			array(
				'url' => 'megaphone',
				'title' => self::$lang['captcha_megaphone']
			),
			array(
				'url' => 'microphone',
				'title' => self::$lang['captcha_microphone']
			),
			array(
				'url' => 'minus',
				'title' => self::$lang['captcha_minus']
			),
			array(
				'url' => 'mobile-phone',
				'title' => self::$lang['captcha_mobile-phone']
			),
			array(
				'url' => 'monitor',
				'title' => self::$lang['captcha_monitor']
			),
			array(
				'url' => 'newspaper',
				'title' => self::$lang['captcha_newspaper']
			),
			array(
				'url' => 'notebook',
				'title' => self::$lang['captcha_notebook']
			),
			array(
				'url' => 'paper-bag',
				'title' => self::$lang['captcha_paper-bag']
			),
			array(
				'url' => 'paper-bag-label',
				'title' => self::$lang['captcha_paper-bag-label']
			),
			array(
				'url' => 'pencil',
				'title' => self::$lang['captcha_pencil']
			),
			array(
				'url' => 'photo-album',
				'title' => self::$lang['captcha_photo-album']
			),
			array(
				'url' => 'photo-album-blue',
				'title' => self::$lang['captcha_photo-album-blue']
			),
			array(
				'url' => 'plus',
				'title' => self::$lang['captcha_plus']
			),
			array(
				'url' => 'point',
				'title' => self::$lang['captcha_point']
			),
			array(
				'url' => 'printer',
				'title' => self::$lang['captcha_printer']
			),
			array(
				'url' => 'receipt-text',
				'title' => self::$lang['captcha_receipt-text']
			),
			array(
				'url' => 'ruler',
				'title' => self::$lang['captcha_ruler']
			),
			array(
				'url' => 'scissors',
				'title' => self::$lang['captcha_scissors']
			),
			array(
				'url' => 'scissors-blue',
				'title' => self::$lang['captcha_scissors-blue']
			),
			array(
				'url' => 'server',
				'title' => self::$lang['captcha_server']
			),
			array(
				'url' => 'service-bell',
				'title' => self::$lang['captcha_service-bell']
			),
			array(
				'url' => 'smiley',
				'title' => self::$lang['captcha_smiley']
			),
			array(
				'url' => 'smiley-lol',
				'title' => self::$lang['captcha_smiley-lol']
			),
			array(
				'url' => 'soap',
				'title' => self::$lang['captcha_soap']
			),
			array(
				'url' => 'socket',
				'title' => self::$lang['captcha_socket']
			),
			array(
				'url' => 'sofa',
				'title' => self::$lang['captcha_sofa']
			),
			array(
				'url' => 'sort',
				'title' => self::$lang['captcha_sort']
			),
			array(
				'url' => 'stamp',
				'title' => self::$lang['captcha_stamp']
			),
			array(
				'url' => 'star',
				'title' => self::$lang['captcha_star']
			),
			array(
				'url' => 'star-empty',
				'title' => self::$lang['captcha_star-empty']
			),
			array(
				'url' => 'sticky-note',
				'title' => self::$lang['captcha_sticky-note']
			),
			array(
				'url' => 'store',
				'title' => self::$lang['captcha_store']
			),
			array(
				'url' => 'store-label',
				'title' => self::$lang['captcha_store-label']
			),
			array(
				'url' => 'switch',
				'title' => self::$lang['captcha_switch']
			),
			array(
				'url' => 'system-monitor',
				'title' => self::$lang['captcha_system-monitor']
			),
			array(
				'url' => 'table',
				'title' => self::$lang['captcha_table']
			),
			array(
				'url' => 'tag',
				'title' => self::$lang['captcha_tag']
			),
			array(
				'url' => 'tag-label',
				'title' => self::$lang['captcha_tag-label']
			),
			array(
				'url' => 'target',
				'title' => self::$lang['captcha_target']
			),
			array(
				'url' => 'television',
				'title' => self::$lang['captcha_television']
			),
			array(
				'url' => 'terminal',
				'title' => self::$lang['captcha_terminal']
			),
			array(
				'url' => 'thumb',
				'title' => self::$lang['captcha_thumb']
			),
			array(
				'url' => 'thumb-up',
				'title' => self::$lang['captcha_thumb-up']
			),
			array(
				'url' => 'tick',
				'title' => self::$lang['captcha_tick']
			),
			array(
				'url' => 'ticket',
				'title' => self::$lang['captcha_ticket']
			),
			array(
				'url' => 'universal',
				'title' => self::$lang['captcha_universal']
			),
			array(
				'url' => 'user',
				'title' => self::$lang['captcha_user']
			),
			array(
				'url' => 'user-business',
				'title' => self::$lang['captcha_user-business']
			),
			array(
				'url' => 'user-business-boss',
				'title' => self::$lang['captcha_user-business-boss']
			),
			array(
				'url' => 'user-female',
				'title' => self::$lang['captcha_user-female']
			),
			array(
				'url' => 'vise',
				'title' => self::$lang['captcha_vise']
			),
			array(
				'url' => 'wall',
				'title' => self::$lang['captcha_wall']
			),
			array(
				'url' => 'wand',
				'title' => self::$lang['captcha_wand']
			),
			array(
				'url' => 'wand-hat',
				'title' => self::$lang['captcha_wand-hat']
			),
			array(
				'url' => 'water',
				'title' => self::$lang['captcha_water']
			),
			array(
				'url' => 'webcam',
				'title' => self::$lang['captcha_webcam']
			),
			array(
				'url' => 'wooden-box',
				'title' => self::$lang['captcha_wooden-box']
			),
			array(
				'url' => 'wooden-box-label',
				'title' => self::$lang['captcha_wooden-box-label']
			),
			array(
				'url' => 'yin-yang',
				'title' => self::$lang['captcha_yin-yang']
			)
		);

		// Some useful variables.
		$this->vars = array(
			'total_images' => (count($this->images) - 1),
			'calculated_width' => round(133.33 * $this->boxes + ($this->boxes * 6)) . 'px'
		);

		// Generate the first row, randomly.
		$this->randomCaptcha = array();
		for ($count = 1; $count <= $this->boxes; $count++)
		{
			do {
				$key = mt_rand(0, ($this->vars['total_images']));
			} while (array_key_exists($key, $this->randomCaptcha));
			$this->randomCaptcha[] = $this->images[$key];
		}

		// Start to figure out the second row, which is scrambled.
		$usableCaptchaImages = $this->randomCaptcha;
		$lastCaptchaImage = (count($usableCaptchaImages) - 1);
		for ($count = 1; $count <= $this->boxes; $count++)
		{
			if (count($usableCaptchaImages) > 1)
			{
				do {
					$key = mt_rand(0, ($lastCaptchaImage));
				} while (!array_key_exists($key, $usableCaptchaImages));
			}
			else
			{
				$key = 0;
				while (!array_key_exists($key, $usableCaptchaImages))
					$key++;
			}
			$this->captchaSecondRow[$key] = $this->randomCaptcha[$key];
			unset($usableCaptchaImages[$key]);
		}
		foreach (array_keys($this->captchaSecondRow) as $key => $value)
		{
			if ($key === $value)
				$identical[] = true;
		}
		if (isset($identical) && count($identical) == $this->boxes)
			$this->captchaSecondRow = array_reverse($this->captchaSecondRow);

		// CSS
		self::$globals['import_scripts'] .= '
		<link rel="stylesheet" type="text/css" href="' . $this->currentthemeurl . '/css/captcha.css" />
		<link rel="stlyesheet" type="text/css" href="' . $this->currentthemeurl . '/css/jquery-ui.min.css" />';

		// Script files.
		self::$globals['import_scripts'] .= '
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/jquery-ui.min.js"></script>
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/captcha.ui.js"></script>';

		self::$globals['script_vars'] .= '
			var captchaCorrect = 0;
			var captcha_submit_id = \'' . $this->submit_id . '\';
			var captchaWidth = \'' . $this->vars['calculated_width'] . '\';
			var form_id = \'' . $this->form_id . '\';
			var form_action = \'' . $this->form_action . '\';
			var submit_id = \'' . $this->submit_id . '\';
			var boxes = \'' . $this->boxes . '\';
			var captchaRandom = ' . json_encode($this->randomCaptcha) . ';
			var captchaSecondRow = ' . json_encode($this->captchaSecondRow) . ';
		';

		// Throw them in the scope.
		self::$globals['captchaRandom'] = $this->randomCaptcha;
		self::$globals['captchaSecondRow'] = $this->captchaSecondRow;
		self::$globals['use_labels'] = $this->use_labels;

		require_once($this->currentthemedir . '/Captcha.template.php');
		$this->template = new CaptchaTemplate;

	}
}