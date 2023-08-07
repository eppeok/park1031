<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// image map hot spot missing alt text
/********************************************************************/	
function wp_ada_compliance_basic_validate_imagemap_missing_alt_text($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('imagemap_missing_alt_text', $wp_ada_compliance_basic_scanoptions)) return 1;	

$maps = $dom->find('map');

foreach ($maps as $map) {	

$mapcode = $map->outertext;

$areas = $map->find('area');

foreach ($areas as $area) {
	if (isset($area) and ($area->getAttribute('alt') == "")) {
	
		// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"imagemap_missing_alt_text", $mapcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"imagemap_missing_alt_text",$wp_ada_compliance_basic_def['imagemap_missing_alt_text']['StoredError'], $mapcode);	
			
			
		}

	}
		
}
return 1;
}

?>