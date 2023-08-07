<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/*********************************************************************************/	
// validate links without href but that include event handlers
/*********************************************************************************/	
function wp_ada_compliance_basic_validate_missing_href($content, $postinfo){

global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('missing_href', $wp_ada_compliance_basic_scanoptions)) return 1;	
	

$elements = $dom->find("a");

foreach ($elements as $element) {

// ignore 	
if(isset($element)){
$founderror = 0;
	// click events
	if ( ($element->getAttribute('href') == "" and !stristr($element->getAttribute('role'), 'link')) and
		($element->getAttribute('onclick') 
		 or $element->getAttribute('ondblclick') 
		 or $element->getAttribute('onmousedown') 
		 or $element->getAttribute('onmouseup')
		or $element->getAttribute('onkeypress') 
		 or $element->getAttribute('onkeydown') 
		 or $element->getAttribute('onkeyup')
		or $element->getAttribute('onmouseover') 
		or $element->getAttribute('onmouseout') 
		or $element->getAttribute('onmousemove')
		or $element->getAttribute('onfocus') 
		or $element->getAttribute('onblur'))) {
				
			$code = $element->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_href", $code))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_href", $wp_ada_compliance_basic_def['missing_href']['StoredError'], $code);
			

			
		}
	}
}
	return 1;
}
?>