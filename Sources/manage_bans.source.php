<?php
class ManageBanSource extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function init()
	{
		self::$universal->load_language('manage_bans');
	}
	
	public function add_ban()
	{
		// This will load the information for editing an existing ban when I put that in.
	}
}
