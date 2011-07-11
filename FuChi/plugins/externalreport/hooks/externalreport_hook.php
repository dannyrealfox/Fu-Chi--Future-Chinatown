<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Flickrwijit Hook - Load All Events
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

class externalreport {

	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		$this->db = Database::instance();
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}

	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Add a Sub-Nav Link
		Event::add('ushahidi_action.nav_admin_reports', array($this, 
			'_reports_link'));
		
	}
	
	public function _reports_link() 
	{
		$this_sub_page = Event::$data;
		
		echo ($this_sub_page == "externalreport") ? Kohana::lang('externalreport.externalreports_menu') : 
			"<a href=\"".url::site()."admin/reports/externalreports/\>".Kohana::lang('externalreport.externalreports_menu')."</a>";	
	}
	
	
}

new externalreport;

