<?php
/**
 * PureChat (PC)
 *
 * @file ~./Themes/default/Admin.template.php
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

class AdminTemplate extends PureChat
{
	private $methods = array();

	public function __construct()
	{
		parent::__construct();

		$this->methods = array(
			'main' => 'main_template',
			'groups' => 'groups_template',
			'settings' => 'settings_template'
		);

		self::$globals['import_scripts'] .= '
		<link rel="stylesheet" type="text/css" href="' . $this->currentthemeurl . '/css/admin.css" />
		<script type="text/javascript" src="' . $this->currentthemeurl . '/scripts/admin.js"></script>';
	}

	public function admin_head()
	{
		echo '
			<div class="content">
				<ul class="tabs">
					<li><a href="', $this->script, '">', self::$lang['page_home'], '</a></li>
					<li><a href="', $this->script, '?page=admin">', self::$lang['page_admin'], '</a></li>
					<li><a href="', $this->script, '?page=admin&sp=groups">', self::$lang['page_groups'], '</a></li>
					<li><a href="', $this->script, '?page=admin&sp=settings">', self::$lang['page_settings'], '</a>
				</ul>';
	}

	public function admin_footer()
	{
		echo '
			</div>';
	}

	public function home_content()
	{
		// Welcome box.
		echo '
				<div class="admin_section">
					<div class="header_cap2">', self::$lang['velkomen_til_admin'], '</div>
					<div class="lower_content_container2">
						', self::$lang['velkomen_text'], '
					</div>
				</div>';

		// PureChat news feed.
		echo '
				<div class="admin_section">
					<div class="header_cap2">', self::$lang['news_feed'], '</div>
					<div class="lower_content_container2">
						We can pull a news file off our website server or something.
						However we need to build it well so it doens\'t crash the site or something
						when the admin isn\'t connected to the internet, i.e. localhost.
						<br /><br />
					</div>
				</div>
				<br class="clear" />';

		// Information box.
		echo '
				<div class="admin_section">
					<div class="header_cap">', self::$lang['software_details'], '</div>
					<div class="lower_content_container2">
						', self::$lang['admins'], ': ';

		$loop_total = count(self::$globals['admin']['administrators']) - 1;
		for ($i = 0; $i <= $loop_total; $i++)
		{
			echo
						self::$globals['admin']['administrators'][$i], $i != $loop_total ? ', ' : '<br />';
		}

		echo '
						<hr />
						', self::$lang['version'], ': ', PC_VERSION, '
						<hr />
						', self::$lang['latest_version'], ':
						<hr />
						', self::$lang['copyright'], ': ', PC_COPY, '
					</div>
				</div>';


		// Admin Notes
		echo '
				<div class="admin_section">
					<div class="header_cap">', self::$lang['admin_notes'], '</div>
					<div class="lower_content_container2">
						<p>
							Matt, you can develope this if you want.
							If not, I don\'t mind, but I think it would be a cool feature.
							Especially on active chats where OPs need to stay in contact.
						</p>
						<p>
							This doesn\'t need to be at all fancy at this point,
							because we should be concentrating our efforts on more important things.
							Just standard [hr] separated message sections with an input at the bottom will do.
							It doesn\'t even need to be Ajax updated.
						</p>
					</div>
				</div>';
	}
}
