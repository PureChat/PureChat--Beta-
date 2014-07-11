<?php
class Action extends PureChat
{
	public function init()
	{
		echo '
var pc_script = \'', $this->script, '\';
var pc_last_message = parseInt(', isset(PureChat::$globals['last_message']) ? PureChat::$globals['last_message'] : 1, ');
var pc_currentthemeurl = \'', $this->currentthemeurl, '\';
var pc_themesurl = \'', $this->themesurl, '/default\';
var pc_imagesurl = pc_themesurl + \'/images\'; 
var pc_username = \'', PureChat::$globals['user']['display_name'], '\';
var pc_user_id = parseInt(', PureChat::$globals['user']['id'], ');
var pc_display_name = "', PureChat::$globals['user']['display_name'], '";
var pc_smilies = ', !empty(PureChat::$globals['smilies']) ? json_encode(PureChat::$globals['smilies']) : 'new Object()', ';
var pc_irc_commands = ', !empty(PureChat::$globals['irc_commands']) ? json_encode(PureChat::$globals['irc_commands']) : 'new Object', ';
var pc_lang = ', json_encode(PureChat::$lang), ';
var status = \'', PureChat::$globals['user']['status'], '\';';
		
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


		if (!empty(PureChat::$globals['script_vars']))
			echo PureChat::$globals['script_vars'];
	}
}