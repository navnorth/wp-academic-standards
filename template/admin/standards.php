<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e('WP Academic Standards', WAS_SLUG); ?></h2>
    <div class="notice notice-success standards-notice-success is-dismissible hidden-block">
    	
    </div>
    <?php was_display_admin_standards(); ?>
</div>