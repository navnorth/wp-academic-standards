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
    function child_standards($id, $oer_standard = "")
    {
        global $wpdb;
        global $chck, $class;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $id ) ,ARRAY_A);
        if(!empty($oer_standard))
        {
            $stndrd_arr = explode(",",$oer_standard);
        }
        
        if(!empty($results))
        {
            echo "<div id='".$id."' class='collapse'>";
            echo "<ul>";
            foreach($results as $result)
            {
                $value = 'sub_standards-'.$result['id'];
                if(!empty($stndrd_arr))
                {
                    if(in_array($value, $stndrd_arr))
                    {
                        $chck = 'checked="checked"';
                        $class = 'selected';
                    }
                    else
                    {
                        $chck = '';
                        $class = '';
                    }
                }

                $id = 'sub_standards-'.$result['id'];
                $subchildren = get_substandard_children($id);
                $child = check_child_standard($id);

                echo "<li class='oer_sbstndard ". $class ."'>";
                
                if (!empty($subchildren)){
                    echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_title']."</a>";
                }
                
                $id = 'sub_standards-'.$result['id'];
                child_standards($id, $oer_standard);
                
                if (!empty($child)) {
                    echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_title']."</a>";
                    $sid = 'sub_standards-'.$result['id'];
                    child_standard_notations($sid, $oer_standard);
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
    function child_standard_notations($id, $oer_standard = "")
    {
        global $wpdb;
        
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $id ) , ARRAY_A);
    
        if(!empty($oer_standard))
        {
            $stndrd_arr = explode(",",$oer_standard);
        }
    
        if(!empty($results))
        {
            echo "<div id='".$id."' class='collapse'>";
            echo "<ul>";
                foreach($results as $result)
                {
                    $chck = '';
                    $class = '';
                    $id = 'standard_notation-'.$result['id'];
                    $child = check_child_standard($id);
                    $value = 'standard_notation-'.$result['id'];
    
                    if(!empty($oer_standard))
                    {
                        if(in_array($value, $stndrd_arr))
                        {
                            $chck = 'checked="checked"';
                            $class = 'selected';
                        }
                    }
    
                    echo "<li class='".$class."'>";
                    if(!empty($child))
                    {
                        echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_notation']."</a>";
                    }
                            
                    echo  $result['standard_notation']."
                        <div class='oer_stndrd_desc'> ". $result['description']." </div>";
    
                    child_standard_notations($id, $oer_standard);
    
                    echo "</li>";
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