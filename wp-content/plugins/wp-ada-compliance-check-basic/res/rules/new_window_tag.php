<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// target new window
/********************************************************************/	
function wp_ada_compliance_basic_validate_new_window_tag($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$striptarget ='false';
$report_filtered_errors = 'true';		
	
// check if being scanned
if(in_array('new_window_tag', $wp_ada_compliance_basic_scanoptions)) return 1;	

$links = $dom->find('a');
foreach ($links as $link) {
$imagealtiscompliant = 0;
	// check embeded image for compliance
	$nodes = $link->children();
		foreach ($nodes as $node) {
			if($node->tag == "img" or $node->tag == "i") {
				if(stristr($node->getAttribute('title'),"new window") 
                   or stristr($node->getAttribute('alt'),"new window") 
				   or stristr($node->getAttribute('aria-label'),"new window")
				   or stristr($node->getAttribute('title'),"new tab") 
                    or (stristr($node->getAttribute('alt'),"new window") 
				   or stristr($node->getAttribute('aria-label'),"new tab"))){
				$imagealtiscompliant = 1;
				}
			}
			}
		
		if(isset($link) 
	   and ($link->getAttribute('target') == "_blank" or stristr($link->getAttribute('onclick'),"window.open") or stristr($link->getAttribute('onclick'),"openwindow")) 
	   and !stristr($link->plaintext,"new window") 
	   and !stristr($link->plaintext,"new tab")
	   and !stristr($link->getAttribute('title'),"new tab") 
		and !stristr($link->getAttribute('aria-label'),"new tab")
	   and !stristr($link->getAttribute('title'),"new window")
		and !stristr($link->getAttribute('aria-label'),"new window")
	   and $imagealtiscompliant == 0) {
		    
	
			$newwindowtag = $link->outertext;
		
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"new_window_tag", $newwindowtag))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"new_window_tag", $wp_ada_compliance_basic_def['new_window_tag']['StoredError'], $newwindowtag);
			
			
	
		}
}
	return 1;
}

?>