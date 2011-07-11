<?php
/**
 * Performs install/uninstall methods for the adminsection plugin
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Actionable Installer
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Adminsection_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db = new Database();
	}

	/**
	 * Creates the required database tables for the actionable plugin
	 */
	public function run_install()
	{
		
		$adminsection = "CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."adminsection` (
  			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  			`adminsection_title` varchar(255) DEFAULT NULL,
  			`adminsection_active` tinyint(4) NOT NULL DEFAULT '1',
  			PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		
		$adminsection_users = "CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."adminsection_users` (
  			`user_id` int(11) NOT NULL ,
  			`adminsection_id` int(11) DEFAULT NULL,
  			PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		
		$crowd_incident = "CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."crowd_report` (
  			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`incident_id` int(11) NOT NULL,
  			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$monitor_incident = "CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."monitor_report` (
  			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`incident_id` int(11) NOT NULL,
  			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$peacenet_incident = "CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."peacenet_report` (
  			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`incident_id` int(11) NOT NULL,
  			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		$report_mode = "CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."report_mode` (
  			`id` int(11) NOT NULL AUTO_INCREMENT,
  			`incident_id` int(11) NOT NULL,
  			PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		// Create the database tables.
		// Also include table_prefix in name
		$this->db->query($adminsection);
		$this->db->query($adminsection_users);
	}
	
	

	/**
	 * Deletes the database tables for the actionable module
	 */
	public function uninstall()
	{
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'adminsection');
		$this->db->query('DROP TABLE `'.Kohana::config('database.default.table_prefix').'adminsection_users');		
	}
}