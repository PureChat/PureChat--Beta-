<?php
/**
 * PureChat (PC)
 *
 * @file ~./Includes/controller.php
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

class PureChat
{
	// Directories.
	public $root;
	protected $includesdir;
	protected $sourcesdir;
	protected $actionsdir;
	protected $themesdir;
	protected $languagesdir;

	// Objects.
	protected $db;

	// Global Variables.
	protected $script;
	protected $rooturl;
	protected $themesurl;
	protected $currenttheme;
	protected $currentthemeurl;
    protected $smileysurl;
	protected $currentthemedir;
    public $parse;
	// Static Protected Properties
	static protected $globals = array('ajax' => false);
	static protected $lang = array();

	// Actions, Pages and Sources (theme names are stored in the database).
	private $actions = array();
	private $sources = array();
	private $pages = array();

	// Reusable, universal sub methods.
	static protected $universal;

	/**
	 * Define the paths and variables to be used throughout PureChat code.
	 *
	 * This constructor method can be called from any sub-class constructor,
	 * so there's no longer a need to globalize variables. Only variable definitions
	 * should be performed in this method, it is not used for calling other functions.
	 * However there may be a need to call some database queries, that's fine.
	 *
	 * No paramters are taken.
	 */
	public function __construct()
	{
		// Directory paths.
		$this->root = dirname($_SERVER['SCRIPT_FILENAME']);
		$this->actionsdir = $this->root . '/Actions';
		$this->includesdir = $this->root . '/Includes';
		$this->sourcesdir = $this->root . '/Sources';
		$this->themesdir = $this->root . '/Themes';
		$this->languagesdir = $this->root . '/Languages';

		// Require the junk that makes database interaction possible.
		require($this->includesdir . '/db_info.php');
		require_once($this->includesdir . '/db_object.php');

		$this->db = new DatabaseController($db_info['host'], $db_info['name'], $db_info['username'], $db_info['password']);

		if ($this->db->error && file_exists($this->root . '/install.php'))
			exit ('Please run the <a href="install.php">installer</a> file to configure the database.');

		else if (!$this->db->error && file_exists($this->root . '/install.php'))
			exit ('Please <strong>delete</strong> the installer script before continuing.');

		if ($this->db->error)
			exit ('Datbase connection error.<br /><br />');

		// Actions = array($_GET['action'] => 'file_name.action.php');
		$this->actions = array(
			'main' => 'main.action.php',
			'user' => 'user.action.php',
			'chat_controller' => 'chat_controller.action.php',
			'online_list' => 'online_list.action.php',
			'profile_controller' => 'profile_controller.action.php',
			'js_vars' => 'js_vars.action.php',
			'manage_bans' => 'manage_bans.action.php'
		);

		// Sources = array($_GET['page'] => 'file_name.source.php');
		$this->sources = array(
			'admin' => array(
				'file' => 'admin.source.php',
				'initial_method' => 'init'
			),
			'js_vars' => array(
				'file' => 'script2js.source.php',
				'initial_method' => 'init'
			)
		);

		// Pages (template files) array($_GET['page'] => 'file_name.template.php');
		$this->pages = array(
			'admin' => 'Admin',
			'chat' => 'Chat',
			'login_register' => 'Login_Register'
		);

		// What theme are we using? I dunno, but we'll find out in a second.
		$sql = '
			SELECT value
			FROM pc_settings
			WHERE setting = \'theme\'';
		$result = $this->db->get_one($sql);
		$this->currenttheme = $result['value'];

		// Global arrays and variables.
		$this->script = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
		$this->rooturl = str_replace('index.php', '', 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);

		// Theme urls and directory.
		$this->themesurl = $this->rooturl . 'Themes';
		$this->currentthemeurl = $this->themesurl . '/' . $this->currenttheme;
        $this->smiliesurl = $this->currentthemeurl . '/images/smilies'; // Necessary?
		$this->currentthemedir = $this->themesdir . '/' . $this->currenttheme;

	}

	/**
	 * The main method called from index.php.
	 *
	 * This method is called from index.php to initiate the PureChat framework.
	 * It executes immediately after the __construct method, so it's safe to use
	 * the variables defined in the constructor ($this->).
	 *
	 * Again, no paramters are taken.
	 */
	public function init()
	{

		PureChat::$globals['template'] = array();
		PureChat::$globals['template']['files'] = array();
		PureChat::$globals['template']['methods'] = array();
		PureChat::$globals['script_vars'] = '';
 		PureChat::$globals['import_scripts'] = '';
 		PureChat::$globals['groups'] = array();

		// Load the reusable methods.
		require_once($this->includesdir . '/universal_functions.php');
		PureChat::$universal = new Universal;

		// Load the main language file.
		call_user_func(array(PureChat::$universal, 'load_language'), 'main');

		// Call some "self" methods.
		$self_methods = array('load_user', 'load_sources', 'do_action', 'load_UI');
		foreach ($self_methods as $key => $value)
			call_user_func(array('self', $value));

	}

	/**
	 * Peform some coolio action, such as loging in or out.
	 *
	 * This function basically just checks the validity of the $_GET['action']
	 * varable against the object's action array. If the action is valid, it loads
	 * the file needed, instantiates a new object and calls the initializing method.
	 *
	 * Also, if the optional $_GET['ajax_action'] is set, it throws a switch that
	 * stops the template layers from loading. This is essential for recieving
	 * Ajax responses.
	 *
	 * No parameters. :P
	 */
	private function do_action()
	{
		if (isset($_REQUEST['ajax_connection']))
			PureChat::$globals['ajax'] = true;

		$file = $this->actionsdir . '/' . $this->actions['main'];
		require_once($file);
		$main_action = new MainAction;
		$main_action->init();

		if (!empty($_GET['action']) && array_key_exists($_GET['action'], $this->actions))
		{
			$file = $this->actionsdir . '/' . $this->actions[$_GET['action']];
			require_once($file);
			$action = new Action;
			$action->init();
		}
	}

	/**
	 * Loads the user infomation.
	 *
	 * There's not much to say about this method. It just opens the file that
	 * controls user information, creates a new local object and calls the
	 * initializing method.
	 *
	 * @calledfile: ~/Sources/user.source.php
	 */
	private function load_user()
	{
		require_once($this->sourcesdir . '/user.source.php');
		$user = new User;
		
		$user->load_user_info();
		$user->get_groups();
	}
	
	/**
	 * Seems to me we might need to check if any sources need to be loaded....
	 *
	 * This method calls the main source file with every page load, so we never
	 * go without the global information we need. It also checks to see if any
	 * further sources are needed for the page we're viewing.
	 *
	 * @calledfile: ~/Sources/main.source.php
	 */
	private function load_sources()
	{
		require_once($this->sourcesdir . '/main.source.php');
		$source_main = new SourceMain;
		$source_main->init();

		if (!PureChat::$globals['user']['logged'])
		{
			require_once($this->sourcesdir . '/login_register.source.php');
			$guest_source = new GuestSource;
			$guest_source->init();

			// Don't need the rest of this stuff if we're a guest.
			return true;
		}

		// Load a source file for a page or action.
		if (!empty($_GET['page']) && array_key_exists($_GET['page'], $this->sources))
			$page = $this->sources[$_GET['page']];
		else if (!empty($_GET['action']) && array_key_exists($_GET['action'], $this->sources))
			$page = $this->sources[$_GET['action']];
		
		if (isset($page))
		{
			$file = $this->sourcesdir . '/' . $page['file'];
			require_once($file);
			
			$source = new Source;
			if (empty($page['initial_method']) || !method_exists($source, $page['initial_method']))
				$method = 'init';
			else
				$method = $page['initial_method'];
			$source->$method();
		}
	}

	/**
	 * As the name suggests, this method loads the UI.
	 *
	 * This method does quite a few things to load up the user interface.
	 * - Tests to see if the theme name from the databse actually exists.
	 * - Requires the theme files.
	 * - Instantiates the template object.
	 * - Includes the required page content, and makes a new page object.
	 * - Puts everything together in the right order.
	 */
	private function load_UI()
	{
		if (PureChat::$globals['ajax'])
			return false;

		$directory = $this->currentthemedir;
		if (is_dir($directory) && file_exists($directory . '/main.template.php'))
			require_once($directory . '/main.template.php');
		else
			require_once($directory . '/default/main.template.php');

		$wrapper = new MainTemplate;

		$wrapper->template_top();
		foreach (PureChat::$globals['template']['methods'] as $method)
			call_user_func(array($method['object'], $method['method']));
		$wrapper->template_bottom();
	}
}
