<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// image missing alt text
/********************************************************************/	
function wp_ada_compliance_basic_validate_img_empty_alt($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('img_empty_alt', $wp_ada_compliance_basic_scanoptions)) return 1;		
	
$tags = array('img', 'input', 'svg');	
	
foreach ($tags as $tag) {		
$elements = $dom->find($tag);	
	
foreach ($elements as $element) {

if (($element->tag == 'img' and $element->hasAttribute('alt') and $element->getAttribute('alt') == "" and $element->getAttribute('role') != "presentation" and $element->getAttribute('aria-hidden') != "true")
	or  ($element->tag == 'input' 
		 and $element->hasAttribute('alt') and $element->getAttribute('type') == "image" and $element->getAttribute('alt') == "")
   ) {
			
		
			$imagecode = $element->outertext;
			
			// ignore certain images
			if(wp_ada_compliance_basic_ignore_plugin_issues($imagecode))	goto img_empty_altbottom;
			
			// ignore images with captions
			if(wp_ada_compliance_basic_ignore_inside_valid_caption($imagecode, $content)) goto img_empty_altbottom;
	
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"img_empty_alt", $imagecode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"img_empty_alt", $wp_ada_compliance_basic_def['img_empty_alt']['StoredError'], $imagecode);
			

		
}
	img_empty_altbottom:
		}
	
}
	return 1;
} 
?>