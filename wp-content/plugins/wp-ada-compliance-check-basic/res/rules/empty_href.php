<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************	
check for empty anchor tag	
********************************************************************/	
function wp_ada_compliance_basic_validate_empty_href($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
    
  
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());


// check if being scanned
if(in_array('empty_href', $wp_ada_compliance_basic_scanoptions)) return 1;	

$links = $dom->find('a');
foreach ($links as $link) {
	
$skipaccordian = 0;
    
// check if link is in an accordian
 if(isset($link->parent()->tag) and $link->parent()->getAttribute('role') == 'tab') {
  $skipaccordian = 1;   
 }
	
if ($link->hasAttribute('href') and $link->getAttribute('href') == "" and $skipaccordian == 0) {
			
		$atagcode = $link->outertext;
		
			
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"empty_href", $atagcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"empty_href",$wp_ada_compliance_basic_def['empty_href']['StoredError'], $atagcode);
						
				
				
				
		}
}
return 1;
}
?>