<?php 
/*
Plugin - WP ADA Compliance Check
includes all content checks and validations for the scan process and content publishing
*/
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/*******************************************************************
// all content is sent to this function to be checked for issues.
*******************************************************************/
function wp_ada_compliance_basic_validate_post($postinfo) {
    
// skip check when importing records    
if(get_option('wp_ada_compliance_basic_import_inprocess') == '1') return 1;     

if(!isset($postinfo['fullcontent'])) $postinfo['fullcontent'] = '';  
    
if($postinfo['fullcontent'] != '')  $content  = $postinfo['fullcontent']; 
else{
$content = wp_ada_compliance_basic_strip_shortcodes($postinfo['content']);

// elementor content
if(is_plugin_active('elementor/elementor.php') and class_exists("\\Elementor\\Plugin")) {
$content = apply_filters( 'elementor/frontend/the_content', $content );  
}
$content  = apply_filters('the_content', $content );  
}
	
// correct issues with encoding when website is using non utf-8
if(function_exists('mb_convert_encoding'))
$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");	
		
// clear error message
if(!isset($_SESSION['my_ada_notices'])) $_SESSION['my_ada_notices'] = "";
	
	
/******************************	
 start validation	
*******************************/

// specialty validations
// duplicate title check
wp_ada_compliance_basic_validate_duplicate_title($postinfo);
	
// do content checks
if($content != "") 
wp_ada_compliance_basic_complete_content_validations($content, $postinfo);	
    

// clear error message if not scanning on save	
if(isset($_GET['startscan']) or !array_key_exists('scantype',$postinfo) and !wp_ada_compliance_basic_check_is_admin(1)) 
	$_SESSION['my_ada_notices'] = "";
}

