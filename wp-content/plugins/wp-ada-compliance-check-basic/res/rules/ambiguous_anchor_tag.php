<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
	
/********************************************************************/	
// validate ambiguous link text
/********************************************************************/	
function wp_ada_compliance_basic_validate_ambiguous_anchor_tag($content, $postinfo){
		
global $wp_ada_compliance_basic_def;

$content =  preg_replace("/&#?[a-z0-9]+;/i","",$content);	
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('ambiguous_anchor_tag', $wp_ada_compliance_basic_scanoptions)) return 1;
				
// tags to check
$tags = array('a', 'button');	
	
foreach ($tags as $tag) {	    
    
$links = $dom->find($tag);
foreach ($links as $link) {
	
        // if wrapping an image include alt text in check
    $imagealt = '';
    $svgimagealt = '';
    if(stristr($link->innertext,'<img')){    
    $images = $link->find('img');
        foreach ($images as $img) {
        if(isset($img)){
         $imagealt = trim($img->getAttribute('alt')); 
        }
        }
    }
    if(stristr($link->innertext,'<svg')){    
    $svgs = $link->find('svg');
        foreach ($svgs as $svg) {
        if(isset($svg)){
          $svgimagealt = trim(wp_ada_compliance_basic_check_svg_img_alt_text($svg, $dom)); 
        }
      }
    }
       
        
	if($link->getAttribute('title') != "" and trim($link->getAttribute('title')) != trim($link->plaintext)) {
			$linktitle = $link->getAttribute('title');
		} else $linktitle = '';
    
        if($link->getAttribute('aria-label') != "") {
			$linktext = $link->getAttribute('aria-label');
		}
      elseif($link->getAttribute('aria-labelledby') != "") {
			$ariaid = $link->getAttribute('aria-labelledby');
          if(isset($dom->getElementById($ariaid)->plaintext))  
          $linktext = $dom->getElementById($ariaid)->plaintext;
		}
      elseif($link->getAttribute('aria-describedby') != "") {
			$ariaid = $link->getAttribute('aria-describedby');
          if(isset($dom->getElementById($ariaid)->plaintext))  
          $linktext = $dom->getElementById($ariaid)->plaintext;
		}
    
		else{
             if($svgimagealt != ''){
			$linktext = trim(strip_tags($link->innerttext)).$svgimagealt.$linktitle;
             }
            elseif($imagealt != '')
			$linktext = trim($link->plaintext).$imagealt.$linktitle;
            else $linktext = trim($link->plaintext).$linktitle;
		}
	
		// remove punctuation
	  // $linktext = preg_replace("#[[:punct:]]#", "", $linktext);
$urlpattern = "#^(".__('link to','wp-ada-compliance-basic')."|".__('permalink to','wp-ada-compliance-basic').")*(:*\s*)(?i)\b((?:https?://(?:www\d{0,3}[.])?|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\".,<>?«»“”‘’]))#i";
	
	if(isset($link->parent()->tag) 
        and $link->parent()->tag == 'nav'){
           $parenthtml = $link->parent()->outertext;
        } elseif(isset($link->parent()->parent()->tag) 
        and $link->parent()->parent()->tag == 'nav'){
           $parenthtml = $link->parent()->parent()->outertext;
        } else $parenthtml = '';
    
		if (($imagealt != '' and (stristr($imagealt,__(' logo','wp-ada-compliance-basic')) and !wp_ada_compliance_basic_check_image_wrapped_in_anchor($dom, $imagealt))) 
        or ($linktext != "" and (
		preg_match('|^'.__('continue reading','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or preg_match('|^'.__('click here','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or preg_match('|^'.__('Read More','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
            or preg_match('|^'.__('Link','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or preg_match('|^'.__('Read More >','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or preg_match('|^'.__('here','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or preg_match('|^'.__('find out more','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or preg_match('|^'.__('More...','wp-ada-compliance-basic').'(\s)*$|i',$linktext)
		or preg_match('|^'.__('More','wp-ada-compliance-basic').'(\s)*$|i',$linktext)
		or (preg_match('|^'.__('Previous','wp-ada-compliance-basic').'(\s)*$|i',$linktext) and !stristr($parenthtml,'screen-reader-text') and !stristr($parenthtml,'aria-label='))
		or (preg_match('|^'.__('Next','wp-ada-compliance-basic').'(\s)*$|i',$linktext) and !stristr($parenthtml,'screen-reader-text') and !stristr($parenthtml,'aria-label='))
		or (preg_match('|^'.__('1','wp-ada-compliance-basic').'(\s)*$|i',$linktext) and !stristr($parenthtml,'screen-reader-text') and !stristr($parenthtml,'aria-label='))
		or preg_match('|^'.__('clicking here','wp-ada-compliance-basic').'(\s)*$|i',$linktext)
		or preg_match('|^'.__('website','wp-ada-compliance-basic').'(\s)*$|i',$linktext)
		or preg_match('|^'.__('click for details','wp-ada-compliance-basic').'(\s)*$|i',$linktext) 
		or (preg_match($urlpattern,$linktext) and !strstr($parenthtml, 'wp-video') and !strstr($parenthtml, 'wp-audio'))))) {
		 		
		 					
			$ahtagcode = $link->outertext;
			
		
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"ambiguous_anchor_tag", $ahtagcode)){
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"ambiguous_anchor_tag", $wp_ada_compliance_basic_def['ambiguous_anchor_tag']['StoredError'],  $ahtagcode);
			}
				
		
		}
}
} 

	return 1;
} 
/********************************************************************/	
// check if image is wrapped in an anchor and linked to larger image
/********************************************************************/
function wp_ada_compliance_basic_check_image_wrapped_in_anchor($dom, $alt){
	
$links = $dom->find('a');
foreach ($links as $link) {
	$images = $link->find('img');
	if(isset($images)){
	foreach ($images as $image) {
		$src=$image->getAttribute('src');
		$href = $link->getAttribute('href');
		$extension = substr($image->getAttribute('src'),-4,4);
		if($extension != '' and $alt == $image->getAttribute('alt') and $extension != '' and stristr($href, $extension)) return 1;
	}
}
}
return 0;
}
?>