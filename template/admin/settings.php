<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $message, $type;

if (isset($_REQUEST['settings-updated'])) {
    if (!current_user_can('manage_options')) {
        wp_die( "You don't have permission to access this page!" );
    }
    
    //Import CCSS Standards
    $import_ccss = get_option('was_import_ccss');
    if ($import_ccss) {
        $response = was_importDefaultStandards();
        if ($response) {
            $message .= $response["message"];
            $type .= $response["type"];
        }
    }
    
    // Standards slug Root
    $standard_root_slug = get_option('was_standard_slug');
    if (isset($standard_root_slug) && $standard_root_slug!==""){
        was_add_rewrites($standard_root_slug);
        //Trigger permalink reset
        flush_rewrite_rules();
        $message = "Permalink structure has been reset for standards root slug ".$standard_root_slug;
        $type = "success";
    }
    
    //Redirect to main settings page
    wp_safe_redirect( admin_url( 'admin.php?page=standards-settings' ) );
    exit();
}
?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2 class="was-plugin-page-title"><?php _e("Settings - WP Academic Standards", WAS_SLUG); ?></h2>
    <?php settings_errors(); ?>
    <?php was_show_setup_settings(); ?>
</div><!-- /.wrap -->
<div class="was-plugin-footer">
    <div class="plugin-info"><?php echo WAS_ADMIN_PLUGIN_NAME . " " . WAS_VERSION .""; ?></div>
    <div class="clear"></div>
</div>
<?php was_display_loader(); ?>