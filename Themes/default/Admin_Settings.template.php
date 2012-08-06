<?php
class AdminSettings extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	public function content()
	{
		echo '
			<div class="header_cap">General Settings</div>
			<div class="lower_content_container">
				<p>Something</p>
			</div>';
	}
}