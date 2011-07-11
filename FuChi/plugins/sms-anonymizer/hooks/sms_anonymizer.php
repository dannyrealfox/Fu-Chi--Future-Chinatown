<?php defined('SYSPATH') or die('No direct script access.');
/**
 * SMS Anonymizer Hook - Load All Events
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

class sms_anonymizer {
	
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Hook into incoming sms event
		Event::add('ushahidi_action.message_sms_add', array($this, '_anonymize'));
	}
	
	public function _anonymize()
	{
		$anon = text::random($type = 'alnum', $length = 50);
		$sms = Event::$data;
		$sms->message_from = $anon;
		$sms->save();
		
		$reporter = $sms->reporter;
		$reporter->service_account = $anon;
		$reporter->save();
	}
}
new sms_anonymizer;