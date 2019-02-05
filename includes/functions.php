<?php
global $wpdb;

/** Check Child Standard Notation **/
if (!function_exists('check_child_standard')) {
    function check_child_standard($id)
    {
            global $wpdb;
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $id ) , ARRAY_A);
            return $results;
    }
}

/** Get Substandard Children **/
if (!function_exists('get_substandard_children')){
    function get_substandard_children($id)
    {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $id ) , ARRAY_A);
        return $results;
    }
}

// Get Title or Description of Standard or Notation
if (!function_exists('get_standard_label')) {
    function get_standard_label($slug){
        global $wpdb;
        
        $slugs = explode("-", $slug);
        $table_name = "oer_".$slugs[0];
        $id = $slugs[1];
        $standard = null;
        
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. $table_name . " where id = %s" , $id ) , ARRAY_A);
        if (!empty($results)){
                foreach($results as $result) {
                        $standard = $result['description'];
                }
        }
        
        return $standard;
    }
}

/** Get Sub Standard **/
if (!function_exists('child_standards')){
    function child_standards($id)
    {
        global $wpdb, $chck, $class;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $id ) ,ARRAY_A);
        
        if(!empty($results))
        {
            echo "<div id='".$id."' class='collapse'>";
            echo "<ul>";
            foreach($results as $result)
            {
                $value = 'sub_standards-'.$result['id'];

                $id = 'sub_standards-'.$result['id'];
                $subchildren = get_substandard_children($id);
                $child = check_child_standard($id);

                echo "<li class='was_sbstndard ". $class ."'>";
                
                if (!empty($subchildren)){
                    echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_title']."</a>";
                }
                
                if(empty($subchildren) && empty($child)) {
                    echo $result['standard_title'];
		}
                
                $id = 'sub_standards-'.$result['id'];
                child_standards($id);
                
                if (!empty($child)) {
                    echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_title']."</a>";
                    $sid = 'sub_standards-'.$result['id'];
                    child_standard_notations($sid);
                }
                echo "</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }
}

/** Get Standard Notation **/
if (!function_exists('child_standard_notations')) {
    function child_standard_notations($id)
    {
        global $wpdb, $class;
        
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $id ) , ARRAY_A);
    
        if(!empty($results))
        {
            echo "<div id='".$id."' class='collapse'>";
            echo "<ul>";
                foreach($results as $result)
                {
                    $id = 'standard_notation-'.$result['id'];
                    $child = check_child_standard($id);
                    $value = 'standard_notation-'.$result['id'];
    
                    echo "<li class='".$class."'>";
                    if(!empty($child))
                    {
                        echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_notation']."</a>";
                    }
                            
                    echo  "<strong>".$result['standard_notation']."</strong>
                        <div class='was_stndrd_desc'> ". $result['description']." </div>";
    
                    echo "</li>";
                    
                    child_standard_notations($id);
                }
            echo "</ul>";
            echo "</div>";
        }
    }
}

if (!function_exists('was_display_loader')){
    function was_display_loader(){
        ?>
        <div class="loader"><div class="loader-img"><div><img src="<?php echo OER_URL; ?>images/loading.gif" align="center" valign="middle" /></div></div></div>
        <?php
    }
}

if (!function_exists('was_display_admin_standards')){
    function was_display_admin_standards(){
        global $wpdb;
        $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
        if ($results){
        ?>
        <ul class='was-standard-list'>
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
    }
}

if (!function_exists('was_stylesheet_installed')){
    function was_stylesheet_installed($arr_styles)
    {
        global $wp_styles;
    
        foreach( $wp_styles->queue as $style ) 
        {
            foreach ($arr_styles as $css)
            {
                if (false !== strpos( $wp_styles->registered[$style]->src, $css ))
                    return true;
            }
        }
        return false; 
    }
}