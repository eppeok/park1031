<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/**********************************************
// check if error exists
**********************************************/
function wp_ada_compliance_basic_error_check($postinfo, $errortype, $object) {
	global $wpdb;
	
		$scantype = sanitize_text_field($postinfo['scantype']);
	$postid = (int)$postinfo['postid'];
	$title = sanitize_text_field($postinfo['title']);
	$type = sanitize_text_field($postinfo['type']);
        $errortype = sanitize_text_field($errortype);
	
	//set flag for onsave scans
	if($scantype == 'onsave') $onsave = 1;
	else $onsave = 0;
	
	$ignre = wp_ada_compliance_basic_get_ignore_value($errortype, $onsave, $type, $object);
		
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT postid, ignre FROM '.$wpdb->prefix.'wp_ada_compliance_basic where type = %s and postid = %d and errorcode = %s and object = %s ', $type, $postid, $errortype, $object), ARRAY_A );	
	

	if ( $results ) {
	foreach ( $results as $row) {
		
	// if being ignored, don't overwrite value
	if($row['ignre'] == 1)  $ignre = 1;	
        
    // mark theme errors    
    $themeerror = wp_ada_compliance_basic_theme_error_probability_check($object, $errortype);
    wp_ada_compliance_basic_mark_theme_errors($object, $themeerror, $type);
		
		// set recordcheck to 1
	$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic SET recordcheck = %d, onsave = %d, ignre = %d  WHERE postid = %d and errorcode = %s and object = %s and type = %s', 1, $onsave, $ignre, $postid, $errortype, $object, $type) );	
	
	return 1;
	}
	}
}
/**********************************************
// check if theme error
**********************************************/
function wp_ada_compliance_basic_theme_error_probability_check($object, $errorcode) {
	global $wpdb;
$probability = '';
$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM '.$wpdb->prefix.'wp_ada_compliance_basic where object = %s and errorcode = %s', $object, $errorcode), ARRAY_A );
if($wpdb->num_rows > 10) $probability = 100;
elseif($wpdb->num_rows > 6 and $wpdb->num_rows < 11) $probability =  75;     
elseif($wpdb->num_rows > 3 and $wpdb->num_rows < 7) $probability =  33;    
elseif($wpdb->num_rows > 1 and $wpdb->num_rows < 4) $probability =  10; 
    
return  $probability; 
    
}

/**********************************************
// format error location message
**********************************************/
function wp_ada_compliance_basic_format_error_location($ignre, $errorcode,$type,$errorid, $themeerror, $tags = 1) {
global $wpdb;    
$source = '';
$details = '';
    
    $marketing = __('There is a HIGH probability that this issue will be found somewhere external to the editor content of this file. Theme error tool tips help you quickly identify the location of errors found in widget content, custom fields, excerpts and other areas outside of the actual page content. Upgrade to the full version to unlock this feature.','wp-ada-compliance-basic');
 
// certain error types are always theme errors       
$errorTypes = array('skip_nav_links', 'missing_lang_attr', 'multiple_ways_failure', 'missing_landmarks', 'unlabeled_landmarks');     

// certain error types are always theme errors   
if(in_array($errorcode,$errorTypes)) {
 $source = __('** theme error','wp-ada-compliance-basic'); 
//$details = __('Due to the error type, there is a HIGH probability that this issue will be found somewhere external to the editor content of this file.','wp-ada-compliance-basic'); 
}  
    
// if location notes are not set yet show probability notes
if($source == ''){    
if($themeerror == 10) {
$source = __('** theme error','wp-ada-compliance-basic'); 
//$details = __('Due to the number of recurrences of this issue, there is a LOW probability that this issue will be found somewhere external to the editor content of this file.','wp-ada-compliance-basic'); 

}
elseif($themeerror == 33) {
$source = __('** theme error','wp-ada-compliance-basic'); 
//$details = __('Due to the number of recurrences of this issue, there is a MEDIUM probability that this issue will be found somewhere external to the editor content area of this file.','wp-ada-compliance-basic'); 
   //    $details .= '<span class="wp-ada-theme-error">';
 //   $details .= __('Theme errors are errors that are not likely to be found in the content of the file listed. Instead the issue is more likely to be found in an external source such as a theme file. ','wp-ada-compliance-basic');
  //  $details .= '</span>';
}
elseif($themeerror > 74 and $type != 'term' and $type != 'theme') {
$source = __('** theme error','wp-ada-compliance-basic'); 
//$details = __('Due to the number of recurrences of this issue, there is a HIGH probability that this issue will be found somewhere external to the editor content area of this file.','wp-ada-compliance-basic'); 
} 
 elseif($themeerror == 100) {
$source = __('** theme error','wp-ada-compliance-basic'); 
//$details = __('Errors found in term and theme files are marked as theme errors by default. The exception being errors found to be caused by content present in another location (i.e... post excerpt, term description or page content).','wp-ada-compliance-basic'); 
} 
}
  
if($source != '') {
        if(strstr($source,'theme error')){
     //      $details .= '<span class="wp-ada-theme-error">';
  //  $details .= __('Theme errors are errors that are not likely to be found in the content of the file listed. Instead the issue is more likely to be found in an external source such as a theme file. ','wp-ada-compliance-basic');
  //  $details .= '</span>';
    }
    if($tags == 1)
$message = '<br /><div class="wp-ada-compliance-location-other wp-ada-compliance-location" ><span class="wp-ada-source-text">'.$source.'</span> <div class="wp-ada-screen-reader-text">'.$details.'<span class="adaIgnored">'.$marketing.'</span></div></div>'; 
else  $message = $source;   
    


return $message;
}
}

