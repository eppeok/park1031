<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/*********************************************************************************/	
// validate elments with onclick but not onkeypress
/*********************************************************************************/	
function wp_ada_compliance_basic_validate_missing_onkeypress($content, $postinfo){

global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('missing_onkeypress', $wp_ada_compliance_basic_scanoptions)) return 1;	
	

$elements = $dom->find("*");

foreach ($elements as $element) {

// ignore 	
if(isset($element)){
$founderror = 0;
	// click events
	if (($element->getAttribute('ondblclick') 
		 or $element->getAttribute('onmousedown') or $element->getAttribute('onmouseup'))
		and !$element->getAttribute('onkeypress') and !$element->getAttribute('onkeydown') and !$element->getAttribute('onkeyup')) $founderror = 1;
	
	// focus and blur events
	if (($element->getAttribute('onmouseover') or $element->getAttribute('onmouseout') or $element->getAttribute('onmousemove'))
		and !$element->getAttribute('onfocus') and !$element->getAttribute('onblur')) $founderror = 1;
	
	if($founderror == 1){
				
			$code = $element->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_onkeypress", $code))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_onkeypress", $wp_ada_compliance_basic_def['missing_onkeypress']['StoredError'], $code);
			

			
		}
	}
}
	return 1;
}
?>