<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
	
	/**
	 * Doctrine Entity manage
	 * 
	 * @var object
	 */
	public $em;
	
	/**
	 * Current user
	 * 
	 * @var object
	 */
	protected $current_user = false;
	
	/**
	 * Current user permissions
	 *
	 * @var array 
	 */
	protected $permissions = array();
	
	/**
	 * The name of the module that this controller instance actually belongs to.
	 *
	 * @var string 
	 */
	protected $module;
	
	/**
	 * The name of the controller class for the current class instance.
	 *
	 * @var string
	 */
	protected $controller;
	
	/**
	 * The name of the method for the current request.
	 *
	 * @var string 
	 */
	protected $method;
	
	protected $tax = false;
	
	public function __construct() {
		parent::__construct();
		
		// load site config file
		// Todo: move to database
		$this->config->load('config_site', TRUE);
		
		// Instantiate Doctrine's Entity manage so we don't have to everytime we want to use Doctrine
		$this->em = $this->doctrine->em;
		
		// Load the Memcached
		//$this->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		
		$this->load->model('user/auth_m', 'auth');
		
		// Current user object
		$this->current_user = $this->auth->current_user();
		
		if ($this->current_user) {
			$temp_permissions = $this->current_user->getRole()->getPermissions();
			
			foreach ($temp_permissions as $permission) {
				$this->permissions[] =  $permission->getModule() . '/' . $permission->getName();
			}
		}
		
		if (!$this->tax) {
			$this->tax = $this->em->getRepository('setting\models\Setting')->findOneByName('tax')->getValue();	
		}
		
		$this->module = $this->router->fetch_module();
		$this->controller = $this->router->fetch_class();
		$this->method = $this->router->fetch_method();
	}
}