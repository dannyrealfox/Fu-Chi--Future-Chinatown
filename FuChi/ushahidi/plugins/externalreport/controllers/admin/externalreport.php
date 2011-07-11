<?php defined('SYSPATH') or die('No direct script access.');
/**
 * controller for flickrwijit
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
class Externalreport_Controller extends Admin_Controller {
	
	public function __construct() 
	{
		parent::__construct();

		$this->template->this_page = 'externalreport';
		
	}
	
	function index()
	{
		$this->template->content = new View('admin/externalreport_download');
		
		$form = array(
			'data_point'      => '',
			'data_include'      => '',
			'from_date'    => '',
			'to_date'    => ''
		);
		$errors = $form;
		$form_error = FALSE;
		
		// check, has the form been submitted, if so, setup validation
	    if ($_POST)
	    {
            // Instantiate Validation, use $post, so we don't overwrite $_POST fields with our own things
			$post = Validation::factory($_POST);

	         //  Add some filters
	        $post->pre_filter('trim', TRUE);

	        // Add some rules, the input field, followed by a list of checks, carried out in order
	        $post->add_rules('data_point.*','required','numeric','between[1,4]');
			$post->add_rules('data_include.*','numeric','between[1,5]');
			$post->add_rules('from_date','date_mmddyyyy');
			$post->add_rules('to_date','date_mmddyyyy');
			
			// Validate the report dates, if included in report filter
			if (!empty($_POST['from_date']) || !empty($_POST['to_date']))
			{	
				// Valid FROM Date?
				if (empty($_POST['from_date']) || (strtotime($_POST['from_date']) > strtotime("today"))) {
					$post->add_error('from_date','range');
				}
				
				// Valid TO date?
				if (empty($_POST['to_date']) || (strtotime($_POST['to_date']) > strtotime("today"))) {
					$post->add_error('to_date','range');
				}
				
				// TO Date not greater than FROM Date?
				if (strtotime($_POST['from_date']) > strtotime($_POST['to_date'])) {
					$post->add_error('to_date','range_greater');
				}
			}
			
			// Test to see if things passed the rule checks
	        if ($post->validate())
	        {
				// Add Filters
				$filter = " ( 1=1";
				// Report Type Filter
				foreach($post->data_point as $item)
				{
					if ($item == 1) {
						$filter .= " OR incident_active = 1 ";
					}
					if ($item == 2) {
						$filter .= " OR incident_verified = 1 ";
					}
					if ($item == 3) {
						$filter .= " OR incident_active = 0 ";
					}
					if ($item == 4) {
						$filter .= " OR incident_verified = 0 ";
					}
				}
				$filter .= ") ";
				
				// Report Date Filter
				if (!empty($post->from_date) && !empty($post->to_date)) 
				{
					$filter .= " AND ( incident_date >= '" . date("Y-m-d H:i:s",strtotime($post->from_date)) . "' AND incident_date <= '" . date("Y-m-d H:i:s",strtotime($post->to_date)) . "' ) ";					
				}
				
				// Retrieve reports
				$incidents = ORM::factory('incident')->where($filter)->orderby('incident_dateadd', 'desc')->find_all();
				
				// Column Titles
				$report_csv = "#,INCIDENT TITLE,INCIDENT DATE";
				foreach($post->data_include as $item)
				{
					if ($item == 1) {
						$report_csv .= ",LOCATION";
					}
					if ($item == 2) {
						$report_csv .= ",DESCRIPTION";
					}
					if ($item == 3) {
						$report_csv .= ",CATEGORY";
                                        }
                                        if ($item == 4) {
                                                $report_csv .= ",LATITUDE";
                                        }
                                        if($item == 5) {
                                                $report_csv .= ",LONGITUDE";
                                        }
				}
				$report_csv .= ",APPROVED,VERIFIED";
				$report_csv .= "\n";
				
				foreach ($incidents as $incident)
				{
					$report_csv .= '"'.$incident->id.'",';
					$report_csv .= '"'.$this->_csv_text($incident->incident_title).'",';
					$report_csv .= '"'.$incident->incident_date.'"';
					
					foreach($post->data_include as $item)
					{
						if ($item == 1) {
                                                        $report_csv .= ',"'.$this->_csv_text($incident->location->location_name).'"';
						}
						if ($item == 2) {
							$report_csv .= ',"'.$this->_csv_text($incident->incident_description).'"';
						}
						if ($item == 3) {
							$report_csv .= ',"';
							foreach($incident->incident_category as $category) 
							{
								if ($category->category->category_title)
								{
									$report_csv .= $this->_csv_text($category->category->category_title) . ", ";
								}
							}
							$report_csv .= '"';
                                                }
                                                if ($item == 4) {
                                                        $report_csv .= ',"'.$this->_csv_text($incident->location->latitude).'"';
                                                }
                                                if ($item == 5) {
                                                        $report_csv .= ',"'.$this->_csv_text($incident->location->longitude).'"';
                                                }
					}
					if ($incident->incident_active) {
						$report_csv .= ",YES";
					}
					else
					{
						$report_csv .= ",NO";
					}
					if ($incident->incident_verified) {
						$report_csv .= ",YES";
					}
					else
					{
						$report_csv .= ",NO";
					}
					$report_csv .= "\n";
				}
				
				// Output to browser
				header("Content-type: text/x-csv");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Content-Disposition: attachment; filename=" . time() . ".csv");
				header("Content-Length: " . strlen($report_csv));
				echo $report_csv;
				exit;
				
	        }
			// No! We have validation errors, we need to show the form again, with the errors
	        else   
			{
	            // repopulate the form fields
	            $form = arr::overwrite($form, $post->as_array());

	            // populate the error fields, if any
	            $errors = arr::overwrite($errors, $post->errors('externalreport'));
				$form_error = TRUE;
	        }
		}
		
		$this->template->content->form = $form;
	    $this->template->content->errors = $errors;
		$this->template->content->form_error = $form_error;
		
		// Javascript Header
		$this->template->js = new View('admin/externalreport_download_js');
		$this->template->js->calendar_img = url::base() . "media/img/icon-calendar.gif";
	}
}
