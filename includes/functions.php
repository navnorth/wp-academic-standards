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

if (!function_exists('was_selectable_admin_standards')){
    function was_selectable_admin_standards($post_id, $meta_key="oer_standard"){
        global $wpdb, $post;
        
        $standards = get_post_meta($post_id, $meta_key, true);
        
        $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
        if ($results){
             ?>
            <ul class='oer-standard-list'>
            <?php
              foreach($results as $row){
                $value = 'core_standards-'.$row['id'];
                ?>
                <li class='core-standard'>
                  <a data-toggle='collapse' data-target='#core_standards-<?php echo $row['id']; ?>'><?php echo $row['standard_name']; ?></a>
                </li>
            <?php
                was_child_standards($value, $standards, $meta_key);
              }
        }
    }
}

/** Get Child Standards **/
if (!function_exists('was_child_standards')){
    function was_child_standards($id, $oer_standard, $meta_key="oer_standard") {
	global $wpdb, $chck, $class;
        
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

                    if(empty($subchildren) && empty($child)) {
                        echo "<input type='checkbox' ".$chck." name='".$meta_key."[]' value='".$value."' onclick='was_check_all(this)' >
                                ".$result['standard_title']."
                                <div class='oer_stndrd_desc'></div>";
                    }
                    
                    $id = 'sub_standards-'.$result['id'];
                    was_child_standards($id, $oer_standard, $meta_key);
                    
                    if (!empty($child)) {
                        echo "<a data-toggle='collapse' data-target='#".$id."'>".$result['standard_title']."</a>";
                        $sid = 'sub_standards-'.$result['id'];
                        was_child_standard_notations($sid, $oer_standard, $meta_key);
                    }
                    echo "</li>";
                }
                echo "</ul>";
            echo "</div>";
	}
    }
}

