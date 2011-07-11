<?php defined('SYSPATH') or die('No direct script access.');
/**
* Model for SMS Dam
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     SMS Dam Reports Model
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Smsdam_Model extends ORM
{
	protected $belongs_to = array('messages');
	
	// Database table name
	protected $table_name = 'smsdam';
}
