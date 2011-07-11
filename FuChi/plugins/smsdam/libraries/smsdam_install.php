<?php
/**
 * Performs install/uninstall methods for the SMS Dam Reports plugin
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   SMS Dam Reports Installer
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Smsdam_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = Database::instance();
	}

	/**
	 * Creates the required database tables for the SMS Dam plugin
	 */
	public function run_install()
	{
		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.Kohana::config('database.default.table_prefix').'smsdam` (
						`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`usid` varchar(100) DEFAULT NULL,
						`message` text,
						`date_received` datetime NOT NULL,
						`status` tinyint(4) NOT NULL COMMENT \'0 = Queued, 1 = Passed On, 2 = Set Aside\',
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;');
		
		// Add this as a service so it shows up in nav
		//$this->db->query('INSERT INTO `'.Kohana::config('database.default.table_prefix').'service` (
		//				`id`, `service_name`, `service_description`, `service_url`, `service_api`
		//				) VALUES ( NULL , \'SMS Dam\', \'Control flow of SMS messages\', NULL, NULL);');
	}

	/**
	 * Deletes the database tables for the SMS Dam module
	 */
	public function uninstall()
	{
		// We don't want to drop the table because we don't want to lose messages on accident
		
		//$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'smsdam`');
	}
}