/** Get Standard Notation **/
if (!function_exists('was_child_standard_notations')) {
    function was_child_standard_notations($id, $oer_standard, $meta_key="oer_standard"){
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

				if (empty($child))
					echo "<input type='checkbox' ".$chck." name='".$meta_key."[]' value='".$value."' onclick='was_check_myChild(this)'>";
					
				echo  $result['standard_notation']."
					<div class='oer_stndrd_desc'> ". $result['description']." </div>";

				was_child_standard_notations($id, $oer_standard,$meta_key);

				echo "</li>";
			}
		echo "</ul>";
		echo "</div>";
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

/**
 * Get Standards Count
 **/
if (!function_exists('was_core_standards_count')){
    function was_core_standards_count(){
            global $wpdb;
            $cnt = 0;
            
            $query = "SELECT count(*) FROM {$wpdb->prefix}oer_core_standards";
    
            $cnt = $wpdb->get_var($query);
            
            return $cnt;
    }
}

/**
 * Get Standards
 **/
if (!function_exists('was_core_standards')){
    function was_core_standards(){
            global $wpdb;
            
            $query = "SELECT * FROM {$wpdb->prefix}oer_core_standards";
            
            $standards = $wpdb->get_results($query);
            
            return $standards;
    }
}

/**
 * Get Resource Count By Standard
 **/
if (!function_exists('was_resource_count_by_standard')){
    function was_resource_count_by_standard($standard_id){
            
        $cnt = 0;
        
        $substandards = was_substandards($standard_id);
        
        if(count($substandards)>0){
                foreach($substandards as $substandard){
                        $cnt += was_resource_count_by_substandard($substandard->id);
                }
        }
        $notations = was_standard_notations($standard_id);
        
        if ($notations){
                foreach($notations as $notation){
                        $cnt += was_resource_count_by_notation($notation->id);
                }
        }
        return $cnt;
    }
}

/**
 * Get Resource Count By Sub-Standard
 **/
if (!function_exists('was_resource_count_by_substandard')){
    function was_resource_count_by_substandard($substandard_id){
        $cnt = 0;
        
        $child_substandards = was_substandards($substandard_id, false);
        
        if(count($child_substandards)>0){
            foreach($child_substandards as $child_substandard){
                $cnt += was_resource_count_by_substandard($child_substandard->id, false);
            }
        }
        $notations = was_standard_notations($substandard_id);
        
        if ($notations){
            foreach($notations as $notation){
                $cnt += was_resource_count_by_notation($notation->id);
            }
        }
        return $cnt;
    }
}

/**
 * Get Resource Count By Notation
 **/
if (!function_exists('was_resource_count_by_notation')){
    function was_resource_count_by_notation($notation_id){
        $cnt = 0;
        
        $notation = "standard_notation-".$notation_id;
        
        //later in the request
        $args = array(
                'post_type'  => 'resource', //or a post type of your choosing
                'posts_per_page' => -1,
                'meta_query' => array(
                        array(
                        'key' => 'oer_standard',
                        'value' => $notation,
                        'compare' => 'like'
                        )
                )
        );
        
        $query = new WP_Query($args);
        
        $cnt += count($query->posts);
        
        $child_notations = was_child_notations($notation_id);
        
        if ($child_notations){
                foreach ($child_notations as $child_notation){
                        $cnt += was_resource_count_by_notation($child_notation->id);
                }
        }
        
        return $cnt;
    }
}

/**
 * Get child standards of a core standard
 **/
if (!function_exists('was_substandards')) {
    function was_substandards($standard_id, $core=true){
        global $wpdb;
        
        if ($core)
                $std_id = "core_standards-".$standard_id;
        else
                $std_id = "sub_standards-".$standard_id;
        
        $substandards = array();
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_sub_standards where parent_id='%s'";
        
        $substandards = $wpdb->get_results($wpdb->prepare($query, $std_id));
        
        return $substandards;
    }
}

/**
 * Get Standard Notations under a Sub Standard
 **/
if (!function_exists('was_standard_notations')){
    function was_standard_notations($standard_id){
        global $wpdb;
        
        $std_id = "sub_standards-".$standard_id;
        
        $notations = array();
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation where parent_id='%s'";
        
        $result = $wpdb->get_results($wpdb->prepare($query, $std_id));
        
        foreach ($result as $row){
                $notations[] = $row;
        }
        
        return $notations;
    }
}

/**
 * Get Substandard(s) by Notation
 **/
if (!function_exists('was_substandards_by_notation')) {
    function was_substandards_by_notation($notation){
        global $wpdb;
        
        $std = null;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE standard_notation = '%s'";
        
        $standard_notation = $wpdb->get_results($wpdb->prepare($query, $notation));
        
        if ($standard_notation){
            $substandard_id = $standard_notation[0]->parent_id;
            $std = was_hierarchical_substandards($substandard_id);
        }
        
        return $std;
    }
}

/**
 * Get Child Notations
 **/
if (!function_exists('was_child_notations')){
    function was_child_notations($notation_id){
        global $wpdb;
        
        $notation = "standard_notation-".$notation_id;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE parent_id = '%s'";
        
        $standard_notations = $wpdb->get_results($wpdb->prepare($query, $notation));
        
        return $standard_notations;
    }
}

/**
 * Get Core Standard by standard or substandard ID
 **/
if (!function_exists('was_corestandard_by_standard')){
    function was_corestandard_by_standard($parent_id){
        global $wpdb;
        
        $standard = null;
        $parent = explode("-",$parent_id);
        if ($parent[0]=="sub_standards") {
                $query = "SELECT * FROM {$wpdb->prefix}oer_sub_standards WHERE id = '%s'";
                $substandards = $wpdb->get_results($wpdb->prepare($query, $parent[1]));
                
                foreach($substandards as $substandard){
                        $standard = was_corestandard_by_standard($substandard->parent_id);
                }
        } else {
                $query = "SELECT * FROM {$wpdb->prefix}oer_core_standards WHERE id = '%s'";
                $standards = $wpdb->get_results($wpdb->prepare($query, $parent[1]));
                foreach($standards as $std){
                        $standard = $std;
                }
        }
        
        return $standard;
    }
}

/**
 * Get Standard By Id
 **/
if (!function_exists('was_standard_by_id')){
    function was_standard_by_id($id){
        global $wpdb;
        
        $std = null;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_core_standards WHERE id = %d";
        
        $standards = $wpdb->get_results($wpdb->prepare($query,$id));
        
        foreach($standards as $standard){
                        $std = $standard;
        }
        
        return $std;
    }
}

/**
 * Get Standard By Slug
 **/
if (!function_exists('was_standard_by_slug')){
    function was_standard_by_slug($slug){
        global $wpdb;
        
        $std = null;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_core_standards";
        
        $standards = $wpdb->get_results($query);
        
        foreach($standards as $standard){
            if (sanitize_title($standard->standard_name)===$slug)
                $std = $standard;
        }
        
        return $std;
    }
}

/**
 * Get SubStandard By Slug
 **/
if (!function_exists('was_substandard_by_slug')){
    function was_substandard_by_slug($slug){
        global $wpdb;
        
        $std = null;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_sub_standards";
        
        $substandards = $wpdb->get_results($query);
        
        foreach($substandards as $substandard){
                if (sanitize_title($substandard->standard_title)===$slug)
                        $std = $substandard;
        }
        
        return $std;
    }
}

/**
 * Get Core Standard by Notation
 **/
if (!function_exists('was_standard_by_notation')) {
    function was_standard_by_notation($notation){
        global $wpdb;
        
        $std = null;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE standard_notation = '%s'";
        
        $standard_notation = $wpdb->get_results($wpdb->prepare($query, $notation));
        
        if ($standard_notation){
            $substandard_id = $standard_notation[0]->parent_id;
            $substandard = was_parent_standard($substandard_id);
            
            if (strpos($substandard[0]['parent_id'],"core_standards")!==false){
                $pIds = explode("-",$substandard[0]['parent_id']);
                
                if (count($pIds)>1){
                    $parent_id=(int)$pIds[1];
                    $std = was_standard_by_id($parent_id);
                }
            }
        }
        
        return $std;
    }
}

/** Get Parent Standard **/
if (!function_exists('was_parent_standard')){
    function was_parent_standard($standard_id) {
        global $wpdb, $_oer_prefix;
        
        $stds = explode("-",$standard_id);
        $table = $stds[0];
        
        $prefix = substr($standard_id,0,strpos($standard_id,"_")+1);
        
        $table_name = $wpdb->prefix.$_oer_prefix.$table;
        
        $id = $stds[1];
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $table_name. " where id = %s" , $id ) , ARRAY_A);
        
        foreach($results as $result) {

            $stdrds = explode("-",$result['parent_id']);
            $tbl = $stdrds[0];
            
            $tbls = array('sub_standards','standard_notation');
            
            if (in_array($tbl,$tbls)){
                $results = was_parent_standard($result['parent_id']);
            }

        }
        return $results;
    }
}

