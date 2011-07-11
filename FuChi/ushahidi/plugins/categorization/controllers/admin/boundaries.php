<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This controller is used to manage administrative boundaries
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

class Boundaries_Controller extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'boundaries';

	}

	function index()
	{
		$this->template->content = new View('boundaries/boundaries');
		$this->template->content->title = 'Administrative Boundaries';

		// setup and initialize form field names
		$form = array
		(
			'action' => '',
			'boundary_id'      => '',
			'boundary_name'      => ''
			);
			 
			// copy the form as errors, so the errors will be stored with keys corresponding to the form field names
			$errors = $form;
			$form_error = FALSE;
			$form_saved = FALSE;
			$form_action = "";
			// check, has the form been submitted, if so, setup validation
			if ($_POST)
			{
				// Instantiate Validation, use $post, so we don't overwrite $_POST fields with our own things
				$post = Validation::factory(array_merge($_POST,$_FILES));
					
				//  Add some filters
				$post->pre_filter('trim', TRUE);

				if ($post->action == 'a')		// Add Action
				{
					// Add some rules, the input field, followed by a list of checks, carried out in order
					$post->add_rules('boundary_name','required', 'length[3,80]');
				}
					
				// Test to see if things passed the rule checks
				if ($post->validate())
				{
					$boundary_id = $post->boundary_id;
					$boundary = new Boundary_Model($boundary_id);

					if( $post->action == 'd' )
					{ // Delete Action
						$boundary->delete( $boundary_id );
						$form_saved = TRUE;
						$form_action = 'DELETED';
							
					}
					else if( $post->action == 'a' )
					{ // Save Action
						$boundary->boundary_name = $post->boundary_name;
						$boundary->save();
							
						$form_saved = TRUE;
						$form_action = 'ADDED/EDITED!';
					}
				}
				// No! We have validation errors, we need to show the form again, with the errors
				else
				{
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
                            'total_items'    => ORM::factory('boundary')
			->count_all()
			));

			$boundaries = ORM::factory('boundary')
			->orderby('boundary_name', 'asc')
			->find_all((int) Kohana::config('settings.items_per_page_admin'),
			$pagination->sql_offset);

			$this->template->content->errors = $errors;
			$this->template->content->form_error = $form_error;
			$this->template->content->form_saved = $form_saved;
			$this->template->content->form_action = $form_action;
			$this->template->content->pagination = $pagination;
			$this->template->content->total_items = $pagination->total_items;
			$this->template->content->boundaries = $boundaries;

			// Locale (Language) Array
			$this->template->content->locale_array = Kohana::config('locale.all_languages');

			// Javascript Header
			$this->template->colorpicker_enabled = TRUE;
			$this->template->js = new View('js/boundaries_js');
	}

}