/******************************	
content validations	
*******************************/
function wp_ada_compliance_basic_complete_content_validations($content, $postinfo){

if($content == "") return ;
	
// check for redundant alt text on images	
wp_ada_compliance_basic_validate_redundant_alt_text($content,$postinfo);	
	
// check for redundant title attributes on links	
wp_ada_compliance_basic_validate_redundant_title_tag($content, $postinfo);
		
// check for absolute font sizes
wp_ada_compliance_basic_validate_absolute_fontsize($content, $postinfo);

// check for ambiguous ancor text
wp_ada_compliance_basic_validate_ambiguous_anchor_tag($content, $postinfo);
	
// check for audio or video tags with autoplay
wp_ada_compliance_basic_validate_av_tag_with_autoplay($content, $postinfo);

// check for audio, video or iframe video missing tracks	
wp_ada_compliance_basic_validate_av_tags_missing_track($content, $postinfo);
	
// check for empty anchor tag
wp_ada_compliance_basic_validate_empty_anchor_tag($content, $postinfo);	

// check for empty heading tag
wp_ada_compliance_basic_validate_empty_heading_tag($content, $postinfo);
	
// validate title tag in iframe
wp_ada_compliance_basic_validate_iframe_missing_title($content, $postinfo);	
	
// validate alt text content
wp_ada_compliance_basic_validate_img_alt_invalid($content, $postinfo);
	
// image missing alt text
wp_ada_compliance_basic_validate_img_missing_alt($content, $postinfo);
	
// image empty alt text
wp_ada_compliance_basic_validate_img_empty_alt($content, $postinfo);	
	
// target new window
wp_ada_compliance_basic_validate_new_window_tag($content, $postinfo);
	
// validate redundent link text
wp_ada_compliance_basic_validate_redundant_anchor_text($content, $postinfo);
	
// validate tables missing th	
wp_ada_compliance_basic_validate_missing_th($content, $postinfo);	
	
// validate form fields for labels
wp_ada_compliance_basic_validate_missing_form_label($content, $postinfo);
	
// image map hot spot missing alt text
wp_ada_compliance_basic_validate_imagemap_missing_alt_text($content, $postinfo);	
	
// validate forms that submit automatically or trigger an unexpected context change
wp_ada_compliance_basic_validate_context_change_form($content, $postinfo);	
	
// validate elments with onclick but not onkeypress
wp_ada_compliance_basic_validate_missing_onkeypress($content, $postinfo);	
	
	// look for links without a visual cue
wp_ada_compliance_basic_validate_link_without_visual_cue($content, $postinfo);
	
// validate page structure, look for missing headings
wp_ada_compliance_basic_validate_missing_headings($content, $postinfo);	
	
//check for anchor tags linking images to them self
wp_ada_compliance_basic_validate_img_linked_to_self($content, $postinfo);	
	
// validate animated images
wp_ada_compliance_basic_validate_animated_image($content, $postinfo);	
	
// look for foreground and background colors without enough contrast
wp_ada_compliance_basic_validate_color_contrast_failure($content, $postinfo);	
		
//check missing page titles
wp_ada_compliance_basic_validate_missing_title($content, $postinfo);	
	
// image empty alt text with title or non empty aria label attribute
wp_ada_compliance_basic_validate_img_empty_alt_with_title($content, $postinfo);		
		
// validate blinking text
wp_ada_compliance_basic_validate_blinking_text($content, $postinfo);
	
// look for links and focus styles where visual focus indication has been removed.
 wp_ada_compliance_basic_validate_visual_focus_removed($content, $postinfo);
	
//look for tags with onclick used to emulate links
wp_ada_compliance_basic_validate_emulating_links($content, $postinfo);
	
// validate meta refresh attributes that reload the page or redirect to a new location after a timeout
wp_ada_compliance_basic_validate_meta_refresh_use($content, $postinfo);
	
// validate page structure, look for headings that are not in order
wp_ada_compliance_basic_validate_incorrect_heading_order($content, $postinfo);
	
// check for tab order changed using tabindex 
wp_ada_compliance_basic_validate_tab_order_modified($content, $postinfo);	
	
// validate justified text
wp_ada_compliance_basic_validate_text_justified($content, $postinfo);	
			

// validate links without href but that include event handlers
wp_ada_compliance_basic_validate_missing_href($content, $postinfo);	
		
//check for empty button tag	
wp_ada_compliance_basic_validate_empty_button_tag($content, $postinfo);

//check for empty anchor tag	
wp_ada_compliance_basic_validate_empty_href($content, $postinfo);	
    
// links in content that are lot included in an anchor tag
wp_ada_compliance_basic_validate_unlinked_anchors($content, $postinfo);   
        
// elementor galleries    
wp_ada_compliance_basic_validate_elementor_gallery($content, $postinfo);  
    
// look for elementor toc widgets
wp_ada_compliance_basic_validate_elementor_toc($content, $postinfo);     
    
// look for elementor toggle or accordion
wp_ada_compliance_basic_validate_elementor_toggles($content, $postinfo);    
    
    // validate adjacent identical links
wp_ada_compliance_basic_validate_adjacent_identical_links($content, $postinfo);   
    
    // validate empty th cells
wp_ada_compliance_basic_validate_empty_th($content, $postinfo);
    
// check for links to non html content
wp_ada_compliance_basic_validate_link_to_non_html_content($content, $postinfo);  

// validate skip nav links in theme files
wp_ada_compliance_basic_validate_skip_nav_links($content, $postinfo);  
    
// validate missing landmarks
wp_ada_compliance_basic_validate_missing_landmarks($content, $postinfo);   
    
// validate unlabeled landmarks
wp_ada_compliance_basic_validate_unlabeled_landmarks($content, $postinfo);   
    
// validate missing laguage attributes in theme files
wp_ada_compliance_basic_validate_missing_lang_attr($content, $postinfo);   
    
// look for elementor image carousel set to autoplay
wp_ada_compliance_basic_validate_elementor_carousel_autoplay($content, $postinfo);   
    
    // look for links without enough contrast between the text around them
wp_ada_compliance_basic_validate_link_color_contrast_failure($content, $postinfo);

// check for accessibility help options
wp_ada_compliance_basic_validate_accessibility_help($content, $postinfo);

	
}


/**********************************************************
strip unwanted shortcodes from content before validating
*********************************************************/
function wp_ada_compliance_basic_strip_shortcodes( $content ) {
  global $shortcode_tags;

$shortcodesArray = array('embed','caption','audio', 'video', 'gallery');
	
  if ( false === strpos( $content, '[' ) ) {
    return $content;
  }

  if (empty($shortcode_tags) || !is_array($shortcode_tags))
    return $content;


foreach($shortcode_tags as $key => $value){		
if(!in_array($key,$shortcodesArray) and strstr($content,$key)){
//$pattern = "/\[(\/)*ezcol_1half_end(.*?)\]/";
$pattern = '#\[(\[?)('.$key.')(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)#';	
$content = preg_replace( $pattern, '', $content );
}

}

  return $content;
}

