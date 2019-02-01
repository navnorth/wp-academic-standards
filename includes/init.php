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
    add_menu_page("WP Acamedic Standards",
                  "Standards",
                  "edit_posts",
                  "wp-academic-standards",
                  "wp_academic_standards_page",
                  "dashicons-awards",
                  26);
}

function wp_academic_standards_page(){
    include_once(WAS_PATH."/template/standards.php");
}

?>