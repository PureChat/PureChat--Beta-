<?php
class Source extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	public function init()
	{
		if (!self::$globals['user']['is_admin'])
			self::$universal->redirect();
			
		$this->load_info();
		$this->load_sub_source();
		$this->load_admin_template();
	}
	
	private function load_sub_source()
	{
		if (empty($_GET['sp']))
			return false;
		
		$sub_source;
		switch ($_GET['sp'])
		{
			case 'add_ban':
				self::$universal->load_source(
					'manage_bans', // File Name.
					'ManageBanSource', // Class Name
					$sub_source, // Class Reference Variable.
					'init' // Auto-Called Method.
				);
				break;
		}
	}	
	
	private function load_admin_template()
	{
		$subs = array('settings', 'groups', 'add_ban');

		$files = array(
			'admin_template' => array(
				'file' => 'Admin',
				'class' => 'AdminTemplate'
			),
			'admin_groups' => array(
				'file' => 'Admin_Groups',
				'class' => 'AdminGroups'
			),
			'admin_settings' => array(
				'file' => 'Admin_Settings',
				'class' => 'AdminSettings'
			),
			'ban_manager' => array(
				'file' => 'Ban_Manager',
				'class' => 'BanManager'
			)
		);
		$methods = array(
			'top' => array(
				'class_key' => 'admin_template', // The index of the class array.
				'method' => 'admin_head'
			),
			'home' => array(
				'class_key' => 'admin_template',
				'method' => 'home_content',
				'condition' => empty($_GET['sp']) || !in_array($_GET['sp'], $subs)? true : false
			),
			'groups' => array(
				'class_key' => 'admin_groups',
				'method' => 'content',
				'condition' => !empty($_GET['sp']) && $_GET['sp'] == 'groups' ? true : false
			),
			'settings' => array(
				'class_key' => 'admin_settings',
				'method' => 'content',
				'condition' => !empty($_GET['sp']) && $_GET['sp'] == 'settings' ? true : false
			),
			'ban_manager' => array(
				'class_key' => 'ban_manager',
				'method' => 'add_ban',
				'condition' => !empty($_GET['sp']) && $_GET['sp'] == 'add_ban' ? true : false
			),
			'bottom' => array(
				'class_key' => 'admin_template',
				'method' => 'admin_footer'
			)
		);

		self::$universal->load_template($files, $methods);
	}

	protected function load_info()
	{
		// This is very subject to change once we get the groups in place.
		// We'll use a query once that happens.
		// In the mean time, static FTW.

		self::$globals['admin']['administrators'] = array('The Craw', 'Matthew K.');
	}
}
