<?php 
/*
Plugin - WP ADA Compliance Check
functions to process post scans
*/
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************
validate post content when saved
*********************************************************/
function wp_ada_compliance_basic_validate_ada_post_compliance( $post_id ) {
	
// remove error records if post no longer exists	
if(get_post_status($post_id) == false) {
wp_ada_compliance_basic_remove_records_if_post_no_longer_exists($post_id);	
}	

if ( !current_user_can( "edit_pages" ) ) return 1;
$post_types = get_option('wp_ada_compliance_basic_posttypes',array('page','post'));

$post_object = get_post( $post_id );
	
if(!is_object($post_object)) return;
	
$postinfo['type'] = $post_object->post_type;

// ignore posts not being checked
if (!is_array($post_types) or !in_array($postinfo['type'], $post_types)) return 1;
    
	    // ignore drafts & revisions 
	$ignored_status = array("cus_sc_publish","tao_sc_publish","draft","auto-draft","revision","private");
	if(in_array($post_object->post_status,$ignored_status)) return 1;

	if($postinfo['type'] == 'attachment'){
	if(strstr($post_object ->post_mime_type,'image/')) {	
		$postinfo['content'] = get_post_meta($post_id, '_wp_attached_file', true); 
		$postinfo['postid'] = $post_id;
		$postinfo['title'] = $post_object->post_title;
		$postinfo['scantype'] = 'onsave';	
		$postinfo['taxonomy'] = '';
		$postinfo['wpget'] = ''; // when saving datbase scan only is conducted to reduce confusion		
	}
	else return 1;
	}
	else{
		$postinfo['excerpt'] = $post_object->post_excerpt;	
		$postinfo['content'] = $post_object->post_content;
		$postinfo['postid'] = $post_id;
		$postinfo['title'] = $post_object->post_title;
		$postinfo['scantype'] = 'onsave';	
		$postinfo['taxonomy'] = '';
		$postinfo['wpget'] = ''; // when saving datbase scan only is conducted to reduce confusion	
        
            // render elementor content    
$postinfo['content'] = wp_ada_compliance_basic_check_elementor_content($postinfo['content'],$postinfo['postid']);
        
// render beaver builder content    
$postinfo['content'] = wp_ada_compliance_basic_check_beaver_builder_content($postinfo['content'],$postinfo['postid'], $postinfo['type']);  
       
}
	
// set record check flag on previous error records
	wp_ada_compliance_basic_remove_corrected_posts($postinfo['scantype'], $postinfo['postid'], $postinfo['type'], 1);

	// scan content 
	wp_ada_compliance_basic_validate_post($postinfo);
	
	// remove records that have been corrected
	wp_ada_compliance_basic_remove_corrected_posts($postinfo['scantype'], $postinfo['postid'], $postinfo['type'], 2);
    
    // get the error notices
$_SESSION['my_ada_notices'] = wp_ada_compliance_basic_get_error_list_for_post($postinfo['postid'], $postinfo['type']);
	
}

/********************************************************
manual or cron auto scan of post content
*********************************************************/
function wp_ada_compliance_basic_scan_ada_compliance_post($adacounter) {
	global $wpdb, $wp_ada_compliance_basic_error;

	if (!current_user_can( "edit_pages" ) ) return 1;
	
	
	// get post types to be scanned
	$posttypes = get_option('wp_ada_compliance_basic_posttypes',array('page','post'));
	
	if(!is_array($posttypes)) return 1;
		
	$how_many = count($posttypes);
	$placeholders = array_fill(0, $how_many, '%s');
	$format = implode(', ', $placeholders);
	
	array_unshift($posttypes,"trash");
	array_unshift($posttypes,"auto-draft");
    array_unshift($posttypes,"draft");
    array_unshift($posttypes,"private");
	
	if($adacounter == 0)
	$query = 'SELECT * FROM '.$wpdb->prefix.'posts where post_status NOT IN(%s, %s,%s, %s) and post_type IN('.$format.') order by post_date  LIMIT 15';
   	elseif($adacounter == 1)
	$query = 'SELECT * FROM '.$wpdb->prefix.'posts where post_status NOT IN(%s, %s,%s, %s) and post_type IN('.$format.') order by post_date  LIMIT 5 OFFSET 0';
	elseif($adacounter == 2)
	$query = 'SELECT * FROM '.$wpdb->prefix.'posts where post_status NOT IN(%s, %s,%s, %s) and post_type IN('.$format.') order by post_date  LIMIT 5 OFFSET 5';
	elseif($adacounter == 3)
	$query = 'SELECT * FROM '.$wpdb->prefix.'posts where post_status NOT IN(%s, %s,%s, %s) and post_type IN('.$format.') order by post_date  LIMIT 5 OFFSET 10';
	else return;
	
		$results = $wpdb->get_results( $wpdb->prepare( $query, $posttypes), ARRAY_A );
        
		foreach ( $results as $row ) {
            
            wp_ada_compliance_basic_set_php_ini_Settings(1);

			$postinfo['content'] = $row["post_content"];
			$postinfo['excerpt'] = $row["post_excerpt"];
			$postinfo['postid'] = $row['ID'];
			$postinfo['title'] = $row[ "post_title"];
			$postinfo['type'] = $row['post_type'];
		    $postinfo['scantype'] = 'fullscan';	
			$postinfo['taxonomy'] = '';
			$postinfo['wpget'] = '';
						
		            // render elementor content    
$postinfo['content'] = wp_ada_compliance_basic_check_elementor_content($postinfo['content'],$postinfo['postid']);
        
// render beaver builder content    
$postinfo['content'] = wp_ada_compliance_basic_check_beaver_builder_content($postinfo['content'],$postinfo['postid'], $postinfo['type']); 	
            
$url = esc_url_raw(get_permalink($postinfo['postid']));    
$args = array('timeout'  => 10,
'redirection' => 10,
'sslverify'   => false, 
'user-agent' => 'WP ADA COMPLIANCE CRAWLER'); 
$response = wp_remote_get($url, $args);	  
$response_code = wp_remote_retrieve_response_code($response);
if($response_code == '200')	{
$postinfo['fullcontent'] = wp_remote_retrieve_body($response); 
}        

// set record check flag on previous error records
wp_ada_compliance_basic_remove_corrected_posts($postinfo['scantype'], $postinfo['postid'], $postinfo['type'], 1);

// scan content database content
wp_ada_compliance_basic_validate_post($postinfo);

// remove records that have been corrected
wp_ada_compliance_basic_remove_corrected_posts($postinfo['scantype'], $postinfo['postid'], $postinfo['type'], 2);

}
 
if(count($results) < 5 or $adacounter == 3) echo 'complete';
}

/*********************************************************
remove corrected error
*******************************************************/
function wp_ada_compliance_basic_remove_corrected_posts($scantype, $post_id, $type, $pre=1) {
global $wpdb;
	

if($pre == 1){
// set record flag before validating content	
$wpdb->query($wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic SET recordcheck = %d WHERE postid = %d and type = %s', 0, $post_id, $type) );
}elseif($pre == 2){
// after validation is complete remove previous errors that were not found
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE postid = %d  and type = %s and recordcheck = %d', $post_id, $type, 0) );
}
}
?>