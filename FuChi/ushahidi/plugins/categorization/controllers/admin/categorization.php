<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This controller is used to manage monitorrs
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Admin Users Controller
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Categorization_Controller extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'categorization';

	}

	function index()
	{
		$this->template->content = new View('categorization/categorization');
		$this->template->content->title = 'Manage Monitors';

		// set up and initialize form fields
		$form = array
		(
			'action'	=> '',
			'monitor_id' => '',
			'location_id' => '',
			'phonenumber' => '',
			'polling_station' => '',
			'boundary_name' => ''
			);
			//copy the form as errors, so the errors will be stored with keys corresponding to the form field names
			$errors = $form;
			$form_error = FALSE;
			$form_saved = FALSE;
			$form_action = "";
			$location_array = array();

			// check, has the form been submitted, if so, setup validation
			if ($_POST)
			{

				$post = Validation::factory($_POST);
					
				//  Add some filters
				$post->pre_filter('trim', TRUE);
					
				if ($post->action == 'a') 				// Add/Edit Action
				{
					// Add some rules, the input field, followed by a list of checks, carried out in order
					$post->add_rules('phonenumber','required','length[3,50]');
					$post->add_rules('location_id','required');
				}
					
				if ($post->validate())
				{

					//$monitor = ORM::factory('monitors',$post->monitor_id);
					$monitor_id = $post->monitor_id;
					$monitor = new Monitor_Model($monitor_id);

					if( $post->action == 'd' )
					{ // Delete Action
						$monitor->delete( $monitor_id );
						$form_saved = TRUE;
						$form_action = 'DELETED';
							
					}
					elseif ($post->action == 'a') 				// Add/Edit Action
					{
						$monitor = ORM::factory('monitor',$post->monitor_id);

						// Existing Monitor??
						if ($monitor->loaded==true)
						{
							$monitor->phonenumber = $post->phonenumber;
							$monitor->location_id = $post->location_id;
							$monitor->polling_station = $post->polling_station;
							$monitor->save();

							$form_saved = TRUE;
							$form_action = "Edited";
						}
						else
						{
							$monitor->phonenumber = $post->phonenumber;
							$monitor->location_id = $post->location_id;
							$monitor->polling_station = $post->polling_station;
							$monitor->save();

							$form_saved = TRUE;
							$form_action = "Added";
						}
					}

				}
				else{
					// repopulate the form fields
					$form = arr::overwrite($form, $post->as_array());

					// populate the error fields, if any
					$errors = arr::overwrite($errors, $post->errors('categorization'));
					$form_error = TRUE;
				}
			}

			// Pagination
			$pagination = new Pagination(array(
                            'query_string' => 'page',
                            'items_per_page' => (int) Kohana::config('settings.items_per_page_admin'),
                            'total_items'    => ORM::factory('monitor')
			->count_all()
			));
			$monitors = ORM::factory('monitor')->orderby('monitor.location_id', 'asc')
			->find_all((int) Kohana::config('settings.items_per_page_admin'),
			$pagination->sql_offset);


			/* Get the list of locations */
			$location_array = ORM::factory('boundary')
			->select_list('id', 'boundary_name');
			
			$this->template->content->form = $form;
			$this->template->content->form_error = $form_error;
			$this->template->content->form_saved = $form_saved;
			$this->template->content->form_action = $form_action;
			$this->template->content->pagination = $pagination;
			$this->template->content->total_items = $pagination->total_items;
			$this->template->content->monitors = $monitors;
			$this->template->content->errors = $errors;
			$this->template->content->location_array = $location_array;

			// Javascript Header
			$this->template->colorpicker_enabled = TRUE;
			$this->template->js = new View('js/categorization_js');
	}


	/**
	 * Checks if phonenumber already exists.
	 * @param Validation $post $_POST variable with validation rules
	 */
	/*
	 public function phonenumber_exists_chk(Validation $post)
	 {
	 $monitors = ORM::factory('monitor');
	 // If add->rules validation found any errors, get me out of here!
	 if (array_key_exists('phonenumber', $post->errors()))
	 return;

	 if( $monitors->phonenumber_exists($post->phonenumber) )
	 $post->add_error( 'phonenumber', 'exists');
	 }
	 */


}
