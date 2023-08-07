<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate meta refresh attributes that reload the page or redirect to a new location after a timeout
/********************************************************************/
function wp_ada_compliance_basic_validate_meta_refresh_use($content, $postinfo){

global $wp_ada_compliance_basic_def;


$dom = str_get_html($content);	
		
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());

// check if being scanned
if(in_array('meta_refresh_use', $wp_ada_compliance_basic_scanoptions)) return 1;

$metas = $dom->find('meta');
foreach ($metas as $meta) {
   if(isset($meta) and stristr($meta->getAttribute('http-equiv'), 'refresh') ){
	$attributes = explode(';',$meta->getAttribute('content'));
	
	if(((isset($attributes[1]) and stristr($attributes[1],'url=')) 
	  and (isset($attributes[0]) and $attributes[0] >= '1'))
	
	  or ((!isset($attributes[1]) or !stristr($attributes[1],'url=')) and $meta->getAttribute('content') >= '0' )
	){
	
$code =  $meta->outertext;
		
// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"meta_refresh_use", $code)){
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"meta_refresh_use", $wp_ada_compliance_basic_def['meta_refresh_use']['StoredError'], $code);
}
		
		


}
}
}
}	
?>
