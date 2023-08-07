<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate alt text content
/********************************************************************/
function wp_ada_compliance_basic_validate_img_alt_invalid($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	


// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$remove_invalid_img_alt_text = 'false';	
$report_filtered_errors = 'true';	
	
// check if being scanned
if(in_array('img_alt_invalid', $wp_ada_compliance_basic_scanoptions)) return 1;			

// check svgs
$svgs = $dom->find('svg');
foreach ($svgs as $svg) {
if (isset($svg)) {
  $alt = wp_ada_compliance_basic_check_svg_img_alt_text($svg, $dom);
   $error = wp_ada_compliance_basic_check_image_alt_validity($alt,'',$dom);
    
    
if($error > 0){	 

$imagecode = $svg->outertext;

// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"img_alt_invalid", $imagecode))
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"img_alt_invalid", $wp_ada_compliance_basic_def['img_alt_invalid']['StoredError'], $imagecode);

}
}  
}
    
// check images    
$images = $dom->find('img');
foreach ($images as $image) {
	
if (isset($image)) {

// check imag alt
$alt = $image->getAttribute('alt');
if($image->getAttribute('title') != '') $alt .= $image->getAttribute('title');    
$error = wp_ada_compliance_basic_check_image_alt_validity($alt, $image,$dom);

    
if($error > 0){	 

$imagecode = $image->outertext;

// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"img_alt_invalid", $imagecode))
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"img_alt_invalid", $wp_ada_compliance_basic_def['img_alt_invalid']['StoredError'], $imagecode);

}
}
}
return 1;
}

/**********************************************************
get svg alt image
**********************************************************/
function wp_ada_compliance_basic_check_svg_img_alt_text($svg, $dom){
$alt = '';
if(is_array($svg)) {   
    
if(count($svg) == 0) return 1;     
    
$title = $svg[0]->find('title');

if(count($title) == 0) $alt = $title[0]->innertext;
if($alt == '') $alt = wp_ada_compliance_basic_get_aria_values($dom, $svg[0], 'aria-labelledby'); 
if($alt == '') $alt = wp_ada_compliance_basic_get_aria_values($dom, $svg[0], 'aria-describedby');

}else{
$title = $svg->find('title');

if(count($title) > 0) $alt = $title[0]->innertext;
if($alt == '') $alt = wp_ada_compliance_basic_get_aria_values($dom, $svg, 'aria-labelledby'); 
if($alt == '') $alt = wp_ada_compliance_basic_get_aria_values($dom, $svg, 'aria-describedby'); 
    
}
    
return $alt;    
}
   
/**********************************************************
check if the image alt is valid
**********************************************************/   
function wp_ada_compliance_basic_check_image_alt_validity($alt,$image, $dom){
$error = 0;

 
    // exclude check if linked to larger image
//if($image == '' or $image->parent()->tag != 'a' or !strstr($image->parent()->href,substr($image->getAttribute('src'),-4,4))){ 
if($image == '' or !wp_ada_compliance_basic_check_image_wrapped_in_anchor($dom, $image->getAttribute('alt'))){  
if(stristr($alt,__('image of','wp-ada-compliance-basic'))) $error = 2;
if(stristr($alt,__('graphic of','wp-ada-compliance-basic')))  $error = 2;
if(stristr($alt,__('photo of','wp-ada-compliance-basic'))) $error = 2;
}    
    
// cehck for file name    
if (stristr($alt,__('.jpg','wp-ada-compliance-basic')) 
or stristr($alt,__('.png','wp-ada-compliance-basic')) 
    or stristr($alt,__('.svg','wp-ada-compliance-basic')) 
    or stristr($alt,__('.jpeg','wp-ada-compliance-basic'))
or stristr($alt,__('.gif','wp-ada-compliance-basic'))  
or stristr($alt,__('DSCN','wp-ada-compliance-basic')) 
    or stristr($alt,__('DSCF','wp-ada-compliance-basic')) 
or stristr($alt,__('_','wp-ada-compliance-basic'))
or preg_match('/^\d[a-zA-Z]\d[a-zA-Z][\w\-_]+\d$/',$alt)
or preg_match('/^[a-zA-Z]+\d$/',$alt)) $error = 1; 
        
    
if(strtolower($alt) == __('alt""','wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('alt=""','wp-ada-compliance-basic')) $error = 1;
if(stristr($alt,__('click this','wp-ada-compliance-basic'))) $error = 1;
if(stristr($alt,__('link to','wp-ada-compliance-basic'))) $error = 1;
if(stristr($alt,__('image001','wp-ada-compliance-basic'))) $error = 1;
if(stristr($alt,__('Featured Image','wp-ada-compliance-basic'))) $error = 1;
if(strstr($alt,__('IMG ','wp-ada-compliance-basic'))) $error = 1;
if(strtolower($alt) == __('spacer','wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('image','wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('picture','wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('logo','wp-ada-compliance-basic')) $error = 1;
if($alt == __('OLYMPUS DIGITAL CAMERA','wp-ada-compliance-basic')) $error = 1;
if(stristr($alt,__('UNTITLED','wp-ada-compliance-basic'))) $error = 1;
    
if(trim($alt) == '*') $error = 1;
    
for($i=1; $i < 10; $i++) {
if(strtolower($alt) == __('picture '.$i,'wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('image '.$i,'wp-ada-compliance-basic')) $error = 1;

if(strtolower($alt) == __('spacer '.$i,'wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('000'.$i,'wp-ada-compliance-basic')) $error = 1;
if(strtolower($alt) == __('intro#'.$i,'wp-ada-compliance-basic')) $error = 1;

}

return $error;

}
?>