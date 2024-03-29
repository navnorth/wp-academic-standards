<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

if (isset($_REQUEST['std'])) {
    $standard = sanitize_text_field($_REQUEST['std']);
    $root_standard = was_standard_details($standard);
?>
<div class="wrap">
    <div class="oer-backlink">
        <a class="backlink-btn button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=wp-academic-standards')); ?>"><?php _e("<i class='fa fa-angle-double-left'></i> Back to Standards",WAS_SLUG); ?></a>
    </div>
    <div id="icon-themes" class="icon32"></div>
    <div class="wrap-header">
        <h1 class="wp-heading-inline standard-heading-inline"><?php echo stripslashes($root_standard->standard_name); ?></h1>
        <a data-target="#addStandardModal" class="page-title-action float-right" data-parent="<?php echo $standard; ?>" id="addStandard">Add New Standard</a>
    </div>
    <div class="notice notice-success standards-notice-success is-dismissible hidden-block"></div>
    <div id="admin-standard-children-list">
    <?php was_children_standards($standard,true); ?>
    </div>
</div>
<?php
}
?>