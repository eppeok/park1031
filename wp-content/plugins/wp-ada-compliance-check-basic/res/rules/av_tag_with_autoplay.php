<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
/// check video with autoplay
/********************************************************************/	
function wp_ada_compliance_basic_validate_av_tag_with_autoplay($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$strip_autoplay = 'false';
$report_filtered_errors = 'true';		
	
// check if being scanned
if(in_array('av_tag_with_autoplay', $wp_ada_compliance_basic_scanoptions)) return 1;	
	

$videos = $dom->find('video');
foreach ($videos as $video) {
		$videocode = $video->outertext;
		if (isset($video) and (stristr($videocode,'autoplay') and !stristr($videocode,'muted'))){			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tag_with_autoplay", $videocode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tag_with_autoplay", $wp_ada_compliance_basic_def['av_tag_with_autoplay']['StoredError'], $videocode);
			

		
		}
}	
// check audio tags for autoplay	
$audios = $dom->find('audio');
foreach ($audios as $audio) {
		$audiocode = $audio->outertext;
		if (isset($audio) and (strstr($audiocode,'autoplay') and !strstr($audiocode,'muted'))){			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tag_with_autoplay", $audiocode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tag_with_autoplay", $wp_ada_compliance_basic_def['av_tag_with_autoplay']['StoredError'], $audiocode);
			

	
		}
}
//Check object tags for auto play
$objects = $dom->find('object');
foreach ($objects as $object) {	
$nodes = $object->children;
		foreach ($nodes as $node) {
			if($node->tag == "param") {
				
			if((strtolower($node->getAttribute('name')) == "autoplay" and strtolower($node->getAttribute('value')) == "true") or (strtolower($node->getAttribute('name')) == "flashvars" 
		and stristr($node->getAttribute('value'),"autoPlay=true"))){
				   
			
			$objectcode = $object->outertext;	
					
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tag_with_autoplay", $objectcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tag_with_autoplay", $wp_ada_compliance_basic_def['av_tag_with_autoplay']['StoredError'], $objectcode);
			

		}// end child attribute check
		}		
}// end child node foreach object
}

// check embed tags for autostart	
$embeds = $dom->find('embed');
foreach ($embeds as $embed) {
		$embedcode = $embed->outertext;
		if (isset($embed) and $embed->getAttribute('autostart') == 'true'){			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tag_with_autoplay", $embedcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tag_with_autoplay", $wp_ada_compliance_basic_def['av_tag_with_autoplay']['StoredError'], $embedcode);
			

		}
}	
	
return 1;	
}
?>