<?php defined('SYSPATH') or die('No direct script access.');
/**
 * SMS Dam Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @module     SMS Dam Hook
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class smsdam {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		$this->frontlinesmskey = '';
		$this->sender = '';
		$this->message = '';
		
		// Hook into routing
		Event::add('system.post_routing', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Only add the events if we are on that controller
		if (Router::$controller == 'frontlinesms')
		{
			switch (Router::$method)
			{
				// Hook into the Report Add/Edit Form in Admin
				case 'index':
					$this->_intercept_vars();
					// Hook into the report_edit (post_SAVE) event
					//Event::add('ushahidi_action.report_edit', array($this, '_report_form_submit'));
					break;
				
				// Hook into the Report view (front end)
				case 'view':
					//Event::add('ushahidi_action.report_extra', array($this, '_report_view'));
					break;
			}
		}
		elseif (Router::$controller == 'messages')
		{
			Event::add('ushahidi_action.nav_admin_messages', array($this, '_admin_nav'));
			
			if(isset(Router::$arguments[0]) AND Router::$arguments[0] == 'smsdam')
			{
				Event::add('ushahidi_action.admin_messages_custom_layout', array($this, '_layout'));
			}
		}
	}
	
	/**
	 * Take the FrontlineSMS variables from the get string and drop it in smsdata table
	 */
	public function _layout()
	{	
		//$service_id = Event::$data;
		$view = View::factory('smsdam_incomming');
		
		$settings = ORM::factory('settings', 1);
		$view->frontlinesmskey = $settings->frontlinesms_key;
		
		$view->render(TRUE);
	}
	
	/**
	 * Take the FrontlineSMS variables from the get string and drop it in smsdata table
	 */
	public function _admin_nav()
	{
		$service_id = Event::$data;
		
		if($service_id == 'smsdam')
		{
			echo "SMS Dam";
		}else{
			echo "<a href=\"" . url::site() . "admin/messages/index/smsdam\">SMS Dam</a>";
		}
	}
	
	/**
	 * Take the FrontlineSMS variables from the get string and drop it in smsdata table
	 */
	public function _intercept_vars()
	{
		// If we want to bypass smsdam for some reason (maybe we're approving a message to go into the system), bypass this step
		
		if(isset($_GET['bypass_smsdam'])) return false;
		
		// Grab SMS data and delete it so the FrontlineSMS controller won't use it
		
		if (isset($_GET['key']))
		{
			$this->frontlinesmskey = $_GET['key'];
			unset($_GET['key']);
		}
		
		if (isset($_GET['s']))
		{
			$this->usid = $_GET['s'];
			unset($_GET['s']);
		}
		
		if (isset($_GET['m']))
		{
			$this->message = $_GET['m'];
			unset($_GET['m']);
		}
		
		// Check Key Validity
		
		$keycheck = ORM::factory('settings', 1)
						->where('frontlinesms_key', $this->frontlinesmskey)
						->find();

		if ($keycheck->loaded == TRUE)
		{
			$message = new Smsdam_Model();
			$message->usid = $this->usid;
			$message->message = $this->message;
			$message->status = 0;
			$message->date_received = date("Y-m-d H:i:s",time());
			$message->save();
		}
		
	}
	
	/**
	 * Add Actionable Form input to the Report Submit Form
	 */
	public function _report_form()
	{	
		//xxx
	}
	
	/**
	 * Handle Form Submission and Save Data
	 */
	public function _report_form_submit()
	{
		// xxxx
	}
	
	/**
	 * Render the Action Taken Information to the Report
	 * on the front end
	 */
	public function _report_view()
	{
		// xxxx
	}
}

new smsdam;