<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/****************************************
check duplicate page titles
****************************************/
function wp_ada_compliance_basic_validate_duplicate_title($postinfo){
global $wp_ada_compliance_basic_def;
if($postinfo['title'] == "") return 1;
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('duplicate_title', $wp_ada_compliance_basic_scanoptions)) return 1;	

if(wp_ada_compliance_basic_title_single_check($postinfo['title'])){	

	
	$duplicate_title_errorcode = '<title>'.$postinfo['title'].'</title>';
	// save error
	if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"duplicate_title", $duplicate_title_errorcode)){	
		$insertid = wp_ada_compliance_basic_insert_error($postinfo,"duplicate_title", $wp_ada_compliance_basic_def['duplicate_title']['StoredError'], $duplicate_title_errorcode);
		}
	
}
}
	
/*******************************************
scan page titles
******************************************/
function wp_ada_compliance_basic_title_single_check($title){
global $wpdb;
	
// get post types to be scanned
$variables = get_option('wp_ada_compliance_basic_posttypes',array('page','post'));
$check_attachments = get_option('wp_ada_compliance_basic_attachmenttitles','false');	

if(!is_array($variables)) return false;
	
if($check_attachments == 'false'){
$attach_loc = array_search('attachment',$variables);
unset($variables["$attach_loc"]); 	
}
    
if(!is_array($variables) or count($variables) == 0) {
return 0;	
}   
	
$how_many = count($variables);
$placeholders = array_fill(0, $how_many, '%s');
$format = implode(', ', $placeholders);
	
array_unshift($variables,$title);	
array_unshift($variables,"trash");
array_unshift($variables,"tao_sc_publish");	
    array_unshift($variables,"cus_sc_publish");	
array_unshift($variables,"auto-draft");
	array_unshift($variables,"draft");
array_unshift($variables,"revision");
	
$query = 'SELECT COUNT(*) FROM '.$wpdb->prefix.'posts where post_type NOT IN(%s) and post_status NOT IN(%s,%s, %s, %s, %s) and post_title = %s and post_type IN('.$format.')';

$total = $wpdb->get_var( $wpdb->prepare( $query,$variables));
if($total > 1) return $total;
else return 0;
}
?>