/***************************************************************************
// mark all theme errors
****************************************************************************/
function wp_ada_compliance_basic_mark_theme_errors($object, $probability, $type) {
global $wpdb;

$wpdb->query( $wpdb->prepare('UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic set themeerror = %d where object  = %s and type = %s', $probability, $object, $type) );   

}
/**********************************************
// check if post has errors 
**********************************************/
function wp_ada_compliance_basic_reported_errors_check($postid, $type, $onsave) {
	global $wpdb;

	$query = 'SELECT postid FROM '.$wpdb->prefix.'wp_ada_compliance_basic where type = %s and postid = %d and onsave = %d and ignre != 1'; 
	
	
	$results = $wpdb->get_results( $wpdb->prepare( $query, $type, $postid, $onsave ), ARRAY_A );

if($wpdb->num_rows > 0) return 1;

	return 0;
}

/**********************************************
// insert error
**********************************************/
function wp_ada_compliance_basic_insert_error($postinfo, $errortype, $error, $object ) {
	global $wpdb;
	$ignre = 0;
    $themeerror = '';
  
    $errortype = sanitize_text_field($errortype);
    $error = sanitize_text_field($error);
    $scantype = sanitize_text_field($postinfo['scantype']);
	$postid = (int)$postinfo['postid'];
	$title = sanitize_text_field($postinfo['title']);
	$type = sanitize_text_field($postinfo['type']);
    
        // mark theme errors    
    $themeerror = wp_ada_compliance_basic_theme_error_probability_check($object, $errortype);
    wp_ada_compliance_basic_mark_theme_errors($object, $themeerror, $type);
    
	if(isset($postinfo['taxonomy'])) $taxonomy = sanitize_text_field($postinfo['taxonomy']);
	else $taxonomy = "";
	if(isset($postinfo['externalsrc'])) $externalsrc = sanitize_text_field($postinfo['externalsrc']);
	else $externalsrc = "";
	
	if(isset($postinfo['examplecode'])) $examplecode = strip_tags($postinfo['examplecode'],'<div><img>');
	else $examplecode = "";	
	
	//set flag for onsave scans
	if($scantype == 'onsave') $onsave = 1;
	else $onsave = 0;
	
		$ignre = wp_ada_compliance_basic_get_ignore_value($errortype, $onsave, $type, $object);
	
	$timezone = get_option('timezone_string');
	if($timezone == "") $timezone = 'America/Chicago';
	date_default_timezone_set($timezone);
	$errordate = date('Y-m-d H:i.s', time()+120);
	$userid = sanitize_text_field(get_current_user_id());
	if($userid == "") $userid = 'autoscan';
	
	
	$wpdb->query( $wpdb->prepare( 'INSERT INTO '.$wpdb->prefix.'wp_ada_compliance_basic (postid, object, errorcode, posttitle, type, taxonomy, date, activeuser, ignre, recordcheck, scantype, onsave, externalsrc, examplecode) values(%d,  %s, %s, %s, %s, %s, %s, %s, %d, %d, %s, %s, %s, %s)', $postid, $object, $errortype, $title, $type, $taxonomy, $errordate, $userid, $ignre, 1, $scantype, $onsave, $externalsrc, $examplecode) );
	return $wpdb->insert_id;
}

