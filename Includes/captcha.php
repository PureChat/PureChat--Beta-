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
	public $template, $vars, $total_width;
	public $random_captcha, $second_row;
	public function __construct()
	{
		parent::__construct();
		call_user_func(array(PureChat::$universal, 'load_language'), 'captcha');
	}
	public function initalize_captcha($form_action, $form_id, $submit_id, $boxes = 4, $use_labels = false)
	{
		$this->form_action = $form_action;
		$this->form_id = $form_id;
		$this->submit_id = $submit_id;
		$this->boxes = $boxes;
		$this->use_labels = $use_labels;
		$this->total_width = $this->boxes * 15;
		$this->images = array(
			array(
				'url' => 'address-book',
				'title' => PureChat::$lang['captcha_address-book']
			),
			array(
				'url' => 'address-book-blue',
				'title' => PureChat::$lang['captcha_address-book-blue']
			),
			array(
				'url' => 'alarm-clock',
				'title' => PureChat::$lang['captcha_alarm-clock']
			),
			array(
				'url' => 'alarm-clock-blue',
				'title' => PureChat::$lang['captcha_alarm-clock-blue']
			),
			array(
				'url' => 'application',
				'title' => PureChat::$lang['captcha_application']
			),
			array(
				'url' => 'application-blue',
				'title' => PureChat::$lang['captcha_application-blue']
			),
			array(
				'url' => 'arrow',
				'title' => PureChat::$lang['captcha_arrow']
			),
			array(
				'url' => 'arrow-090',
				'title' => PureChat::$lang['captcha_arrow-090']
			),
			array(
				'url' => 'arrow-180',
				'title' => PureChat::$lang['captcha_arrow-180']
			),
			array(
				'url' => 'arrow-270',
				'title' => PureChat::$lang['captcha_arrow-270']
			),
			array(
				'url' => 'balloon',
				'title' => PureChat::$lang['captcha_balloon']
			),
			array(
				'url' => 'balloon-facebook',
				'title' => PureChat::$lang['captcha_balloon-facebook']
			),
			array(
				'url' => 'balloon-twitter',
				'title' => PureChat::$lang['captcha_balloon-twitter']
			),
			array(
				'url' => 'battery-charge',
				'title' => PureChat::$lang['captcha_battery-charge']
			),
			array(
				'url' => 'battery-empty',
				'title' => PureChat::$lang['captcha_battery-empty']
			),
			array(
				'url' => 'battery-full',
				'title' => PureChat::$lang['captcha_battery-full']
			),
			array(
				'url' => 'battery-low',
				'title' => PureChat::$lang['captcha_battery-low']
			),
			array(
				'url' => 'battery-plug',
				'title' => PureChat::$lang['captcha_battery-plug']
			),
			array(
				'url' => 'bell',
				'title' => PureChat::$lang['captcha_bell']
			),
			array(
				'url' => 'bin',
				'title' => PureChat::$lang['captcha_bin']
			),
			array(
				'url' => 'bin-metal',
				'title' => PureChat::$lang['captcha_bin-metal']
			),
			array(
				'url' => 'blue-document-text',
				'title' => PureChat::$lang['captcha_blue-document-text']
			),
			array(
				'url' => 'blue-folder',
				'title' => PureChat::$lang['captcha_blue-folder']
			),
			array(
				'url' => 'book',
				'title' => PureChat::$lang['captcha_book']
			),
			array(
				'url' => 'book-brown',
				'title' => PureChat::$lang['captcha_book-brown']
			),
			array(
				'url' => 'bookmark',
				'title' => PureChat::$lang['captcha_bookmark']
			),
			array(
				'url' => 'box',
				'title' => PureChat::$lang['captcha_box']
			),
			array(
				'url' => 'box-label',
				'title' => PureChat::$lang['captcha_box-label']
			),
			array(
				'url' => 'briefcase',
				'title' => PureChat::$lang['captcha_briefcase']
			),
			array(
				'url' => 'calculator',
				'title' => PureChat::$lang['captcha_calculator']
			),
			array(
				'url' => 'calendar-day',
				'title' => PureChat::$lang['captcha_calendar-day']
			),
			array(
				'url' => 'calendar-month',
				'title' => PureChat::$lang['captcha_calendar-month']
			),
			array(
				'url' => 'camera',
				'title' => PureChat::$lang['captcha_camera']
			),
			array(
				'url' => 'camera-lens',
				'title' => PureChat::$lang['captcha_camera-lens']
			),
			array(
				'url' => 'card-address',
				'title' => PureChat::$lang['captcha_card-address']
			),
			array(
				'url' => 'clock',
				'title' => PureChat::$lang['captcha_clock']
			),
			array(
				'url' => 'color',
				'title' => PureChat::$lang['captcha_color']
			),
			array(
				'url' => 'color-swatch',
				'title' => PureChat::$lang['captcha_color-swatch']
			),
			array(
				'url' => 'credit-card',
				'title' => PureChat::$lang['captcha_credit-card']
			),
			array(
				'url' => 'credit-card-green',
				'title' => PureChat::$lang['captcha_credit-card-green']
			),
			array(
				'url' => 'cross',
				'title' => PureChat::$lang['captcha_cross']
			),
			array(
				'url' => 'database',
				'title' => PureChat::$lang['captcha_database']
			),
			array(
				'url' => 'disc',
				'title' => PureChat::$lang['captcha_disc']
			),
			array(
				'url' => 'disc-blue',
				'title' => PureChat::$lang['captcha_disc-blue']
			),
			array(
				'url' => 'disk',
				'title' => PureChat::$lang['captcha_disk']
			),
			array(
				'url' => 'disk-black',
				'title' => PureChat::$lang['captcha_disk-black']
			),
			array(
				'url' => 'document-text',
				'title' => PureChat::$lang['captcha_document-text']
			),
			array(
				'url' => 'drive',
				'title' => PureChat::$lang['captcha_drive']
			),
			array(
				'url' => 'edit',
				'title' => PureChat::$lang['captcha_edit']
			),
			array(
				'url' => 'equalizer',
				'title' => PureChat::$lang['captcha_equalizer']
			),
			array(
				'url' => 'eraser',
				'title' => PureChat::$lang['captcha_eraser']
			),
			array(
				'url' => 'exclamation',
				'title' => PureChat::$lang['captcha_exclamation']
			),
			array(
				'url' => 'feed',
				'title' => PureChat::$lang['captcha_feed']
			),
			array(
				'url' => 'film',
				'title' => PureChat::$lang['captcha_film']
			),
			array(
				'url' => 'fire',
				'title' => PureChat::$lang['captcha_fire']
			),
			array(
				'url' => 'folder',
				'title' => PureChat::$lang['captcha_folder']
			),
			array(
				'url' => 'globe-green',
				'title' => PureChat::$lang['captcha_globe-green']
			),
			array(
				'url' => 'home',
				'title' => PureChat::$lang['captcha_home']
			),
			array(
				'url' => 'image','title'  => 'Image'
			),
			array(
				'url' => 'image-sunset',
				'title' => PureChat::$lang['captcha_image-sunset']
			),
			array(
				'url' => 'inbox',
				'title' => PureChat::$lang['captcha_inbox']
			),
			array(
				'url' => 'information',
				'title' => PureChat::$lang['captcha_information']
			),
			array(
				'url' => 'jar',
				'title' => PureChat::$lang['captcha_jar']
			),
			array(
				'url' => 'jar-label',
				'title' => PureChat::$lang['captcha_jar-label']
			),
			array(
				'url' => 'keyboard',
				'title' => PureChat::$lang['captcha_keyboard']
			),
			array(
				'url' => 'layer',
				'title' => PureChat::$lang['captcha_layer']
			),
			array(
				'url' => 'lifebuoy',
				'title' => PureChat::$lang['captcha_lifebuoy']
			),
			array(
				'url' => 'light-bulb',
				'title' => PureChat::$lang['captcha_light-bulb']
			),
			array(
				'url' => 'light-bulb-off',
				'title' => PureChat::$lang['captcha_light-bulb-off']
			),
			array(
				'url' => 'magnet',
				'title' => PureChat::$lang['captcha_magnet']
			),
			array(
				'url' => 'magnifier',
				'title' => PureChat::$lang['captcha_magnifier']
			),
			array(
				'url' => 'mail',
				'title' => PureChat::$lang['captcha_mail']
			),
			array(
				'url' => 'mail-open',
				'title' => PureChat::$lang['captcha_mail-open']
			),
			array(
				'url' => 'map',
				'title' => PureChat::$lang['captcha_map']
			),
			array(
				'url' => 'marker',
				'title' => PureChat::$lang['captcha_marker']
			),
			array(
				'url' => 'media-player',
				'title' => PureChat::$lang['captcha_media-player']
			),
			array(
				'url' => 'media-player-black',
				'title' => PureChat::$lang['captcha_media-player-black']
			),
			array(
				'url' => 'megaphone',
				'title' => PureChat::$lang['captcha_megaphone']
			),
			array(
				'url' => 'microphone',
				'title' => PureChat::$lang['captcha_microphone']
			),
			array(
				'url' => 'minus',
				'title' => PureChat::$lang['captcha_minus']
			),
			array(
				'url' => 'mobile-phone',
				'title' => PureChat::$lang['captcha_mobile-phone']
			),
			array(
				'url' => 'monitor',
				'title' => PureChat::$lang['captcha_monitor']
			),
			array(
				'url' => 'newspaper',
				'title' => PureChat::$lang['captcha_newspaper']
			),
			array(
				'url' => 'notebook',
				'title' => PureChat::$lang['captcha_notebook']
			),
			array(
				'url' => 'paper-bag',
				'title' => PureChat::$lang['captcha_paper-bag']
			),
			array(
				'url' => 'paper-bag-label',
				'title' => PureChat::$lang['captcha_paper-bag-label']
			),
			array(
				'url' => 'pencil',
				'title' => PureChat::$lang['captcha_pencil']
			),
			array(
				'url' => 'photo-album',
				'title' => PureChat::$lang['captcha_photo-album']
			),
			array(
				'url' => 'photo-album-blue',
				'title' => PureChat::$lang['captcha_photo-album-blue']
			),
			array(
				'url' => 'plus',
				'title' => PureChat::$lang['captcha_plus']
			),
			array(
				'url' => 'point',
				'title' => PureChat::$lang['captcha_point']
			),
			array(
				'url' => 'printer',
				'title' => PureChat::$lang['captcha_printer']
			),
			array(
				'url' => 'receipt-text',
				'title' => PureChat::$lang['captcha_receipt-text']
			),
			array(
				'url' => 'ruler',
				'title' => PureChat::$lang['captcha_ruler']
			),
			array(
				'url' => 'scissors',
				'title' => PureChat::$lang['captcha_scissors']
			),
			array(
				'url' => 'scissors-blue',
				'title' => PureChat::$lang['captcha_scissors-blue']
			),
			array(
				'url' => 'server',
				'title' => PureChat::$lang['captcha_server']
			),
			array(
				'url' => 'service-bell',
				'title' => PureChat::$lang['captcha_service-bell']
			),
			array(
				'url' => 'smiley',
				'title' => PureChat::$lang['captcha_smiley']
			),
			array(
				'url' => 'smiley-lol',
				'title' => PureChat::$lang['captcha_smiley-lol']
			),
			array(
				'url' => 'soap',
				'title' => PureChat::$lang['captcha_soap']
			),
			array(
				'url' => 'socket',
				'title' => PureChat::$lang['captcha_socket']
			),
			array(
				'url' => 'sofa',
				'title' => PureChat::$lang['captcha_sofa']
			),
			array(
				'url' => 'sort',
				'title' => PureChat::$lang['captcha_sort']
			),
			array(
				'url' => 'stamp',
				'title' => PureChat::$lang['captcha_stamp']
			),
			array(
				'url' => 'star',
				'title' => PureChat::$lang['captcha_star']
			),
			array(
				'url' => 'star-empty',
				'title' => PureChat::$lang['captcha_star-empty']
			),
			array(
				'url' => 'sticky-note',
				'title' => PureChat::$lang['captcha_sticky-note']
			),
			array(
				'url' => 'store',
				'title' => PureChat::$lang['captcha_store']
			),
			array(
				'url' => 'store-label',
				'title' => PureChat::$lang['captcha_store-label']
			),
			array(
				'url' => 'switch',
				'title' => PureChat::$lang['captcha_switch']
			),
			array(
				'url' => 'system-monitor',
				'title' => PureChat::$lang['captcha_system-monitor']
			),
			array(
				'url' => 'table',
				'title' => PureChat::$lang['captcha_table']
			),
			array(
				'url' => 'tag',
				'title' => PureChat::$lang['captcha_tag']
			),
			array(
				'url' => 'tag-label',
				'title' => PureChat::$lang['captcha_tag-label']
			),
			array(
				'url' => 'target',
				'title' => PureChat::$lang['captcha_target']
			),
			array(
				'url' => 'television',
				'title' => PureChat::$lang['captcha_television']
			),
			array(
				'url' => 'terminal',
				'title' => PureChat::$lang['captcha_terminal']
			),
			array(
				'url' => 'thumb',
				'title' => PureChat::$lang['captcha_thumb']
			),
			array(
				'url' => 'thumb-up',
				'title' => PureChat::$lang['captcha_thumb-up']
			),
			array(
				'url' => 'tick',
				'title' => PureChat::$lang['captcha_tick']
			),
			array(
				'url' => 'ticket',
				'title' => PureChat::$lang['captcha_ticket']
			),
			array(
				'url' => 'universal',
				'title' => PureChat::$lang['captcha_universal']
			),
			array(
				'url' => 'user',
				'title' => PureChat::$lang['captcha_user']
			),
			array(
				'url' => 'user-business',
				'title' => PureChat::$lang['captcha_user-business']
			),
			array(
				'url' => 'user-business-boss',
				'title' => PureChat::$lang['captcha_user-business-boss']
			),
			array(
				'url' => 'user-female',
				'title' => PureChat::$lang['captcha_user-female']
			),
			array(
				'url' => 'vise',
				'title' => PureChat::$lang['captcha_vise']
			),
			array(
				'url' => 'wall',
				'title' => PureChat::$lang['captcha_wall']
			),
			array(
				'url' => 'wand',
				'title' => PureChat::$lang['captcha_wand']
			),
			array(
				'url' => 'wand-hat',
				'title' => PureChat::$lang['captcha_wand-hat']
			),
			array(
				'url' => 'water',
				'title' => PureChat::$lang['captcha_water']
			),
			array(
				'url' => 'webcam',
				'title' => PureChat::$lang['captcha_webcam']
			),
			array(
				'url' => 'wooden-box',
				'title' => PureChat::$lang['captcha_wooden-box']
			),
			array(
				'url' => 'wooden-box-label',
				'title' => PureChat::$lang['captcha_wooden-box-label']
			),
			array(
				'url' => 'yin-yang',
				'title' => PureChat::$lang['captcha_yin-yang']
			)
		);

		// Generate the first row, randomly.
		$this->random_captcha = array();
		$this->used_keys = array();
		for ($count = 1; $count <= $this->boxes; $count++)
		{
			do {
				$key = mt_rand(0, ((count($this->images) - 1)));
			} while (array_key_exists($key, $this->used_keys));
			$this->random_captcha[] = $this->images[$key];
			$this->used_keys[$key] = true;
		}

		// Start to figure out the second row, which is scrambled.
		$usable_captcha_images = $this->random_captcha;
		$last_captcha_image = (count($usable_captcha_images) - 1);
		for ($count = 1; $count <= $this->boxes; $count++)
		{
			if (count($usable_captcha_images) > 1)
			{
				do {
					$key = mt_rand(0, ($last_captcha_image));
				} while (!array_key_exists($key, $usable_captcha_images));
			}
			else
			{
				$key = 0;
				while (!array_key_exists($key, $usable_captcha_images))
					$key++;
			}
			$this->second_row[$key] = $this->random_captcha[$key];
			unset($usable_captcha_images[$key]);
		}
		foreach (array_keys($this->second_row) as $key => $value)
		{
			if ($key === $value)
				$identical[] = true;
		}
		if (isset($identical) && count($identical) == $this->boxes)
			$this->second_row = array_reverse($this->second_row, true);

		// CSS
		PureChat::$globals['import_scripts'] .= '
		<link rel="stylesheet" type="text/css" href="' . $this->currentthemeurl . '/css/captcha.css" />
		<link rel="stlyesheet" type="text/css" href="' . $this->currentthemeurl . '/css/jquery-ui.min.css" />';

		// Script files.
		PureChat::$globals['import_scripts'] .= '
		<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/jquery-ui.min.js"></script>
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/captcha.ui.js"></script>';

		PureChat::$globals['script_vars'] .= '
			captcha = {
				correct: 0,
				boxes: ' . $this->boxes . ',
				use_labels: ' . (!empty($this->use_labels) ? 1 : 0) . ',
				full_width: ' . $this->boxes * 15 . ',
				total_width: ' . $this->total_width . ',
				submit_id: \'' . $this->submit_id . '\',
				form_id: \'' . $this->form_id . '\',
				form_action: \'' . $this->form_action . '\',
				submit_id: \'' . $this->submit_id . '\',
				random: ' . json_encode($this->random_captcha) . ',
				second_row: ' . json_encode($this->second_row) . ',
			};
		';

		if ($this->total_width < 100)
		{
			PureChat::$globals['import_scripts'] .= '
				<style type="text/css">
					#captcha .first_column, #captcha .second_column {
						margin-left: ' . (((100 - $this->total_width) / 2) + 5) . '%;
					}
				</style>
			';
		}

		// Throw them in the scope.
		PureChat::$globals['captcha'] = array(
			'random' => $this->random_captcha,
			'second_row' => $this->second_row,
			'use_labels' => $this->use_labels,
			'box_count' => $this->boxes,
			'total_width' => $this->total_width
		);

		require_once($this->currentthemedir . '/Captcha.template.php');
		$this->template = new CaptchaTemplate;

	}
}