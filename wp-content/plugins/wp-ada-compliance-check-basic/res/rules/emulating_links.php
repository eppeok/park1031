<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
	
/********************************************************************/	
// look for tags with onclick used to emulate links
/********************************************************************/	
function wp_ada_compliance_basic_validate_emulating_links($content, $postinfo){
		
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$wp_ada_compliance_correct_links = 'false';	
$report_filtered_errors = 'true';		
	
// check if being scanned
if(in_array('emulating_links', $wp_ada_compliance_basic_scanoptions)) return 1;
			
$elements = $dom->find('*');
	
foreach ($elements as $element) {
	if(($element->getAttribute('onclick') or $element->getAttribute('ondblclick') 
		or $element->getAttribute('onmousedown') or $element->getAttribute('onmouseup')
		or $element->getAttribute('onkeypress') or $element->getAttribute('onkeydown') 
		or $element->getAttribute('onkeyup') or $element->getAttribute('onmouseover') 
		or $element->getAttribute('onmouseout') or $element->getAttribute('onmousemove')
		or $element->getAttribute('onfocus') or $element->getAttribute('onblur')) 
	   and $element->tag != 'a' 
	   and $element->tag != 'button' 
	   and $element->tag != 'input' 
	   and $element->tag != 'select' 
	   and $element->tag != 'textarea' 
	   and $element->tag != 'area' 
	   and $element->tag != 'datalist' 
	   and $element->tag != 'output' 
	   and (!$element->getAttribute('role')
	   or !$element->hasAttribute('tabindex'))
	  ){
					
			$ahtagcode = $element->outertext;
			
		
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"emulating_links", $ahtagcode)){
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"emulating_links", $wp_ada_compliance_basic_def['emulating_links']['StoredError'],  $ahtagcode);
			}
			

		
		}
}

	return 1;
} 

?>