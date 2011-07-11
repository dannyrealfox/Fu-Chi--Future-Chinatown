<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Version Notifier Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package	   Ushahidi - http://source.ushahididev.com
 * @module     Version Notifier Hook
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class versionnotifier {

	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{
		// Hook into routing
		Event::add('system.post_routing', array($this, 'add'));
	}

	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Only fire if we are in the admin panel
		$uri_arr = explode('/',Router::$routed_uri);
		if (in_array('admin',$uri_arr))
		{
			Event::add('ushahidi_action.admin_header_top_left', array($this, '_show_version'));
		}
	}

	/**
	 * Show version
	 */
	public function _show_version()
	{
		$view = View::factory('versionnotifier');

		$view->version_in_db = Kohana::config('settings.db_version');
		$view->version_in_config = Kohana::config('version.ushahidi_db_version');

		// Check if we are upgrading anything

		if (isset($_POST['versionnotifier_update']))
		{
			// Update DB
			$db_version = $view->version_in_db;
			$upgrade_to = $db_version + 1;

			// Check if the update script exists
			$upgrade_schema = @file_get_contents('sql/upgrade'.$db_version.'-'.$upgrade_to.'.sql');

			// If a table prefix is specified, add it to sql
			$db_config = Kohana::config('database.default');
			$table_prefix = $db_config['table_prefix'];
			if ($table_prefix)
			{
				$find = array(
					'CREATE TABLE IF NOT EXISTS `',
					'INSERT INTO `',
					'ALTER TABLE `',
					'UPDATE `'
					);
				$replace = array(
					'CREATE TABLE IF NOT EXISTS `'.$table_prefix.'_',
					'INSERT INTO `'.$table_prefix.'_',
					'ALTER TABLE `'.$table_prefix.'_',
					'UPDATE `'.$table_prefix.'_'
					);
				$upgrade_schema = str_replace($find, $replace, $upgrade_schema);
			}

			// Split by ; to get the sql statement for creating individual tables.

			$queries = explode(';',$upgrade_schema);

			foreach ($queries as $query)
			{
				$result = mysql_query($query);
			}
			
			// Delete cache and reload the page
			$cache = Cache::instance();
			$cache->delete(Kohana::config('settings.subdomain').'_settings');
			url::redirect(url::base().'/admin');
		}

		// if the version in the database is less than the one in the config file,
		//    then the database needs to be updated
		if($view->version_in_db < $view->version_in_config)
		{
			$view->needs_upgrade = TRUE;
		}else{
			$view->needs_upgrade = FALSE;
		}

		$view->render(TRUE);
	}
}

new versionnotifier;