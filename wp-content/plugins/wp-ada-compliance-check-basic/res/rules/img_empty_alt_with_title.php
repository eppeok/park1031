<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// image empty alt text with title or non empty aria label attribute
/********************************************************************/	
function wp_ada_compliance_basic_validate_img_empty_alt_with_title($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('img_empty_alt_with_title', $wp_ada_compliance_basic_scanoptions)) return 1;		
	
$images = $dom->find('img');
foreach ($images as $image) {

if (isset($image) and ($image->hasAttribute('alt') and $image->getAttribute('alt') == "") and ($image->getAttribute('title') != "" or $image->getAttribute('aria-label') != "")) {
			
			$imagecode = $image->outertext;
			
			// ignore certain images
			if(wp_ada_compliance_basic_ignore_plugin_issues($imagecode))	goto img_empty_alt_with_titlebottom;
			
			//ignore if inside caption with valid text
			if(wp_ada_compliance_basic_ignore_inside_valid_caption($imagecode, $content)) goto img_empty_alt_with_titlebottom;
	
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"img_empty_alt_with_title", $imagecode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"img_empty_alt_with_title", $wp_ada_compliance_basic_def['img_empty_alt_with_title']['StoredError'], $imagecode);
			

		
		
		}
	img_empty_alt_with_titlebottom:
}
	return 1;
} 

?>