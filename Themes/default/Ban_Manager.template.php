<?php
class BanManager extends PureChat
{
	public function __construct()
	{
		parent::__construct();
		
		self::$globals['import_scripts'] .= '
			<script type="text/javascript">
				aom_params = {
					form: \'add_ban\',
					ac: {
						ban_username: {
							url: \'' . $this->script . '?action=manage_bans&perform=auto_complete&field=ban_username\',
							id: \'ban_user\'
						}
					},
					submit_button: \'add_ban_button\'
				}
			</script>';
			
		self::$globals['import_scripts'] .= '
			<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/aom.js">
				// Handles the work of the admin interface, mainly AJAX.
			</script>
			<script type="text/javascript" src="' . $this->themesurl . '/default/scripts/aom.ui.js">
				// Traversing and manipulating the DOM.
			</script>';
	}
	
	public function add_ban()
	{		
		// Titiles and junk.
		echo '
					<h3 class="page_title">', self::$lang['add_ban_header'], '</h3>
					<h5 class="page_description">', self::$lang['add_ban_description'], '</h5>';

		// Form.
		echo'
					<form id="add_ban" method="post" action="', $this->script, '?action=manage_bans&perform=submit">

						<!-- Display Name. -->
						<div class="form_information">
							<input class="form_input" type="text" name="ban_user" id="ban_user" autocomplete="off" />
							<label class="form_label" for="ban_username">', self::$lang['display_name'], '</label>
							<span class="form_desc">', self::$lang['add_ban_display_name_desc'], '</span>
							<hr />
						</div>

						<!-- Email. -->
						<div class="form_information">
							<input class="form_checkbox" type="checkbox" name="ban_email" id="ban_email" />
							<label class="form_label" for="ban_email">', self::$lang['email'], '</label>
							<span class="form_desc">', self::$lang['add_ban_email_desc'], '</span>
							<hr />
						</div>

						<!-- Hostname. -->
						<div class="form_information">
							<input class="form_checkbox" type="checkbox" name="ban_hostname" id="ban_hostname" />
							<label class="form_label" for="ban_hostname">', self::$lang['ban_hostname'], '</label>
							<span class="form_desc">', self::$lang['add_ban_hostname_desc'], '</span>
							<hr />
						</div>

						<!-- IP Address. -->
						<div class="form_information">
							<input class="form_checkbox" type="checkbox" name="ban_ip" id="ban_ip" />
							<label class="form_label" for="ban_ip">', self::$lang['ban_ip'], '</label>
							<span class="form_desc">', self::$lang['add_ban_ip_desc'], '</span>
							<hr />
						</div>

						<div class="form_information">
							<input class="form_button" id="add_ban_button" type="submit" value="', self::$lang['add_ban'], '" />
						</div>
					</form>';
	}
}
