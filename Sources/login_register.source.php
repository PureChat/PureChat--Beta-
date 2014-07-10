<?php
class GuestSource extends PureChat
{
	public function init()
	{
		$class = array(
			'login_register' => array(
				'file' => 'Login_Register',
				'class' => 'LoginRegister'
			)
		);
		$method = array(
			'content_layer' => array(
				'class_key' => 'login_register', // The index of the class array.
				'method' => 'content'
			),
		);
		self::$universal->load_template($class, $method);
	}
}