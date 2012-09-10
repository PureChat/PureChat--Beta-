<?php
class Action extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	public function init()
	{
		echo '
var pc_script = \'', $this->script, '\';
var pc_last_message = parseInt(', isset(self::$globals['last_message']) ? self::$globals['last_message'] : 1, ');
var pc_currentthemeurl = \'', $this->currentthemeurl, '\';
var pc_themesurl = \'', $this->themesurl, '/default\';
var pc_username = \'', self::$globals['user']['display_name'], '\';
var pc_user_id = parseInt(', self::$globals['user']['id'], ');
var pc_display_name = "', self::$globals['user']['display_name'], '";
var pc_smilies = ', !empty(self::$globals['smilies']) ? json_encode(self::$globals['smilies']) : 'new Object()', ';
var pc_irc_commands = ', !empty(self::$globals['irc_commands']) ? json_encode(self::$globals['irc_commands']) : 'new Object', ';
var pc_bbc_codes = ', !empty(self::$globals['bbc_list']) ? json_encode(self::$globals['bbc_list']) : 'new Object', ';
var pc_lang = ', json_encode(self::$lang), ';
var status = \'', self::$globals['user']['status'], '\';';
		
		$page = !empty($_GET['page']) ? $_GET['page'] : '';
		$sp = !empty($_GET['sp']) ? $_GET['sp'] : '';

		/*
		* AOM(
		*	(selector) $form,
		*	(string) $action_file,
		*	(string) $action_method,
		*	Object(
		*		submit_button: (selector) $submit_button
		*	)
		* )
		*/
		$vars = array(
			array(
				'var aom = new AdministrationObjectModel(\'#add_ban\', \'ban_control\', \'add\', {submit_button: \'#next1\'});',
				($page == 'admin' && $sp == 'add_ban') ? true : false
			),
		);

		foreach ($vars as $var)
		{
			// Condition is true, output that variable text.
			if ($var[1])
				echo $var[0];
		}


		if (!empty(self::$globals['script_vars']))
			echo self::$globals['script_vars'];
	}
}