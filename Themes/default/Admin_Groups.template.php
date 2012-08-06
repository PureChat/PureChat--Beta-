<?php
class AdminGroups extends PureChat
{
	public function __construct()
	{
		parent::__construct();
	}

	public function content()
	{
		echo '
			<div class="header_cap">
				Chat Groups
				<div class="floatright">
					<a href="', $this->script, '?page=admin&sp=groups;do=add" target="_self" style="font-weight: bold; text-decoration: none; color: #fff;">Add</a>
				</div>
				<br class="clear" />
			</div>
			<div class="lower_content_container">
				<div style="border: 1px solid #a9a9a9;">';
				if (empty(self::$globals['groups']))
					echo self::$lang['groups_fatal'];
				else
				{
					$last_group = array_keys(self::$globals['groups']);
					foreach (self::$globals['groups'] as $key => $value)
					{
						echo '
							<div style="', $key != end($last_group) ? 'border-bottom: 1px solid #a9a9a9; ' : '', 'padding: 10px;">
								#' . $key . ' - ' . $value['name'] . '
								<div class="floatright">
									<a href="', $this->script, '?page=admin&sp=groups&modify=', $key, '" target="_self">Modify</a>
									', $value['type'] != 1 ? ' | <a href="' . $this->script . '?page=admin&sp=groups&remove=' . $key . '" target="_self">Remove</a>' : '', '
								</div>
								<br class="clear" />
							</div>
						';
					}
				}
				echo '</div>
			</div>';
	}
}