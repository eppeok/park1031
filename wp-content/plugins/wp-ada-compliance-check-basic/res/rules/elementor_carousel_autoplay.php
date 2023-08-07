<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// look for elementor image carousel set to autoplay
/********************************************************************/	
function wp_ada_compliance_basic_validate_elementor_carousel_autoplay($content, $postinfo){
global $wp_ada_compliance_basic_def;
    
if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/plugin.php' );    
if(!is_plugin_active('elementor-pro/elementor-pro.php')) return;       
		
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());
	
// check if being scanned
if(in_array('elementor_carousel_autoplay', $wp_ada_compliance_basic_scanoptions)) return;
    

$dom = str_get_html($content);
   
$elements = $dom->find('[class*=elementor-widget-image-carousel]');

foreach ($elements as $element) {			
	
if(isset($element)){    

if(strstr($element->getAttribute('data-settings'),'&quot;autoplay&quot;:&quot;yes&quot;') 
   or strstr($element->getAttribute('data-settings'),'"autoplay":"yes"')){    

        
$elementor_carousel_autoplay_errorcode = $element->outertext;	

if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"elementor_carousel_autoplay", $elementor_carousel_autoplay_errorcode)){

$insertid = wp_ada_compliance_basic_insert_error($postinfo, "elementor_carousel_autoplay", $wp_ada_compliance_basic_def['elementor_carousel_autoplay']['StoredError'], $elementor_carousel_autoplay_errorcode);
}		
}

}
}
}
?>