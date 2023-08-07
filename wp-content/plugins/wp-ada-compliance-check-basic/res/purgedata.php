<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/**********************************************
// purge data
**********************************************/
function wp_ada_compliance_basic_purge() {
$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');
	if ( !current_user_can( $settingsuser ) ) return 1;
	global $wpdb;
	$wpdb->query( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic');
	$_SESSION['my_ada_important_notices'] = __('Report data has been reset.','wp-ada-compliance-basic');
}



/********************************************
// purge records no longer being tracked
*********************************************/
function  wp_ada_compliance_basic_purge_records() {
global $wpdb;

if ( !defined( 'DOING_CRON' ) and !current_user_can( "edit_pages" ) ) return 1;
$checked = get_option('wp_ada_compliance_basic_posttypes', array('page','post'));	
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// purge draft or trashed records
wp_ada_compliance_basic_purge_trashed_or_draft_post_records();	
	

// purge rules no longer being scanned
if(is_array($wp_ada_compliance_basic_scanoptions) and count($wp_ada_compliance_basic_scanoptions) > 0){
$rule_count = count($wp_ada_compliance_basic_scanoptions);
$ruleplaceholders = array_fill(0, $rule_count, '%s');
$ruleformat = implode(', ', $ruleplaceholders);	

$query = 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE errorcode in('.$ruleformat.') ';	
$wpdb->query( 
	$wpdb->prepare( $query, $wp_ada_compliance_basic_scanoptions));	
}

$table = $wpdb->prefix.'posts';

// purge posts		
$results = $wpdb->get_results($wpdb->prepare("SELECT distinct(post_type) FROM $table where post_status = %s or post_type = %s", array("publish", "attachment")), ARRAY_A);	
	
if($results){
foreach($results as $row){ 
	 if(!is_array($checked) or is_array($checked) and !in_array($row['post_type'], $checked))  {
		
		$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE type= %s ', array($row['post_type']) ) );
	 }
}
}
}

/**********************************************
// purge deleted or draft post records
**********************************************/
function wp_ada_compliance_basic_purge_trashed_or_draft_post_records(){
global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT ID FROM ".$wpdb->prefix."posts where post_status = %s or post_status = %s or post_status = %s or post_status = %s or post_status = %s or post_type = %s or post_status = %s", "trash", "tao_sc_publish","tao_sc_publish","draft","auto-draft","revision","private"), ARRAY_A);	
	
foreach($results as $row){ 	
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE postid = %d ', $row['ID']) );
}
	
}


/**********************************************
// purge deleted post when deleted from trash bin
**********************************************/
function wp_ada_compliance_basic_delete_post( $null, $post, $force_delete ) { 
	global $wpdb;
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE postid = %d and type = %s ', $post->ID, $post->post_type) );
	

}

/*********************************************************
remove deleted post records
*******************************************************/
function wp_ada_compliance_basic_remove_deleted_posts($post_id) {
global $wpdb, $post_type;

// after validation is complete remove previous errors that were not found
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE postid = %d  and type = %s', $post_id, $post_type) );

}


/*********************************************************
remove error records if post no longer exists
*******************************************************/
function wp_ada_compliance_basic_remove_records_if_post_no_longer_exists($post_id) {
global $wpdb;

// after validation is complete remove previous errors that were not found
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic WHERE postid = %d ', $post_id) );

}
?>