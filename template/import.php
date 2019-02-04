<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Import Page **/
global $wpdb;

$message = isset($_GET['message'])?urldecode($_GET['message']):null;
$type = isset($_GET['type'])?urldecode($_GET['type']):null;

if ($type=="lr"){
	if ($message=="0")
		$message = "No record was imported.";
	elseif ($message=="1")
		$message .= " record imported.";
	else
		$message .= " records imported.";
	
	$type="success";
}

if (!current_user_can('manage_options')) {
	wp_die( "You don't have permission to access this page!" );
}

?>
<div class="wrap">
    <div id="icon-themes" class="oer-logo"><img src="<?php echo esc_url(WAS_URL . 'images/wp-oer-admin-logo.png'); ?>" /></div>
    <p class="oer_heading">Import - Academic Standards</p>
    <?php settings_errors(); ?>
    <div class="oer-import-body">
	<div class="oer-import-row">
		<div class="row-left">
			<?php _e("Use the options below to import data sets to the OER tool. Additional information can be found on the support forums or wp-oer.com.", OER_SLUG); ?>
			<div class="oer-import-row">
			<h2 class="hidden"></h2>
			<?php if ($message) { ?>
    			<div class="notice notice-<?php echo $type; ?> is-dismissible">
    			    <p><?php echo $message; ?></p>
    			</div>
			<?php } ?>
			</div>
			<div class="oer-import-row">
			    <?php
				include_once(WAS_PATH.'template/standards-importer.php');
			    ?>
			</div>
		</div>
	</div>
    </div>
</div><!-- /.wrap -->
<div class="plugin-footer">
	<div class="plugin-info"><?php echo WAS_ADMIN_PLUGIN_NAME . " " . WAS_VERSION .""; ?></div>
	<div class="clear"></div>
</div>
<?php was_display_loader(); ?>
