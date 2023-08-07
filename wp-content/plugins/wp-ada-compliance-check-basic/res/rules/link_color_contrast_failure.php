<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// look for links without enough contrast between the text around them
/********************************************************************/	
function wp_ada_compliance_basic_validate_link_color_contrast_failure($content, $postinfo){
global $wp_ada_compliance_basic_def;
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());
	
// check if being scanned
if(in_array('link_color_contrast_failure', $wp_ada_compliance_basic_scanoptions)) return 1;
    
if($postinfo['type'] != 'css')	{
$dom = str_get_html($content); 
        
// check links in content for style tags changing the color of links
    
$elements = $dom->find('a[style], a span[style]');
//$elements = $dom->find('a[style]');    
$textcolor = get_option('wp_ada_compliance_basic_foreground_color', '#000000'); 
foreach ($elements as $element) {
$foreground = '';

if(stristr($element->getAttribute('style'), 'color:')){	   
    
// get link color
preg_match('/[\s|\"|\']*[^-]color:\s*(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\)\s*(!important)*)/i',' '.$element->getAttribute('style'), $matches, PREG_OFFSET_CAPTURE);
if(isset($matches[1][0]) and $matches[1][0] != "") {
    
$foreground = $matches[1][0];    

if(wp_ada_compliance_basic_color_diff($foreground, $textcolor)){	
            
// save error
if($element->tag == 'span') $link_color_contrast_failure_errorcode = $element->parent()->outertext;
else $link_color_contrast_failure_errorcode = $element->outertext;
		
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"link_color_contrast_failure", $link_color_contrast_failure_errorcode)){

$insertid = wp_ada_compliance_basic_insert_error($postinfo, "link_color_contrast_failure", $wp_ada_compliance_basic_def['link_color_contrast_failure']['StoredError'], $link_color_contrast_failure_errorcode);
}
}
}
}
}
}
}


/*********************************************************
check the color contrast
*********************************************************/
function wp_ada_compliance_basic_color_diff($color1, $color2){

//convert color names to hex
$color1 = trim(wp_ada_compliance_basic_convert_color_names($color1));		
$color2 = trim(wp_ada_compliance_basic_convert_color_names($color2)); 

// convert hex to rgb	
$color1 = wp_ada_compliance_basic_hexToRgb($color1);
$color2 = wp_ada_compliance_basic_hexToRgb($color2);

$dif = wp_ada_compliance_basic_test_color_diff($color1, $color2);

// failed
if($dif < 3) {
	 return 1;	
}

return 0;	
}
?>