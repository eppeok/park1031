<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// look for elementor galleries
/********************************************************************/	
function wp_ada_compliance_basic_validate_elementor_gallery($content, $postinfo){
global $wp_ada_compliance_basic_def;
		
if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/plugin.php' );    
if(!is_plugin_active('elementor/elementor.php')) return;       
    
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('elementor_gallery', $wp_ada_compliance_basic_scanoptions)) return 1;

$dom = str_get_html($content);
    
$div = $dom->find('div[class=elementor-gallery__container]');
	
foreach ($div as $element) {			
	
if(isset($element)){    

// save error
	echo $elementor_gallery_errorcode = $element->outertext;
	
	
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"elementor_gallery", $elementor_gallery_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "elementor_gallery", $wp_ada_compliance_basic_def['elementor_gallery']['StoredError'], $elementor_gallery_errorcode);
		}
		
		
}
}

}
?>