<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

if (isset($_REQUEST['std'])){
    include_once(WAS_PATH."template/admin/standard-children.php");
} else {
?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <div class="wrap-header">
        <h1 class="wp-heading-inline"><?php _e('WP Academic Standards', WAS_SLUG); ?></h1>
        <a data-target="#addStandardModal" class="page-title-action" id="addStandardSet">Add New Standard Set</a>
    </div>
    <div class="notice notice-success standards-notice-success is-dismissible hidden-block"></div>
    <div id="admin-standard-list">
    <?php was_display_admin_core_standards(); ?>
    </div>
</div>
<?php } ?>