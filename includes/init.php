<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load Admin Scripts
add_action( 'admin_enqueue_scripts' , 'load_admin_scripts' );
function load_admin_scripts(){
    wp_enqueue_style( 'admin-css', WAS_URL.'css/admin.css');
    wp_enqueue_style( 'bootstrap-css', WAS_URL.'lib/bootstrap/css/bootstrap.min.css');
    wp_enqueue_script( 'bootstrap-js', WAS_URL.'lib/bootstrap/js/bootstrap.min.js', array('jquery'));
}

// Add Standards Menu on Admin
add_action( 'admin_menu' , 'add_standards_menu' );
function add_standards_menu(){
    add_menu_page(__("WP Acamedic Standards", WAS_SLUG),
                  __("Standards",WAS_SLUG),
                  "edit_posts",
                  "wp-academic-standards",
                  "wp_academic_standards_page",
                  "dashicons-awards",
                  26);
    add_submenu_page("wp-academic-standards",
                     __("Import Standards", WAS_SLUG),
                     __("Import", WAS_SLUG),
                     "edit_posts",
                     "import-standards",
                     "was_import_standards_page");
}

function wp_academic_standards_page(){
    include_once(WAS_PATH."/template/standards.php");
}

function was_import_standards_page(){
    include_once(WAS_PATH."/template/import.php");
}

?>