/**
 * Get Parent Sub Standard by Notation
 **/
if (!function_exists('was_substandard_by_notation')) {
    function was_substandard_by_notation($notation) {
        global $wpdb;
        
        $std = null;
        
        $query = "SELECT * FROM {$wpdb->prefix}oer_standard_notation WHERE standard_notation = '%s'";
        
        $substandards = $wpdb->get_results($wpdb->prepare($query, $notation));
        
        foreach($substandards as $substandard){
                $std = $substandard;
        }
        
        return $std;
    }
}

// Get Hierarchical Substandards
if (!function_exists('was_hierarchical_substandards')){
    function was_hierarchical_substandards($substandard_id){
        $substandard=null;
        $substandards = null;
        $hierarchy = "";
        $ids = explode("-",$substandard_id);
        if (strpos($substandard_id,"sub_standards")!==false) {
            do {
                    
                $substandard = was_substandard_details($ids[1]);
                $ids = explode("-", $substandard['parent_id']);
                $substandards[] = $substandard;
                    
            } while(strpos($substandard['parent_id'],"sub_standards")!==false);
        }
        
        return $substandards;
    }
}

// Get Hierarchical Notations
if (!function_exists('was_hierarchical_notations')){
    function was_hierarchical_notations($notation_id){
        $notation=null;
        $notations = array();
        $hierarchy = "";
        $ids = explode("-",$notation_id);
        if (strpos($notation_id,"standard_notation")!==false) {
            do {
                $notation = was_notation_details($ids[1]);
                $ids = explode("-", $notation[0]['parent_id']);
                $notations[] = $notation;
            } while(strpos($notation[0]['parent_id'],"standard_notation")!==false);
        }
        return $notations;
    }
}

// Get Notation Details
if (!function_exists('was_notation_details')){
    function was_notation_details($notation_id){
        global $wpdb;
        $notations = null;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where id = %s" , $notation_id  ) , ARRAY_A);
        foreach ($results as $row){
            $notations = $row;
        }
        return $notations;
    }
}

// Get Substandard Details
if (!function_exists('was_substandard_details')){
    function was_substandard_details($substandard_id){
        global $wpdb;
        $substandards = null;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where id = %s" , $substandard_id  ) , ARRAY_A);
        foreach ($results as $row){
            $substandards = $row;
        }
        return $substandards;
    }
}

/**
 * Get Resources by notation
 **/
if (!function_exists('was_resources_by_notation')) {
    function was_resources_by_notation($notation_id) {
            
        $notation = "standard_notation-".$notation_id;
        
        //later in the request
        $args = array(
            'post_type'  => 'resource', //or a post type of your choosing
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                'key' => 'oer_standard',
                'value' => $notation,
                'compare' => 'like'
                )
            )
        );
        
        $query = new WP_Query($args);
        
        return $query->posts;
    }
}

if (!function_exists('was_custom_styles')) {
    function was_custom_styles(){
        ?>
        <style type="text/css">
            .substandards-template #content ul.oer-substandards > li:not(:active),
            .standards-template #content ul.oer-standards > li,
            .substandards-template #content ul.oer-notations > li,
            .notation-template #content ul.oer-subnotations > li { background:url(<?php echo WAS_URL."/images/arrow-right.png"; ?>) no-repeat top left; padding-left:28px; }
        </style>
        <?php
    }
}