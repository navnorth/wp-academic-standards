<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    
}

?>