<?php
/*
 Plugin Name:  WP Academic Standards
 Plugin URI:   https://www.navigationnorth.com
 Description:  Wordpress Academic Standards
 Version:      0.0.1
 Author:       Navigation North
 Author URI:   https://www.navigationnorth.com
 Text Domain:  wp-academic-standards
 License:      GPL3
 License URI:  https://www.gnu.org/licenses/gpl-3.0.html

 Copyright (C) 2019 Navigation North

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//defining constants for slugs, path, name, and version
define( 'WAS_URL', plugin_dir_url(__FILE__) );
define( 'WAS_PATH', plugin_dir_path(__FILE__) );
define( 'WAS_SLUG','wp-academic-standards' );
define( 'WAS_FILE',__FILE__);
define( 'WAS_PLUGIN_NAME', 'WP Academic Standards' );
define( 'WAS_ADMIN_PLUGIN_NAME', 'WP Academic Standards');
define( 'WAS_VERSION', '0.0.1' );

register_activation_hook(__FILE__, 'was_create_table');
function was_create_table()
{
	global $wpdb;
	$subprefix = "was_";

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . $subprefix . "core_standards";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	{
	  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			    id int(20) NOT NULL AUTO_INCREMENT,
			    standard_name varchar(255) NOT NULL,
			    standard_url varchar(255) NOT NULL,
			    PRIMARY KEY (id)
			    );";
	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  dbDelta($sql);
       }

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . $subprefix . "sub_standards";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	{
	  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			    id int(20) NOT NULL AUTO_INCREMENT,
			    parent_id varchar(255) NOT NULL,
			    standard_title varchar(255) NOT NULL,
			    url varchar(255) NOT NULL,
			    PRIMARY KEY (id)
			    );";
	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	  dbDelta($sql);
	}

	//Change hard-coded table prefix to $wpdb->prefix
	$table_name = $wpdb->prefix . $subprefix . "standard_notation";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name)
	 {
	   $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			     id int(20) NOT NULL AUTO_INCREMENT,
			     parent_id varchar(255) NOT NULL,
			     standard_notation varchar(255) NOT NULL,
			     description varchar(255) NOT NULL,
			     comment varchar(255) NOT NULL,
			     url varchar(255) NOT NULL,
			     PRIMARY KEY (id)
			     );";
	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql);
	}
}

//Load localization directory
add_action('plugins_loaded', 'was_load_textdomain');
function was_load_textdomain() {
	load_plugin_textdomain( 'wp-academic-standards', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

/** Add Settings Link on Plugins page **/
add_filter( 'plugin_action_links' , 'was_add_settings_link' , 10 , 2 );
/** Add Settings Link function **/
function was_add_settings_link( $links, $file ){
	if ( $file == plugin_basename(dirname(__FILE__).'/wp-academic-standards.php') ) {
		/** Insert settings link **/
		$link = "<a href='edit.php?post_type=standards&page=was_settings'>".__('Settings','wp-acad')."</a>";
		array_unshift($links, $link);
		/** End of Insert settings link **/
	}
	return $links;
}

?>