/**********************************************
// set ignore value
**********************************************/
function wp_ada_compliance_basic_get_ignore_value($errortype, $onsave, $type, $object) {

$ignre = 0;	
	
	
if($errortype == 'img_alt_invalid' 
and (stristr($object, __('graphic of','wp-ada-compliance-basic'))
or stristr($object, __('image of','wp-ada-compliance-basic'))
or stristr($object, __('photo of','wp-ada-compliance-basic'))))	$ignre = 2;	

if($errortype == 'accessibility_help')	$ignre = 2;   
if($errortype == 'tab_order_modified')	$ignre = 2;   
if($errortype == 'empty_icon')	$ignre = 2;    
if($errortype == 'missing_landmarks')	$ignre = 2;
    if($errortype == 'link_color_contrast_failure')	$ignre = 2;
if($errortype == 'missing_lang_attr')	$ignre = 2; 
if($errortype == 'unlabeled_landmarks')	$ignre = 2;    
if($errortype == 'skip_nav_links')	$ignre = 2;    
if($errortype == 'missing_th' and stristr($object, 'role-presentation'))	$ignre = 2;
if($errortype == 'redundant_title_tag') $ignre = 2; 
if($errortype == 'redundant_alt_text') $ignre = 2; 
if($errortype == 'absolute_fontsize') $ignre = 2;
if($errortype == 'text_justified') $ignre = 2; 	
if($errortype == 'empty_heading_tag') $ignre = 2; 	
if($errortype == 'empty_anchor_tag' and !stristr($object, '<img') and !stristr($object, 'fa-') and !stristr($object, '<input') and !stristr($object, '<button')) $ignre = 2; 
if($errortype == 'empty_anchor_tag' and stristr($object, 'elementor-image-or-icon-box')) $ignre = 2; 
if($errortype == 'adjacent_identical_links' and stristr($object, 'elementor-image-or-icon-box')) $ignre = 2; 
if($errortype == 'new_window_tag') $ignre = 2; 	
if(stristr($object,"window.open") and $errortype == 'new_window_tag')$ignre = 0;
if($errortype == 'av_tag_with_autoplay') $ignre = 2; 	
if($errortype == 'img_linked_to_self') $ignre = 2;
if($errortype == 'iframe_missing_title' and wp_ada_compliance_basic_check_iframe_for_filtered_src_url('', $object)) $ignre = 2;    
if($errortype == 'emulating_links' and $wp_ada_compliance_correct_links == 'true') $ignre = 2;
if($errortype == 'visual_focus_removed') $ignre = 2;
if($errortype == 'missing_onkeypress' and $wp_ada_compliance_correct_event_handlers == 'true') $ignre = 2;
if($errortype == 'unlinked_anchors' and preg_match("/<a.*?<\/a>(*SKIP)(*F)|[\w-]+@([\w-]+\.)+[\w-]+/i",$object)) $ignre = 2;
if($errortype == 'link_to_non_html_content') $ignre = 2;
if($errortype == 'link_to_in_page_content') $ignre = 2;
if($errortype == 'visual_focus_removed' and strstr($object, 'style=')) $ignre = 2;
if($errortype == 'elementor_toc') $ignre = 2;
if($errortype == 'elementor_toggles') $ignre = 2;
    if($errortype == 'elementor_carousel_autoplay')	$ignre = 2;


	return $ignre;
}
/**********************************************
// ignore error
**********************************************/
function wp_ada_compliance_basic_jquery_ignore_error( $id, $direction) {
	global $wpdb;
	
	if ( !current_user_can( "edit_pages" ) ) return 1;
	

	$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic set ignre = %d where id  = %d ', $direction, $id) );	
	
	
}
function wp_ada_compliance_basic_ignore_error( $id ) {
	global $wpdb;

	if ( !current_user_can( "edit_pages" ) ) return 1;
	
	// cancel ignore
	if (isset( $_GET[ 'canxignore' ] ) ) {
		$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic set ignre = %d where id  = %d ', 0, $id) );
		$_SESSION['my_ada_important_notices'] = __('The selected error is no longer being ignored.','wp-ada-compliance-basic');
	} 
		// ignore just this id
	else {
	$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic set ignre = %d where id  = %d ', 1, $id) );

	$_SESSION['my_ada_important_notices'] = __('The selected error is now being ignored.','wp-ada-compliance-basic');
		}
}
/**********************************************
// check ignore status
**********************************************/
function wp_ada_compliance_basic_ignore_check( $errortype, $postid, $object, $type ) {
	global $wpdb;
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.'wp_ada_compliance_basic where type= %s and postid = %d and errorcode = %s and object = %s and ignre = %d', $type, $postid, $errortype, $object, 1 ), ARRAY_A );

	if ( $results ) {
		return 1;
	}
}

