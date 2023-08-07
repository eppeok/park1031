<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

/********************************************************************/	
// validate title tag in iframe
/********************************************************************/	
function wp_ada_compliance_basic_validate_iframe_missing_title($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$report_filtered_errors = true;
$edit_embed_code = false;
	
// check if being scanned
if(in_array('iframe_missing_title', $wp_ada_compliance_basic_scanoptions)) return 1;		

$iframetags = $dom->find('iframe');
foreach ($iframetags as $iframe) {
if (isset($iframe) and $iframe->getAttribute('title') == "" and $iframe->getAttribute('aria-label') == "") {  

// check if iframe is being filtered
$filteredtitle = wp_ada_compliance_basic_check_iframe_for_filtered_src_url($iframe->getAttribute('src'));
	
	 $iframecode = htmlspecialchars($iframe->outertext);
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"iframe_missing_title", $iframecode)){
				
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"iframe_missing_title",$wp_ada_compliance_basic_def['iframe_missing_title']['StoredError'], $iframecode);
			}
			
			
		}
}
	return 1;
} 

/********************************************************************
check the url to see if it is being filtered
*******************************************************************/
function wp_ada_compliance_basic_check_iframe_for_filtered_src_url($inserturl, $iframecode = "") {

if($iframecode != ""){	

$dom = str_get_html(htmlspecialchars_decode($iframecode));		

$iframes = $dom->find('iframe');
foreach ($iframes as $iframe) {

$inserturl = $iframe->getAttribute('src');	
}
}
	if($inserturl == "") return 0;
	
   $url = parse_url($inserturl);
if(is_array($url) and array_key_exists('host',$url)) {
    switch ( str_replace( 'www.', '', $url['host'] ) ) {
			
        /* media */
		case 'cloudup.com': 
		case 'facebook.com':
		case 'flickr.com':
		case 'imgur.com':
		case 'instagram.com':	
		case 'issuu.com':
		case 'kickstarter.com': 
		case 'meetup.com':
		case 'mixcloud.com':
		case 'photobucket.com':	
		case 'reddit.com':	
		case 'speakerdeck.com':
		case 'scribd.com':	
		case 'slideshare.net':	
		case 'smugmug.com': 
		case 'tumbler.com':
		case 'twitter.com': 	
		case 'wordpress.org':	
		case 'polldaddy.com':
        case 'animoto.com':
        case 'blip.com':
        case 'collegehumor.com':
        case 'dailymotion.com':
        case 'funnyordie.com':
        case 'hulu.com':
        case 'ted.com':
        case 'videopress.com':
        case 'vimeo.com':
        case 'vine.com':
        case 'wordpress.tv':
        case 'youtube.com':
        case 'mixcloud.com':
        case 'reverbnation.com':
        case 'soundcloud.com':
        case 'spotify.com':
			case 'calendar.google.com':	
	    return 1;
        break;
      
        default:
        return 0;
        break;

			
}
}
return 0;	
}

?>