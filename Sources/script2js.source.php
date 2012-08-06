<?php
class Source extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	public function init()
	{
		header('Content-type: text/javascript');
	}
}
