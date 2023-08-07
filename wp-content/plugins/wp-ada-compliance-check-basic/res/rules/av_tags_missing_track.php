<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate audio or video tags missing track tags
/********************************************************************/	
function wp_ada_compliance_basic_validate_av_tags_missing_track($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('av_tags_missing_track', $wp_ada_compliance_basic_scanoptions)) return 1;		
	

// check iframe tags
$iframes = $dom->find('iframe');
foreach ($iframes as $iframe) {
		$videocode = $iframe->outertext;
		if (isset($iframe) and (
			// file formats
			strstr($videocode,'.mp4')
			or stristr($videocode,'.m4a')
			or stristr($videocode,'.ogv')
			or stristr($videocode,'.mp3') 
			or stristr($videocode,'.webm') 
			or stristr($videocode,'.flv') 
			or strstr($videocode,'.vtt')
			
			or strstr($videocode,'.vob')
			or strstr($videocode,'.ogg')
			or strstr($videocode,'.ogv')
			or strstr($videocode,'.wmv')
			or strstr($videocode,'.avi')
			or strstr($videocode,'.m4v')
			or strstr($videocode,'.mov')
			or strstr($videocode,'.swf')
			or strstr($videocode,'.mpeg')
			or strstr($videocode,'.asf')
			or strstr($videocode,'.wav')
			or strstr($videocode,'.wma')
			or strstr($videocode,'.mid')
			or strstr($videocode,'.midi')
			or strstr($videocode,'.au')
			or strstr($videocode,'.aiff')
			or strstr($videocode,'.qt')
			
			
		   )){			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tags_missing_track", $videocode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tags_missing_track", $wp_ada_compliance_basic_def['av_tags_missing_track']['StoredError'], $videocode);
			

		}
}
	
// check video tag	
$videos = $dom->find('video');
foreach ($videos as $video) {
		$videocode = $video->outertext;
		if (isset($video) and !strstr($videocode,'<track')){			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tags_missing_track", $videocode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tags_missing_track", $wp_ada_compliance_basic_def['av_tags_missing_track']['StoredError'], $videocode);
			

		
		}
}	
	
// validate audio tags
$audios = $dom->find('audio');
foreach ($audios as $audio) {
		$audiocode = $audio->outertext;
		if (isset($audio) and !strstr($audiocode,'<track')){
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"av_tags_missing_track", $audiocode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"av_tags_missing_track", $wp_ada_compliance_basic_def['av_tags_missing_track']['StoredError'], $audiocode);
			

		
		}
	
}// end audio validate		
return 1;
}
?>