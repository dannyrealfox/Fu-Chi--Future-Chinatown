<?php defined('SYSPATH') or die('No direct script access.');
/**
* SMS Dam HTTP Post Controller
* Gets HTTP Post data from a FrontlineSMS Installation
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   SMS Dam Controller	
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
*/

class Smsdam_Controller extends Controller
{
	function index()
	{
		// xxx
	}
	
	function json()
	{
		// First check that we have a valid key
		$keycheck = ORM::factory('settings', 1)
						->where('frontlinesms_key', $_GET['key'])
						->find();
		if ($keycheck->loaded == TRUE)
		{
			// Gather all results and return json
			$result = ORM::factory(Kohana::config('database.default.table_prefix').'smsdam')
							->orderby('date_received', 'desc')
							->where(array('status !=' => '1'))
							->where(array('status !=' => '2'))
							->find_all();
			$array = array();
			foreach($result as $item)
			{
				$array[$item->id] = array(
					'usid'=>$item->usid,
					'message'=>$item->message,
					'date_received'=>$item->date_received,
					'status'=>$item->status,
					);
			}
			echo json_encode($array);
		}
	}
	
	function messagecount()
	{
		// First check that we have a valid key
		$keycheck = ORM::factory(Kohana::config('database.default.table_prefix').'settings', 1)
						->where('frontlinesms_key', $_GET['key'])
						->find();
		if ($keycheck->loaded == TRUE)
		{
			// Gather all results and return json
			$result = ORM::factory(Kohana::config('database.default.table_prefix').'smsdam')
							->where(array('status !=' => '1'))
							->where(array('status !=' => '2'));
			echo $result->count_all();
		}
	}
	
	function decide()
	{
		// First check that we have a valid key
		$keycheck = ORM::factory(Kohana::config('database.default.table_prefix').'settings', 1)
						->where('frontlinesms_key', $_GET['key'])
						->find();
		if ($keycheck->loaded == TRUE)
		{
			if(isset($_GET['id']) AND isset($_GET['status']))
			{
				//If we're approving the message, pass it on to FrontlineSMS
				if($_GET['status'] == 1)
				{
					$result = ORM::factory(Kohana::config('database.default.table_prefix').'smsdam')->find($_GET['id']);
					$this->_save_sms($result->usid,$result->message,$result->date_received);
				}
				
				$db = new Database();
				$db->update(Kohana::config('database.default.table_prefix').'smsdam', array('status' => $_GET['status']), array('id' => $_GET['id']));
			}
		}
	}
	
	public function _save_sms($usid,$message_description,$date_received)
	{
		$services = new Service_Model();
		$service = $services->where('service_name', 'SMS')->find();
		if (!$service) 
			return;
	
		$reporter = ORM::factory('reporter')
							->where('service_id', $service->id)
							->where('service_account', $usid)
							->find();

		if (!$reporter->loaded == TRUE)
		{
			// get default reporter level (Untrusted)
			$level = ORM::factory('level')
				->where('level_weight', 0)
				->find();
			
			$reporter->service_id = $service->id;
			$reporter->level_id = $level->id;
			$reporter->service_userid = null;
			$reporter->service_account = $usid;
			$reporter->reporter_first = null;
			$reporter->reporter_last = null;
			$reporter->reporter_email = null;
			$reporter->reporter_phone = null;
			$reporter->reporter_ip = null;
			$reporter->reporter_date = $date_received;
			$reporter->save();
		}
		
		// Save Message
		$message = new Message_Model();
		$message->parent_id = 0;
		$message->incident_id = 0;
		$message->user_id = 0;
		$message->reporter_id = $reporter->id;
		$message->message_from = $usid;
		$message->message_to = null;
		$message->message = $message_description;
		$message->message_type = 1; // Inbox
		$message->message_date = $date_received;
		$message->service_messageid = null;
		$message->save();
	}
}
