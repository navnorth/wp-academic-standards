<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div>
    <h2><?php _e('WP Academic Standards', WAS_SLUG); ?></h2>
    <?php
    $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
    if ($results){
    ?>
    <ul class='standard-list'>
        <?php
        foreach($results as $row){
            $value = 'core_standards-'.$row['id'];
            ?>
            <li class='core-standard'>
                <a data-toggle='collapse' data-target='#core_standards-<?php echo $row['id']; ?>'><?php echo $row['standard_name']; ?></a>
            </li>
        <?php
            child_standards($value);
        }
        ?>
    </ul>
    <?php
    }
    ?>
</div>