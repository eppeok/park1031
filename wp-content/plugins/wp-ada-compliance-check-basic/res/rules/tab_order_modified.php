<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// check for tab order changed using tabindex 
/********************************************************************/	
function wp_ada_compliance_basic_validate_tab_order_modified($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('tab_order_modified', $wp_ada_compliance_basic_scanoptions)) return 1;		

$tags = array('a','input','select','textarea','button','datalist', 'output', 'area');	
	
foreach ($tags as $tag) {		
$elements = $dom->find($tag);	
	
foreach ($elements as $element) {
			 	 
	
if (isset($element) and $element->getAttribute('tabindex') > 0) {  	

$errorcode = $element->outertext;

// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"tab_order_modified", $errorcode)){
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"tab_order_modified", $wp_ada_compliance_basic_def['tab_order_modified']['StoredError'],  $errorcode);
}

}
}
}
return 1;
} 
?>