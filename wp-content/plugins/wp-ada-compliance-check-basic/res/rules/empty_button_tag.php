<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************	
check for empty button tag	
********************************************************************/	
function wp_ada_compliance_basic_validate_empty_button_tag($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	

// check if being scanned
if(in_array('empty_button_tag', $wp_ada_compliance_basic_scanoptions)) return 1;	

$buttons = $dom->find('button');
foreach ($buttons as $button) {
$svgalt = 1; 
$image = $button->find('img');
$input = $button->find('input');
$svg = $button->find('svg');
$i = $button->find('i');
if(isset($svg[0])) {
$meta = $svg[0]->find('metadata');
    
if(isset($meta[0]))     
$metatext = $meta[0]->innertext;
else $metatext = '';

    $svgalt = wp_ada_compliance_basic_check_svg_img_alt_text($svg[0], $dom);
}
    
if ((str_ireplace(array(' ','&nbsp;','-','_'),'',trim($button->plaintext)) == "" or ($svgalt == '' and trim($metatext)==trim($button->plaintext)))
	and $button->getAttribute('aria-label') == "" 
	and $button->getAttribute('title') == ""
    and wp_ada_compliance_basic_get_aria_values($dom, $button, 'aria-labelledby') == ''
    and wp_ada_compliance_basic_get_aria_values($dom, $button, 'aria-describedby') == ''
) {
			
			
	$errorcode = $button->outertext;

		
            if($errorcode != ""  
             and (!isset($image[0]) or trim($image[0]->getAttribute('alt')) == "") 
                and (!isset($svg[0]) or $svgalt == "")
            and (!isset($input[0]) or trim($input[0]->getAttribute('value')) == "")
               and (!isset($i[0]) or (trim($i[0]->getAttribute('title')) == "" and trim($i[0]->getAttribute('aria-label')) == "")))   
             {
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"empty_button_tag", $errorcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"empty_button_tag",$wp_ada_compliance_basic_def['empty_button_tag']['StoredError'], $errorcode);
						
				
				
				
		//}
		}
		}
}
return 1;
}

?>