<?php
/**
 * Performs install/uninstall methods for the Categorization Plugin
 *
 * @package    Ushahidi
 * @author     Ushahidi Team
 * @copyright  (c) 2008 Ushahidi Team
 * @license    http://www.ushahidi.com/license.html
 */
class Categorization_Install {

	/**
	 * Constructor to load the shared database library
	 */
	public function __construct()
	{
		$this->db =  new Database();
	}

	/**
	 * Creates the required database tables for the categorization module
	 */
	public function run_install()
	{
		// Create the database tables
		// Include the table_prefix
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."monitor`
			(
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`location_id` INT(4) NOT NULL COMMENT 'location_id of the monitor',
				`phonenumber` varchar(25) NOT NULL COMMENT 'phone number of the monitor',
				`polling_station` varchar(50) NOT NULL COMMENT 'polling station of the monitor',
				PRIMARY KEY (id)
			);");
		$this->db->query("
                        CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."boundary`
                        (
                                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `boundary_name` varchar(100) NOT NULL COMMENT 'name of the boundary',
                                PRIMARY KEY (id)
                        );");

                $this->db->query("
                        CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."code`
                        (
                                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`code_id` INT(11) NOT NULL COMMENT 'form_code',
                                `code_description` text NOT NULL COMMENT 'form code description',
                                PRIMARY KEY (id)
                        );");

			
	}

	/**
	 * Deletes the database tables for the categorization module
	 */
	public function uninstall()
	{
		$this->db->query("
			DROP TABLE ".Kohana::config('database.default.table_prefix')."monitor;
			DROP TABLE ".Kohana::config('database.default.table_prefix')."boundary;
			DROP TABLE ".Kohana::config('database.default.table_prefix')."code;
			");
	}
}
