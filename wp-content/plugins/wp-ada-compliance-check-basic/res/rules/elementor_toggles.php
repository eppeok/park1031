<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// look for elementor toggle of accordion widget
/********************************************************************/	
function wp_ada_compliance_basic_validate_elementor_toggles($content, $postinfo){
global $wp_ada_compliance_basic_def;
    
if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/plugin.php' );    
if(!is_plugin_active('elementor/elementor.php')) return;       
		
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());
	
// check if being scanned
if(in_array('elementor_toggles', $wp_ada_compliance_basic_scanoptions)) return;
    

$dom = str_get_html($content);
   
$div = $dom->find('div[class*=elementor-widget-accordion], div[class*=elementor-widget-toggle], div[class*=elementor-widget-tabs]');

foreach ($div as $element) {			
	
if(isset($element)){    

$elementor_toggles_errorcode = $element->outertext;

if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"elementor_toggles", $elementor_toggles_errorcode)){

$insertid = wp_ada_compliance_basic_insert_error($postinfo, "elementor_toggles", $wp_ada_compliance_basic_def['elementor_toggles']['StoredError'], $elementor_toggles_errorcode);
}	
}
}
}
?>