/*********************************************************
add view report meta box
*********************************************************/
function wp_ada_compliance_basic_report_meta_box()
{
$post_types = array('page','post');
foreach( $post_types as $type ) { 
add_meta_box( 'wp_ada_meta_box_id', 'Web Accessibility', 'wp_ada_compliance_basic_view_report', $type, 'side', 'high', array(
        '__block_editor_compatible_meta_box' => true,
    ) );
}
}
function wp_ada_compliance_basic_view_report() {  
global $post;
	$enablewave = get_option('wp_ada_compliance_basic_enablewave','true');
if(wp_ada_compliance_basic_reported_errors_check($post->ID, $post->post_type, 1)){
     echo  '<p class="ada_compliance_report_link"><a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view=1&errorid='.esc_attr($post->ID).'&type='.esc_attr($post->post_type).'&iframe=1&TB_iframe=true&width=900&height=550" class="thickbox btnwpada btnwpada-warning adareportlink">'; 
	_e('Accessibility Report','wp-ada-compliance-basic');
	echo '</a></p>';
	if($enablewave == 'true'){
	echo '<p class="wp-ada-compliance-wave-link"><a href="http://wave.webaim.org/report#/'.esc_url(site_url()).'/?p='.esc_attr($post->ID).'"><i class="far fa-check-square" aria-hidden="true"></i>';
	echo __('Evaluate with Wave','wp-ada-compliance-basic');
	echo '</a>
      <a href="https://validator.w3.org/nu/?doc='.esc_url(site_url()).'/?p='.esc_attr($post->ID).'"><i class="fas fa-code" aria-hidden="true"></i>';
	echo __('Validate HTML','wp-ada-compliance');
	echo '</a></p>';
	} 
	}  
	else{
	 echo  '<p class="ada_compliance_report_link_clear ada_compliance_report_link"><i class="fas fa-thumbs-up btnwpada" aria-hidden="true" title="'.__('No Issues','wp-ada-compliance-basic').'"></i></p>';	
	if($enablewave == 'true'){
		echo '<p class="wp-ada-compliance-wave-link"><a href="http://wave.webaim.org/report#/'.esc_url(site_url()).'/?p='.esc_attr($post->ID).'"><i class="far fa-check-square" aria-hidden="true"></i>';
	echo __('Evaluate with Wave','wp-ada-compliance-basic');
	echo '</a>
      <a href="https://validator.w3.org/nu/?doc='.esc_url(site_url()).'/?p='.esc_attr($post->ID).'"><i class="fas fa-code" aria-hidden="true"></i>';
	echo __('Validate HTML','wp-ada-compliance');
	echo '</a></p>';
	}
	}
} 
/********************************************************************/	
// compare strings
/********************************************************************/	
function wp_ada_compliance_basic_compare_strings($string1, $string2){
    // text to remove
	$removeText = array();
    $removeText[] = __('permalink of ', 'wp-ada-compliance');
	$removeText[] = __('permalink to ', 'wp-ada-compliance');
	$removeText[] = __('&nbsp;', 'wp-ada-compliance'); 
    
	$string1 = strtolower($string1);
    $string1 = str_ireplace($removeText, '',$string1);
    $string1 = strip_tags($string1);
    $string1 = trim($string1, " \t\n\r\0\x0B\xC2\xA0");
    $string1 = html_entity_decode($string1);
    
	$string2 = strtolower($string2);
    $string2 = str_ireplace($removeText, '',$string2);
    $string2 = strip_tags($string2);
    $string2 = trim($string2, " \t\n\r\0\x0B\xC2\xA0");
    $string2 = html_entity_decode($string2);

if($string1 == $string2) return 1;
else return 0;
    
}
//  set wordpress option when importing content to disable scans during import
function wp_ada_compliance_basic_start_import(){
   update_option('wp_ada_compliance_basic__import_inprocess','1'); 
}
function wp_ada_compliance_basic_end_import(){
   update_option('wp_ada_compliance_basic_import_inprocess','0'); 
}
?>