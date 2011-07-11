<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This controller is used to manage form codes
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

class Codes_Controller extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'codes';
		
	}

	function index()
	{	
		$this->template->content = new View('codes/codes');
		$this->template->content->title = 'Form Codes';
		
		// setup and initialize form field names
		$form = array
	    (
			'action' => '',
			'code_id'      => '',
			'code_code_id'  => '',
			'code_description'      => ''
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
				$post->add_rules('code_code_id','required');
				$post->add_rules('code_description','required', 'length[3,80]');
			}
			
			// Test to see if things passed the rule checks
	        if ($post->validate())
	        {
				$code_id = $post->code_id;
				$code = new Code_Model($code_id);
				
				if( $post->action == 'd' )
				{ // Delete Action
					$code->delete($code_id );
					$form_saved = TRUE;
					$form_action = 'DELETED';
			
				}
				else if( $post->action == 'a' )
				{ // Save Action				
					$code->code_description = $post->code_description;
					$code->code_id = $post->code_code_id;
					$code->save();
					
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
                            'total_items'    => ORM::factory('code')
			     ->count_all()
                        ));

        $codes = ORM::factory('code')
                        ->orderby('code_id', 'asc')
                        ->find_all((int) Kohana::config('settings.items_per_page_admin'), 
                            $pagination->sql_offset);
		
		$this->template->content->errors = $errors;
        $this->template->content->form_error = $form_error;
        $this->template->content->form_saved = $form_saved;
		$this->template->content->form_action = $form_action;
		$this->template->content->form = $form;
        $this->template->content->pagination = $pagination;
        $this->template->content->total_items = $pagination->total_items;
        $this->template->content->codes = $codes;
		
		// Locale (Language) Array
		$this->template->content->locale_array = Kohana::config('locale.all_languages');

        // Javascript Header
        $this->template->colorpicker_enabled = TRUE;
        $this->template->js = new View('js/codes_js');
    }

}
