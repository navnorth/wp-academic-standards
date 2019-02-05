<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load Admin Scripts
add_action( 'admin_enqueue_scripts' , 'load_admin_scripts' );
function load_admin_scripts(){
    $font_awesome = array('font-awesome', 'fontawesome');
    if (was_stylesheet_installed($font_awesome)===false)
        wp_enqueue_style( 'fontawesome', WAS_URL.'lib/fontawesome/css/all.css');
    wp_enqueue_style( 'admin-css', WAS_URL.'css/admin.css');
    wp_enqueue_style( 'bootstrap-css', WAS_URL.'lib/bootstrap/css/bootstrap.min.css');
    wp_enqueue_script( 'bootstrap-js', WAS_URL.'lib/bootstrap/js/bootstrap.min.js', array('jquery'));
    wp_enqueue_script( 'admin-js', WAS_URL.'js/admin.js', array('jquery'));
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
    include_once(WAS_PATH."/template/standards-importer.php");
}

/**
 * Process Import Standards
 **/
add_action("admin_action_import_standards","import_was_standards");
function import_was_standards(){
    require_once(WAS_PATH."classes/class-standards-importer.php");
    $standard_importer = new was_standards_importer;
    
    $message = null;
    $type = null;
    $other = false;

    if (!current_user_can('manage_options')) {
	    wp_die( "You don't have permission to access this page!" );
    }
    
    //Standards Bulk Import
    if(isset($_POST['standards_import']))
    {
	check_admin_referer('oer_standards_nonce_field');
	    
	$files = array();
    
	if (isset($_POST['oer_common_core_mathematics'])){
	       $files[] = OER_PATH."samples/CCSS_Math.xml";
	}
    
	if (isset($_POST['oer_common_core_english'])){
	       $files[] = OER_PATH."samples/CCSS_ELA.xml";
	}
    
	if (isset($_POST['oer_next_generation_science'])){
	       $files[] = OER_PATH."samples/NGSS.xml";
	}
	
	if (isset($_POST['oer_standard_other']) && isset($_POST['oer_standard_other_url'])){
	       $files[] = $standard_importer->download_standard($_POST['oer_standard_other_url']);
	       $other = true;
	}
	
	foreach ($files as $file) {
	    $import = $standard_importer->import_standard($file, $other);
	    if ($import['type']=="success") {
		if (strpos($file,'Math')) {
		    $message .= "Successfully imported Common Core Mathematics Standards. \n";
		} elseif (strpos($file,'ELA')) {
		    $message .= "Successfully imported Common Core English Language Arts Standards. \n";
		} elseif (strpos($file,'NGSS')) {
		    $message .= "Successfully imported Next Generation Science Standards. \n";
		} else {
		    $message .= "Successfully imported standards. \n";
		}
	    }
	    $type = urlencode($import['type']);
	}
	$message = urlencode($message);
    }
    
    wp_safe_redirect( admin_url("admin.php?page=import-standards&message=$message&type=$type"));
    exit;
}

?>