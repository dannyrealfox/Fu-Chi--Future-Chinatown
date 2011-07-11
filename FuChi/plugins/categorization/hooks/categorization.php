<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Facebook Social Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class categorization {

	/**
	 * Registers the main event add method
	 */
	public $from_number = "";
	
	public function __construct()
	{
		$this->db = Database::instance();
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
		Event::add('system.pre_controller', array($this, 'add_boundary'));
		Event::add('system.pre_controller', array($this, 'add_codes'));
		
	}

	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Add a Sub-Nav Link
		Event::add('ushahidi_filter.nav_admin_monitors', array($this, '_monitor_link'));

		// Only add the events if we are on that controller
		if (Router::$controller == 'admin')
		{
			plugin::add_stylesheet('categorization/views/css/main');
		
		}else if(Router::$controller == 'frontlinesms' AND Router::$method == 'index') {
			
			Event::add('ushahidi_action.message_sender',array($this,'_message_from'));
			//Modify the message description from sms
			Event::add('ushahidi_filter.message_description', array($this,'_modify_message_description'));
			
		} else if (strripos(Router::$current_uri, "admin/reports/edit") !== false ) {
			
			Event::add('ushahidi_filter.location_name', array($this,'_append_location'));
			Event::add('ushahidi_filter.location_find', array($this,'_append_location_find'));
		
		} else if( strripos(Router::$current_uri, "admin/messages") !== false ) {
			Event::add('ushahidi_action.message_from',array($this,'_message_from'));
		}
	}
	
	/**
	 * Add monitor codes to admin.
	 */
	public function add_codes()
	{
		// Add a Sub-Nav Link
		Event::add('ushahidi_action.nav_admin_manage', array($this, '_monitor_link_codes'));
		
		// Only add the events if we are on that controller
		if (Router::$controller == 'admin')
		{
			plugin::add_stylesheet('categorization/views/css/main');
		}
	}
	
	/**
	 * Add admin boundary
	 */
	public function add_boundary()
	{
		// Add a Sub-Nav Link
		Event::add('ushahidi_action.nav_admin_manage', array($this, '_monitor_link_boundary'));

		// Only add the events if we are on that controller
		if (Router::$controller == 'admin')
		{
			plugin::add_stylesheet('categorization/views/css/main');
		}
	}
	

	public function _monitor_link()
	{
		$main_right_tabs = Event::$data;
		Event::$data = array_merge($main_right_tabs, array('categorization'=>'Monitors'));
	}

	public function _monitor_link_boundary()
	{
		$this_sub_page = Event::$data;
		echo  "<a href=\"".url::site()."admin/boundaries\">Admin Boundaries</a>";
	}
	
	public function _monitor_link_codes()
	{
		$this_sub_page = Event::$data;
		echo  "<a href=\"".url::site()."admin/codes\">Form Codes</a>";
	}
	
	
	/**
	 * Modifiy the message description that comes from frontlinesms
	 */
	public function _modify_message_description() {
		$message_description = Event::$data;
		
		if( is_numeric( $message_description ) ) {
		
			$message = $this->_get_description((int)$message_description);
			
			if(!empty($message) ) {	
				if(isset($_SESSION['from_location'])) {
					
					$polling_station = $this->_get_polling_station($_SESSION['from_location']);
					$message_desc = $message . " from $polling_station polling station";
				}else{
					
					$message_desc = $message;
				}
				
			} else {
				
				$message_desc = Kohana::lang('categorization.missing_matching_code');
			} 
			
		} else {
			$message_desc = $message_description;
		}
		
		Event::$data = $message_desc;
		
	}
	
	/**
	 * Append phone numbers that comes monitors to their
	 * TODO rework this wack solution
	 */
	public function _append_location() {
		$location_n = Event::$data;
		if( isset($_SESSION['from_location'] )) {
			$location_n = $this->_get_location($_SESSION['from_location']);
		
			if(empty($location_n)){
				$location_n = "";		
			}
		}
		//unset($_SESSION['from_location']);
		Event::$data = $location_n;
	}
	
	/**
	 * Append phone numbers that comes from monitors to their location.
	 * TODO rework this wack solution
	 */
	public function _append_location_find() {
		$location_find = Event::$data;
		
		if( isset($_SESSION['from_location'] )) {
			$location_find = $this->_get_location($_SESSION['from_location']);
			
			if( empty($location_find) ){
				$location_find = "";		
			}
		}
		unset($_SESSION['from_location']);
		Event::$data = $location_find;
	}

	/**
	 * get from number
	 */
	public function _message_from() {
		$_SESSION['from_location'] = Event::$data;
	}
	
	/**
	 * Get description from code
	 * 
	 * @param - report_code - report code
	 * @param - report_desc - report description
	 */
	public function _get_description($report_code) {
		
		$report_desc = ORM::factory('code')->where('code_id',$report_code)->find();
		
		return $report_desc->code_description;
		
		
	}
	
	/**
	 * Get location from a number
	 * 
	 * @param - loc_number - location number
	 * @param - loc_name - location name 
	 */
	public function _get_location($loc_number) {
		
		$loc_ids = ORM::factory('monitor')->where('phonenumber',$loc_number)->find();
		
		$loc_id = $loc_ids->location_id;
		
		$loc_names = ORM::factory('boundary')->where('id',$loc_id)->find();
		
		$loc_name = $loc_names->boundary_name;
		
		return $loc_name;
	}
	
	/**
	 * Get polling station
	 * 
	 * @param - monitor_number - the monitors number
	 */
	public function _get_polling_station($monitor_number) {
		$polling_station = ORM::factory('monitor')->where('phonenumber',$monitor_number)->find();
		
		return $polling_station->polling_station;
	}
	
}
new categorization;
