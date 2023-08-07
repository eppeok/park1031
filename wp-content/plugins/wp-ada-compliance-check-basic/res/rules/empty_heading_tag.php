<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/
 //VALIDATE EMPTY TAGS
/********************************************************************/
function wp_ada_compliance_basic_validate_empty_heading_tag($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$striptags = 'false';
$report_filtered_errors = 'true';		
	
// check if being scanned
if(in_array('empty_heading_tag', $wp_ada_compliance_basic_scanoptions)) return 1;	
	
for ($i = 1; $i <= 6; $i++) {

	$headings = $dom->find('h'.$i);
	foreach ($headings as $heading) {

 $headingcode = $heading->outertext;

      if ((str_ireplace(array(' ','&nbsp;','-','_'),'',htmlentities(trim($heading->plaintext))) == "" or str_ireplace(array(' ','&nbsp;','-','_'),'',trim($heading->plaintext))== "")  and !preg_match('#<img(\S|\s)*alt=(\'|\")(\w|\s)(\w|\s|\p{P}|\(|\)|\p{Sm}|~|`|’|\^|\$)+(\'|\")#',$headingcode)) {
				
	
				// save error
				if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"empty_heading_tag", $headingcode))
				$insertid = wp_ada_compliance_basic_insert_error($postinfo,"empty_heading_tag", $wp_ada_compliance_basic_def['empty_heading_tag']['StoredError'], $headingcode);
				
	
			}
		
	}
}
	
return 1;
} 
?>