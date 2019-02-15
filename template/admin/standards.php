<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e('WP Academic Standards', WAS_SLUG); ?></h2>
    <?php was_display_admin_standards(); ?>
    <?php was_search_standards(25144,"writing"); ?>
</div>