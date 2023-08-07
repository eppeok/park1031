<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// look for elementor toc widgets
/********************************************************************/	
function wp_ada_compliance_basic_validate_elementor_toc($content, $postinfo){
global $wp_ada_compliance_basic_def;
    
if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/plugin.php' );    
if(!is_plugin_active('elementor-pro/elementor-pro.php')) return;       
		
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());
	
// check if being scanned
if(in_array('elementor_toc', $wp_ada_compliance_basic_scanoptions)) return 1;

$dom = str_get_html($content);
    
$div = $dom->find('div');
	
foreach ($div as $element) {			
	
if(isset($element)){   
       if(strstr($element->getAttribute('class'),'elementor-widget-table-of-contents')){

// save error
	$elementor_toc_errorcode = $element->outertext;
	
	
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"elementor_toc", $elementor_toc_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "elementor_toc", $wp_ada_compliance_basic_def['elementor_toc']['StoredError'], $elementor_toc_errorcode);
		}
		

}
}
}
}
?>