/**********************************************
// check if font awesome link can be corrected
**********************************************/
function wp_ada_compliance_basic_check_if_font_awesome_link_can_be_corrected($content){

// correct issues with encoding when website is using non utf-8
if(function_exists('mb_convert_encoding'))
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");	

$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($content);	

$links = $dom->getElementsByTagName('a');
	
foreach ($links as $link) {
	
$elements = $link->getElementsByTagName("*");
foreach ($elements as $i) {
if(isset($i) and strip_tags(trim($link->nodeValue)) == ''){	
if(stristr($i->getAttribute('class'),'fa-')) return 1;
}
}
}
	
return 0;
}


/**********************************************************
retrieve aria value and return
**********************************************************/
function wp_ada_compliance_basic_get_aria_values($dom, $element, $field){
    
if($field == 'aria-labelledby' and $element->getAttribute('aria-labelledby') != "") {
			$ariaid = $element->getAttribute('aria-labelledby');
    if(isset($dom->getElementById($ariaid)->plaintext))       
    return trim($dom->getElementById($ariaid)->plaintext);
		}

if($field == 'aria-describedby' and $element->getAttribute('aria-describedby') != "") {
			$ariaid = $element->getAttribute('aria-describedby');
    if(isset($dom->getElementById($ariaid)->plaintext))       
   return trim($dom->getElementById($ariaid)->plaintext);
} 
    
return ;    
}

/**********************************************
// get error list for affected post
**********************************************/
function wp_ada_compliance_basic_get_error_list_for_post($postid, $posttype){
	global $wpdb, $wp_ada_compliance_basic_def;
    $errornotices = '';
    
$results = $wpdb->get_results( $wpdb->prepare( 'SELECT distinct errorcode FROM '.$wpdb->prefix.'wp_ada_compliance_basic where postid = %d and type = %s and scantype = %s and ignre != %d', $postid, $posttype, 'onsave', '1'), ARRAY_A );

	foreach ( $results as $row ) {
        $errorcode = $row['errorcode'];
			$errornotices .= '<p>';
			$errornotices .= $wp_ada_compliance_basic_def[$errorcode]['DisplayError'];
			if($wp_ada_compliance_basic_def[$errorcode]['Reference'] != "") 
            $errornotices .= ' <a href="'.$wp_ada_compliance_basic_def[$errorcode]['ReferenceURL'].'" target="_blank" class="adaNewWindowInfo">'.$wp_ada_compliance_basic_def[$errorcode]['Reference'].'<i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';  
			 $errornotices .= '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view=1&errorid='.esc_attr($postid).'&type='.esc_attr($posttype).'&iframe=1&TB_iframe=true&width=900&height=550" class="thickbox adaNewWindowInfo adaErrorText adareportlink" target="_blank"><i class="fas fa-eye" aria-hidden="true"></i>';
             $errornotices .= __('View Accessibility Report for Help Options','wp-ada-compliance-basic');
             $errornotices .= '</a>'; 
			$errornotices .= '</p>';
	}
    return $errornotices;
}
/**********************************************
// check if ambiguous link text can be corrected
**********************************************
function wp_ada_compliance_basic_check_if_ambiguous_link_can_be_corrected($content){

// correct issues with encoding when website is using non utf-8
if(function_exists('mb_convert_encoding'))
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");	

$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($content);	

// redundant title text
$links = $dom->getElementsByTagName('a');
foreach ($links as $link) {	

if(strstr($link->getAttribute('class'),'flex-prev') or strstr($link->getAttribute('class'),'flex-next')) return 1;	
	
$url = esc_url($link->getAttribute('href'));
$post_id = url_to_postid($url);
if(get_the_title($post_id) != "" and $post_id != 0)return 1;
	
}
	return 0;
}*/
?>