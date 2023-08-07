<?php 
/*
Plugin - WP ADA Compliance Check
functiosn to display admin setting pages and process related actions
*/
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/************************************************************
// add the admin settings 
**********************************************************/
function  wp_ada_compliance_basic_admin_init(){

/***********************************************************************
DEFINE CONTENT TO SCAN SETTINGS
**************************************************************************/
add_settings_section('wp_ada_compliance_basic_scantypes', __('Content To Monitor', 'wp-ada-compliance-basic'), 'wp_ada_compliance_basic_scantypetext', 'wp_ada_compliance_basic');		

// scan with wpget files
/*register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_deep_scan', array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_deep_scan'));
add_settings_field('wp_ada_compliance_basic_deep_scan', '', 'wp_ada_compliance_basic_settings_deep_scan', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_scantypes');	*/

// post types
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_posttypes',array('type' => 'array','sanitize_callback' =>'wp_ada_compliance_basic_validate_posttypes'));
add_settings_field('wp_ada_compliance_basic_posttypes', '', 'wp_ada_compliance_basic_settings_posttypes', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_scantypes');
	
/***********************************************************************
DEFINE FILTERS SETTINGS
**************************************************************************/
add_settings_section('wp_ada_compliance_basic_filters', __('Content Filters - (Full Version Only)', 'wp-ada-compliance-basic'), 'wp_ada_compliance_basic_filtertext', 'wp_ada_compliance_basic');

// turn on content filtering
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_filter_content');
add_settings_field('wp_ada_compliance_basic_filter_content', '', 'wp_ada_compliance_basic_settings_filter_content', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_filters');	
	
/***********************************************************************
DEFINE AUTO SCAN & NOTIFICATION SETTINGS
**************************************************************************/	
add_settings_section('wp_ada_compliance_basic_notify', __('Scan & Notification - (Full Version Only)', 'wp-ada-compliance-basic'), 'wp_ada_compliance_basic_notifications', 'wp_ada_compliance_basic');	
	
// how many posts to scan during full scans
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_full_scan_post_count');
add_settings_field('wp_ada_compliance_basic_full_scan_post_count', '', 'wp_ada_compliance_basic_settings_full_scan_post_count', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_notify');		
	
/***********************************************************************
DEFINE SCAN RULE SETTINGS
**************************************************************************/	
add_settings_section('wp_ada_compliance_basic_scan_rules', __('Scan Rules', 'wp-ada-compliance-basic'), 'wp_ada_compliance_basic_scan_rule_text', 'wp_ada_compliance_basic');	
	
// scan rules section
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_scan_rules',array('type' => 'array','sanitize_callback' =>'wp_ada_compliance_basic_validate_scan_rules'));
add_settings_field('wp_ada_compliance_basic_scan_rules', '', 'wp_ada_compliance_basic_settings_scan_rules', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_scan_rules');	
    
/***********************************************************************
DEFINE WIDGET SETTINGS
**************************************************************************/
add_settings_section('wp_ada_compliance_basic_widget', __('Widget - (Full Version Only)', 'wp-ada-compliance-basic'), 'wp_ada_compliance_basic_widget_text', 'wp_ada_compliance_basic');  
    
    // use the accessibility widget
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_use_accessibility_widget');
add_settings_field('wp_ada_compliance_basic_use_accessibility_widget', '', 'wp_ada_compliance_basic_settings_use_accessibility_widget', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_widget');	       
	
/***********************************************************************
DEFINE MISC SETTINGS
**************************************************************************/	
add_settings_section('wp_ada_compliance_basic_main', __('Misc Settings', 'wp-ada-compliance-basic'), 'wp_ada_compliance_basic_text', 'wp_ada_compliance_basic');	
	
// number of errors per page to display on error report
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_errors_per_page',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_errors_per_page'));
add_settings_field('wp_ada_compliance_errors_per_page', '', 'wp_ada_compliance_basic_settings_errors_per_page', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');			

// attachment title check
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_attachmenttitles',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_true_default_false'));
add_settings_field('wp_ada_compliance_basic_attachmenttitles', '', 'wp_ada_compliance_basic_settings_attachmenttitles', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');	

	// settings authorized user
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_settingsusers',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_settingsusers'));
add_settings_field('wp_ada_compliance_basic_settingsusers', '', 'wp_ada_compliance_basic_settings_settingsusers', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');	
// website language code
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_language_code',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_language_code'));
add_settings_field('wp_ada_compliance_basic_language_code', '', 'wp_ada_compliance_basic_settings_language_code', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');	
	
	// assumed foreground color
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_foreground_color',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_foreground_color'));
add_settings_field('wp_ada_compliance_basic_foreground_color', '', 'wp_ada_compliance_basic_settings_foreground_color', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');	
    
	// assumed background color
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_background_color',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_background_color'));
add_settings_field('wp_ada_compliance_basic_background_color', '', 'wp_ada_compliance_basic_settings_background_color', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');	    
	
// starting heading in page content
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_starting_H_level',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_starting_H_level')); 
add_settings_field('wp_ada_compliance_basic_starting_H_level', '', 'wp_ada_compliance_basic_settings_starting_H_level', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');		

	
// enable wave evaluation tools
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_enablewave',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_false_default_true'));
add_settings_field('wp_ada_compliance_basic_enablewave', '', 'wp_ada_compliance_basic_settings_enablewave', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');	

	// retain settings when plugin is deleted
register_setting( 'wp_ada_compliance_basic_options', 'wp_ada_compliance_basic_retain_settings',array('type' => 'string','sanitize_callback' =>'wp_ada_compliance_basic_validate_true_default_false'));
add_settings_field('wp_ada_compliance_basic_retain_settings', '', 'wp_ada_compliance_basic_settings_retain_settings', 'wp_ada_compliance_basic', 'wp_ada_compliance_basic_main');
	
	
}

// instructions for notification settings goes here
function  wp_ada_compliance_basic_notifications() {

}	

// instructions for misc settings goes here
function  wp_ada_compliance_basic_text() {

}	

// scan rule section text goes here
function  wp_ada_compliance_basic_scan_rule_text() {

}	

// widget section text goes here
function  wp_ada_compliance_basic_widget_text() {

}   

// filter section text goes here
function  wp_ada_compliance_basic_filtertext() {
echo '<p class="ada_compliance_settings_text">';
_e('Options for automatically correcting issues found on your website are only available in the full version.', 'wp-ada-compliance-basic');	
echo '</p>';

}	

// filter database content
function  wp_ada_compliance_basic_settings_filter_content() {
echo '<p class="adamarketingtext">'.__('Upgrade to the full version to unlock content filters. Content filters will automatically correct many common issues and save you hours of work. <a href="https://www.alumnionlineservices.com/faq/will-the-wp-ada-compliance-plugin-correct-all-the-issues-on-my-website/">'.__('View a complete list of issues that are corrected automatically','wp-ada-compliance-basic').'</a> or refer to our ','wp-ada-compliance-basic').'<a href="https://www.alumnionlineservices.com/version-comparison/">'.__('version comparison tool','wp-ada-compliance-basic').'</a> '.__('for a complete list of features.','wp-ada-compliance-basic').'</p>';	
    
    echo '<h2>';
	_e('Key options in WP ADA Compliance Pro: ', 'wp-ada-compliance-basic');
	echo '</h2>';
	echo '<p><img src="'.plugin_dir_url(__FILE__).'/filters.png" width="1000" height="1000" alt="'.__('Content Filter Settings','wp-ada-compliance-basic').'"></p>';   echo '<p><img src="'.plugin_dir_url(__FILE__).'/filters2.png" width="957" height="851" alt="'.__('Content Filter Settings Screen 2','wp-ada-compliance-basic').'"></p>';
	echo '<p><img src="'.plugin_dir_url(__FILE__).'/filters3.png" width="957" height="851" alt="'.__('Content Filter Settings Screen 3','wp-ada-compliance-basic').'"></p>'; 
		echo '<p><img src="'.plugin_dir_url(__FILE__).'/filters4.png" width="957" height="851" alt="'.__('Content Filter Settings Screen 4','wp-ada-compliance-basic').'"></p>'; 
			echo '<p><img src="'.plugin_dir_url(__FILE__).'/filters5.png" width="957" height="851" alt="'.__('Content Filter Settings Screen 5','wp-ada-compliance-basic').'"></p>'; 
}

// display accessibility widget settings
function wp_ada_compliance_basic_settings_use_accessibility_widget(){

	echo '<p class="adamarketingtext">'.__('Upgrade to the full version to enable the web accessibility widget. The web accessibility widget is displayed on your website and includes a statement of your commitment to accessibility, a link to report problems and toolbar with a variety of options to further enhance the accessibility of the website.','wp-ada-compliance-basic').'</p>';
	
	echo '<p style="margin: 40px;"><img src="'.plugin_dir_url(__FILE__).'/widget.png" width="396" height="667" alt="'.__('Web Accessibility Widget','wp-ada-compliance-basic').'"></p>';
}

// display scan and notification option text
function wp_ada_compliance_basic_settings_full_scan_post_count(){
	echo '<p class="adamarketingtext">'.__('Upgrade to the full version to unlock password protected website and the automatic scan features. Password protected website scans allow websites that require a Wordpress login or Apache Basic Authentication to be scanned. Automatic scans will monitor your website for issues while you are offline and send detailed reports to you by email.','wp-ada-compliance-basic').'</p>';
        echo '<h2>';
	_e('Key options in WP ADA Compliance Pro: ', 'wp-ada-compliance-basic');
	echo '</h2>';
			echo '<p><img src="'.plugin_dir_url(__FILE__).'/autosettings.png" width="963" height="655" alt="'.__('Automatic Scan Settings such as cron frequency and number of items to scan each cycle.','wp-ada-compliance-basic').'"></p>';
			echo '<p><img src="'.plugin_dir_url(__FILE__).'/passwordprotected.png" width="952" height="432" alt="'.__('Password Protected Settings include enabling Wordpress password and apache basic authentication.','wp-ada-compliance-basic').'"></p>';
}

// display settings deep scan (scan with wpget)
/*
function  wp_ada_compliance_basic_settings_deep_scan() {
global $wp_ada_compliance_basic_unsupported_deepscan;
	
$wp_ada_compliance_basic_deep_scan = get_option('wp_ada_compliance_basic_deep_scan','basic');
	
echo '<fieldset>';
echo '<legend>';
_e('Choose a content scan type:', 'wp-ada-compliance-basic');	
echo '</legend>';	
echo '<br />';
	
echo '<input type="radio" name="wp_ada_compliance_basic_deep_scan" id="wp_ada_compliance_basic_deep_scan_deep" value="deep" disabled><label for="wp_ada_compliance_basic_deep_scan_deep">';
_e('Deep Scan (Slower but thorough scan, will identify issues found in page content and theme files.)', 'wp-ada-compliance-basic');
echo '</label> <br />';	
	
echo '<input type="radio" name="wp_ada_compliance_basic_deep_scan" id="wp_ada_compliance_basic_deep_scan_basic" value="basic" checked ><label for="wp_ada_compliance_basic_deep_scan_basic">';
_e('Basic Scan (Scan of database fields only, will not identify issues found in theme files.)');
echo '</label>';	
	
echo '</fieldset>';


}*/

// scan type section text goes here
function  wp_ada_compliance_basic_scantypetext() {
// purge scan records	
wp_ada_compliance_basic_purge_records();
		echo '<h2 class="adaRedText"><a href="https://www.alumnionlineservices.com/php-scripts/wordpress-wp-ada-compliance-check/">';
	_e('Upgrade to the full version ', 'wp-ada-compliance-basic');
	echo '</a>';
	_e('to unlock all the great features of this plugin! ', 'wp-ada-compliance-basic');
	echo '</h2>';
	echo '<p class="ada_compliance_settings_text">';
_e('Choose the website content to be monitored.', 'wp-ada-compliance-basic');	
echo '</p>';	
}

// number of errors per page to display
function  wp_ada_compliance_basic_settings_errors_per_page() {
$errors = get_option('wp_ada_compliance_basic_errors_per_page','15');
echo '<p>';
	echo '<label for="wp_ada_compliance_basic_errors_per_page">';
_e('Number of errors to display on report page: ', 'wp-ada-compliance-basic');	
echo '</label>';
	echo '<br />';	
	echo '<input type="text" size="25" name="wp_ada_compliance_basic_errors_per_page" id="wp_ada_compliance_basic_errors_per_page" value="'.esc_attr($errors).'" />';
echo '</p>';
}


// display settings to remove linking of images to file
function  wp_ada_compliance_basic_settings_starting_H_level() {

$starting_H_level = get_option('wp_ada_compliance_basic_starting_H_level','h2');

echo '<fieldset>';
echo '<legend>';
_e('The page title should always be wrapped in H1 elements and be displayed at the top of each page with the exception of the home page which may have the title of your website inside H1 elements. Most Wordpress themes are designed to place the page title automatically. If your theme does not place the page title and it is instead entered manually using the Wordpress editor this option should be changed to H1.', 'wp-ada-compliance-basic');
echo '</legend>';	
echo '<br />';
_e('Starting heading level in your Wordpress page editor (normally H2): ', 'wp-ada-compliance-basic');	
echo '<br />';	
	echo '<input type="radio" name="wp_ada_compliance_basic_starting_H_level" id="wp_ada_compliance_basic_starting_H_level_h1" value="h1" ';
if($starting_H_level == 'h1') echo ' checked';
echo '><label for="wp_ada_compliance_basic_starting_H_level_h1">';
_e('H1', 'wp-ada-compliance-basic');
echo '</label> ';
	echo '<input type="radio" name="wp_ada_compliance_basic_starting_H_level" id="wp_ada_compliance_basic_starting_H_level_h2" value="h2" ';
if($starting_H_level == 'h2') echo ' checked';
echo '><label for="wp_ada_compliance_basic_starting_H_level_h2">';
_e('H2', 'wp-ada-compliance-basic');
echo '</label> ';	
echo '</fieldset>';


}	
// enable wave evaluation tool
function  wp_ada_compliance_basic_settings_enablewave() {


$enablewave = get_option('wp_ada_compliance_basic_enablewave','true');

echo '<fieldset>';
echo '<legend>';
_e('Enable external evaluation tools and validator links (i.e... WAVE Web Accessibility Evaluation Tool and W3C Validator)', 'wp-ada-compliance-basic');	
echo '</legend>';	
echo '<br />';
echo '<input type="radio" name="wp_ada_compliance_basic_enablewave" id="wp_ada_compliance_basic_enablewave_true" value="true" ';
if($enablewave == 'true') echo ' checked';
echo '><label for="wp_ada_compliance_basic_enablewave_true">';
_e('Yes', 'wp-ada-compliance-basic');
echo '</label> ';
echo '<input type="radio" name="wp_ada_compliance_basic_enablewave" id="wp_ada_compliance_basic_enablewave_false" value="false" ';
if($enablewave == 'false') echo ' checked';
echo '><label for="wp_ada_compliance_basic_enablewave_false">';
_e('No', 'wp-ada-compliance-basic');
echo '</label> ';	
echo '</fieldset>';


}

// retain settings when plugin is deleted
function  wp_ada_compliance_basic_settings_retain_settings() {

$retain_settings = get_option('wp_ada_compliance_basic_retain_settings','false');
echo '<fieldset>';
echo '<legend>';
_e('When using the "Add Plugin" file uploader to apply plugin updates you will need to delete the old version before uploading the new one. This option allows you to retain plugin settings when the plugin is deleted.  When permanently removing the plugin this setting should be changed so the database and all associated settings are removed.', 'wp-ada-compliance-basic');	
echo '</legend>';	
echo '<br />';
echo '<input type="radio" name="wp_ada_compliance_basic_retain_settings" id="wp_ada_compliance_basic_retain_settings_true" value="true" ';
if($retain_settings == 'true') echo ' checked';
echo '><label for="wp_ada_compliance_basic_retain_settings_true">';
_e('Retain settings when plugin is deleted.', 'wp-ada-compliance-basic');
echo '</label> <br />';
echo '<input type="radio" name="wp_ada_compliance_basic_retain_settings" id="wp_ada_compliance_basic_retain_settings_false" value="false" ';
if($retain_settings == 'false') echo ' checked';
echo '><label for="wp_ada_compliance_basic_retain_settings_false">';
_e('Remove settings when the plugin is deleted.', 'wp-ada-compliance-basic');
echo '</label> ';	
echo '</fieldset>';

}

// display errors settings autorized user list
function  wp_ada_compliance_basic_settings_settingsusers() {

$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');

echo '<fieldset>';
echo '<legend>';
_e('Choose the minimum role that can modify plugin settings: ', 'wp-ada-compliance-basic');	
echo '</legend>';	
echo '<br />';
	echo '<input type="radio" name="wp_ada_compliance_basic_settingsusers" id="wp_ada_compliance_basic_settingsusers_editor" value="edit_pages" ';
if($settingsuser == 'edit_pages') echo ' checked';
echo '><label for="wp_ada_compliance_basic_settingsusers_editor">';
_e('Editors', 'wp-ada-compliance-basic');
echo '</label> ';
	echo '<input type="radio" name="wp_ada_compliance_basic_settingsusers" id="wp_ada_compliance_basic_settingsusers_admin" value="manage_options" ';
if($settingsuser == 'manage_options' or $settingsuser == 'update_core') echo ' checked';
echo '><label for="wp_ada_compliance_basic_settingsusers_admin">';
_e('Administrators', 'wp-ada-compliance-basic');
echo '</label> ';	
echo '</fieldset>';


}

	// foreground color
function  wp_ada_compliance_basic_settings_foreground_color() {
$foreground_color = get_option('wp_ada_compliance_basic_foreground_color','#000000');
echo '<p>';
	_e('While checking for a contrast ratio of 4.5:1 between page text and the background color it is assumed that the foreground is set to black. Change this setting if the content area of your website uses a foreground color other than black. This setting does not apply to colors set in css files, rather only to text that is colored within the page editor.', 'wp-ada-compliance-basic');
       // echo ' <i class="fas fa-plus-circle adanewitem"> '.__('new setting', 'wp-ada-compliance-basic').' </i>';
	echo '<br /><label for="wp_ada_compliance_basic_foreground_color">';
_e('Content area foreground color:', 'wp-ada-compliance-basic');	
echo '</label><br />';
	echo '<input type="text" size="25" name="wp_ada_compliance_basic_foreground_color" id="wp_ada_compliance_basic_foreground_color" value="'.esc_attr($foreground_color).'" />';
echo '</p>';
}

	// background color
function  wp_ada_compliance_basic_settings_background_color() {
$background_color = get_option('wp_ada_compliance_basic_background_color','#ffffff');
echo '<p>';
	_e('While checking for a contrast ratio of 4.5:1 between page text and the background color it is assumed that the background is set to white. Change this setting if the content area of your website uses a background color other than white. This setting does not apply to colors set in css files, rather only to text that is colored within the page editor.', 'wp-ada-compliance-basic');
	echo '<br /><label for="wp_ada_compliance_basic_background_color">';
_e('Content area background color:', 'wp-ada-compliance-basic');	
echo '</label><br />';
	echo '<input type="text" size="25" name="wp_ada_compliance_basic_background_color" id="wp_ada_compliance_basic_background_color" value="'.esc_attr($background_color).'" />';
echo '</p>';
}

// language code
function  wp_ada_compliance_basic_settings_language_code() {
$languagecode = get_option('wp_ada_compliance_basic_language_code','en');
echo '<p>';
	echo '<label for="wp_ada_compliance_basic_language_code">';
_e('Primary language code for the website: ', 'wp-ada-compliance-basic');	
echo '(<a href="https://www.w3schools.com/tags/ref_language_codes.asp">';
_e('Language Code Reference','wp-ada-compliance-basic');
echo'</a>)';
echo '</label><br />';
	echo '<input type="text" size="25" name="wp_ada_compliance_basic_language_code" id="wp_ada_compliance_basic_language_code" value="'.esc_attr($languagecode).'" />';
echo '</p>';
}
	

// include attachments in check title scan
function  wp_ada_compliance_basic_settings_attachmenttitles() {

$attachmenttitles = get_option('wp_ada_compliance_basic_attachmenttitles','false');

echo '<fieldset>';
echo '<legend>';
_e('Include attachments when checking for duplicate or missing titles.', 'wp-ada-compliance-basic');
				echo '<span class="adaAllGood">';
_e(' (Only necessary when your theme presents public facing attachment pages.)','wp-ada-compliance-basic');	
echo '</span>';
echo '</legend>';	
echo '<br />';
	echo '<input type="radio" name="wp_ada_compliance_basic_attachmenttitles" id="wp_ada_compliance_basic_attachmenttitles_yes" value="true" ';
if($attachmenttitles == 'true') echo ' checked';
echo '><label for="wp_ada_compliance_basic_attachmenttitles_yes">';
_e('Yes', 'wp-ada-compliance-basic');
echo '</label> ';
echo '<input type="radio" name="wp_ada_compliance_basic_attachmenttitles" id="wp_ada_compliance_basic_attachmenttitles_no" value="false" ';
if($attachmenttitles == 'false') echo ' checked';
echo '><label for="wp_ada_compliance_basic_attachmenttitles_no">';
_e('No', 'wp-ada-compliance-basic');
echo '</label> ';
echo '</fieldset>';


}

// display post types  field
function  wp_ada_compliance_basic_settings_posttypes() {
$checked = get_option('wp_ada_compliance_basic_posttypes',array('page','post'));	

echo '<fieldset>';
echo '<legend>';
_e('Choose the post types to be monitored: (Limited to 15 in the basic version)', 'wp-ada-compliance-basic');
echo '</legend>';

global $wpdb;

$post_type_list = array();
	// set default post types
$post_type_list[] = "post";
$post_type_list[] = "page";

foreach ( $post_type_list as $post_type ) {
	if(post_type_supports( $post_type, 'editor' )){	
		
	   echo '<p><label for="wp_ada_compliance_basic_posttypes_'.esc_attr($post_type).'"><input id="wp_ada_compliance_basic_posttypes_'.esc_attr($post_type).'" type="checkbox" class="posttypeselector" name="wp_ada_compliance_basic_posttypes[]" value="'.esc_attr($post_type).'"';
	   if(is_array($checked) and in_array($post_type, $checked)) echo ' checked="checked"' ;
	   echo ' /> '.esc_attr($post_type).'</label></p>';
	}
   }
echo '</fieldset>';
echo '<p class="adamarketingtext">'.__('Upgrade to the full version to remove the scan limits and enable scan options for your entire website. The full version will identify errors in theme files, shortcodes, custom post types, widgets, archives, CSS files, PDF files and other files outside of Wordpress that are linked from your website but on the same domain. The full version has no limit on the number of pages or posts that may be scanned. ','wp-ada-compliance-basic').'</p>';	
	    echo '<h2>';
	_e('Key options in WP ADA Compliance Pro: ', 'wp-ada-compliance-basic');
	echo '</h2>';
			echo '<p><img src="'.plugin_dir_url(__FILE__).'/content.png" width="963" height="655" alt="'.__('Content to Monitor Settings, including selection of custom post types, acrives, terms, links, CSS and PDF files.','wp-ada-compliance-basic').'"></p>';
}

/************************************************
define scan rules
*************************************************/
function wp_ada_compliance_basic_settings_scan_rules() {
global $wp_ada_compliance_basic_def;

// update ignore rules
//wp_ada_compliance_basic_update_scan_rule_ignore_options();
	// set default options
	wp_ada_compliance_basic_set_scan_rule_options();
	
$scanoptions = get_option('wp_ada_compliance_basic_scan_rules', array());
$ignore_rules = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());	
echo '<fieldset>';
echo '<legend style="font-weight:bold;">';
_e('Choose the issues to look for while scanning content on your website.', 'wp-ada-compliance-basic');	
echo '</legend>';
echo '<div class="adaReferenceReport">';
echo '<p class="wp_ada_error_key">';
		echo '<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ';
		_e('ALERTS - issues that MAY BE corrected to improve web accessibility, enhance a user\'s experience or avoid the possibility of inaccessible content inadvertently being introduced into a website.','wp-ada-compliance-basic');	
				echo '<br /><br />';
		echo '<i class="fas fa-ban" aria-hidden="true"></i> ';
		_e('WARNINGS - issues that MUST BE corrected to ensure compliance with Section 508 or WCAG 2.1 LEVEL A/AA Web Accessibility Standards and ensure content is accessible to users with disabilities.','wp-ada-compliance-basic');	
		echo '</p>';
if(is_array($wp_ada_compliance_basic_def))
foreach ($wp_ada_compliance_basic_def as $rows => $row){	
echo '<div class="adaReference">';	
echo '<input type="checkbox" class="wp_ada_scan_rules" name="wp_ada_compliance_basic_scan_rules[]" id="wp_ada_compliance_basic_scan_rules_'.esc_attr($rows).'" value="'.esc_attr($rows).'"';
if(!in_array($rows,$ignore_rules)) echo ' checked="checked"';
echo '>';	
	echo '<label for="wp_ada_compliance_basic_scan_rules_'.esc_attr($rows).'">';
	if(strstr($row['DisplayError'],"WARNING")) echo '<i class="fas fa-ban" aria-hidden="true"></i>';
		elseif(strstr($row['DisplayError'],"ALERT")) echo '<i class="fas fa-exclamation-circle" aria-hidden="true"></i>';
	if(isset($row['Settings']) and $row['Settings'] != "") echo esc_html($row['Settings']);
	else echo esc_html($row['StoredError']);
	if($row['Reference'] != "") echo ' <a href="'.esc_url($row['ReferenceURL']).'" target="_blank" class="adaNewWindowInfo">'.esc_html($row['Reference']).' <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
	echo '</label>';
	echo '</div>';	
}
echo '</div>';
echo '</fieldset>';	
}


/**********************************************
CREATE MENU LINKS AND PAGES
**********************************************/
function wp_ada_compliance_basic_admin_menu() {
	$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');
	
	if($settingsuser == "") $settingsuser =	'manage_options';
  
	if(current_user_can('edit_pages')){ 
	// web accessibility heading link
	add_menu_page( __('WP ADA Compliance Report', 'wp-ada-compliance-basic'), __('Web Accessibility Basic','wp-ada-compliance-basic'), 'edit_pages', 'ada_compliance/compliancereportbasic.php', 'wp_ada_compliance_basic_report_page', 'dashicons-media-document', 10 );
	
	// submenu links
	// error report link
	add_submenu_page('ada_compliance/compliancereportbasic.php', __('Error Report', 'wp-ada-compliance-basic'), __('Error Report','wp-ada-compliance-basic'), 'edit_pages', 'ada_compliance/compliancereportbasic.php', 'wp_ada_compliance_basic_report_page' );
	
		// issue reference link
	add_submenu_page('ada_compliance/compliancereportbasic.php', __('ADA Compliance Guidelines Reference', 'wp-ada-compliance-basic'), __('Error References','wp-ada-compliance-basic'), 'read', 'ada_compliance/compliancereferencereportbasic.php', 'wp_ada_compliance_basic_referencereport_page' );
	
		//  settings link
	add_submenu_page('ada_compliance/compliancereportbasic.php', __('Settings', 'wp-ada-compliance-basic'), __('Settings','wp-ada-compliance-basic'), $settingsuser, 'wp-ada-compliance-basic-admin', 'wp_ada_compliance_basic_settings_page' );	
	
	// send report page (hidden from menu)
	add_submenu_page(null, __('Send Report', 'wp-ada-compliance-basic'), __('Send Report','wp-ada-compliance-basic'), 'edit_pages', 'ada_compliance/send-report.php', 'wp_ada_compliance_basic_send_report' );
		
					// print report page (hidden from menu)
	add_submenu_page(null, __('Print Report', 'wp-ada-compliance-basic'), __('Print Report','wp-ada-compliance-basic'), 'edit_pages', 'ada_compliance/print-report.php', 'wp_ada_compliance_basic_print_report' );
	}

}
// webaim validation link and other external links
function wp_ada_compliance_basic_add_external_link_admin_submenu() {
    global $submenu;
    
     $permalink = get_site_url().'/wp-admin/upload.php?mode=list&attachment-filter&attachment-filter=post_mime_type%3Aimage&m=0&accessibility-filter=viewall&filter_action=Filter';
    $submenu['ada_compliance/compliancereportbasic.php'][] = array( __('Image Accessibility Issues','wp-ada-compliance-basic'), 'edit_pages',  esc_url($permalink) );
	
	$enablewave = get_option('wp_ada_compliance_basic_enablewave','true');
	if($enablewave == 'true'){
    $permalink = 'http://wave.webaim.org/report#/'.get_site_url();
    $submenu['ada_compliance/compliancereportbasic.php'][] = array( __('Evaluate with WAVE','wp-ada-compliance-basic'), 'edit_pages', esc_url($permalink) );
	}
	
	$permalink = 'https://www.access-board.gov/guidelines-and-standards/communications-and-it/about-the-ict-refresh/final-regulatory-impact-analysis#_Toc377046563';
    $submenu['ada_compliance/compliancereportbasic.php'][] = array( __('Section 508 Reference','wp-ada-compliance-basic'), 'edit_pages', esc_url($permalink) );
	
	$permalink = 'https://www.w3.org/WAI/WCAG21/quickref/';
    $submenu['ada_compliance/compliancereportbasic.php'][] = array( __('WCAG 2.1 Reference','wp-ada-compliance-basic'), 'edit_pages', esc_url($permalink) );
     
    $permalink = 'https://www.alumnionlineservices.com/accessibility/free-options/';
    $submenu['ada_compliance/compliancereportbasic.php'][] = array( __('About this Plugin','wp-ada-compliance-basic'), 'edit_pages',  esc_url($permalink) );

	
}
 
/************************************************************
// add seeting link to menu
**********************************************************/
function  wp_ada_compliance_basic_admin_add_page() {
$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');	
add_options_page(__('Web Accessibility Settings', 'wp-ada-compliance-basic'), __('Web Accessibility Settings', 'wp-ada-compliance-basic'), $settingsuser, 'wp-ada-compliance-basic-admin', 'wp_ada_compliance_basic_options_page');
		
}
/************************************************************
// display the admin options page
**********************************************************/
function  wp_ada_compliance_basic_options_page() {
		// reset data
	  if (isset($_GET[ 'purge' ])) {
		update_option( 'wp_ada_compliance_basic_cron_count', 0);	
		update_option( 'wp_ada_compliance_basic_scan_increment', 0); 
		wp_ada_compliance_basic_purge();
		  wp_ada_compliance_basic_admin_notices();
		   $_SERVER['REQUEST_URI'] = str_replace('&purge=1', '', $_SERVER['REQUEST_URI']);
          
           echo '<p class="notice notice-success">'; 
               _e('Report data was purged', 'wp-ada-compliance-basic');
          echo '</p>';
	  }
	
	// reset settings
	  if (isset($_GET[ 'reset_settings' ])) {
		 wp_ada_compliance_basic_remove_options();
	    $_SERVER['REQUEST_URI'] = str_replace('&reset_settings=1', '', $_SERVER['REQUEST_URI']);
          
        echo '<p class="notice notice-success">'; 
               _e('Settings were reset to default', 'wp-ada-compliance-basic');
          echo '</p>';
 		} 
	
	echo '<div class="wp-ada-compliance-settings-page">';
	echo  '<p>';
	
	
	
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php" class="btnwpada btnwpada-primary"><i class="fas fa-file-alt" aria-hidden="true"></i> ';
	_e('View Report', 'wp-ada-compliance-basic');
	echo '</a> ';
	
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=wp-ada-compliance-basic-admin&purge=1" onclick="return confirm(\'';
	_e('Are you sure you want to reset all error data?', 'wp-ada-compliance-basic');
	echo '\')" onkeypress="return confirm(\'';
	_e('Are you sure you want to reset all error data?', 'wp-ada-compliance-basic');
	echo '\')" class="btnwpada btnwpada-primary"><i class="fas fa-eraser" aria-hidden="true"></i> ';
	_e('Reset Report Data', 'wp-ada-compliance-basic');
	echo '</a> ';
	
		// reset options
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=wp-ada-compliance-basic-admin&reset_settings=1" onclick="return confirm(\'';
	_e('Are you sure you want to reset plugin settings?', 'wp-ada-compliance-basic');
	echo '\')" onkeypress="return confirm(\'';
	_e('Are you sure you want to reset plugin settings?', 'wp-ada-compliance-basic');
	echo '\')" class="btnwpada btnwpada-primary"><i class="fas fa-cog" aria-hidden="true"></i> ';
	_e('Reset Settings', 'wp-ada-compliance-basic');
	echo '</a> ';
    
    	echo ' <a href="https://www.alumnionlineservices.com/faq/how-do-i-install-the-wp-ada-compliance-plugin/" class="btnwpada btnwpada-primary" style="background-color: #C91215"><i class="fas fa-key" aria-hidden="true"></i> '.__('License Key (Install Full Version)','wp-ada-compliance-basic').'</a>';
	
	echo '</p>';
			// display system requirement issues and additional setup:
	wp_ada_compliance_basic_check_server_requirements();
echo '
<div>
<form action="options.php" method="post" id="ada_compliance_options">';
settings_fields('wp_ada_compliance_basic_options');
wp_ada_compliance_basic_do_settings_sections_tabs('wp_ada_compliance_basic');
 echo '<input id="adasettsingsave" name="Submit" type="submit" value="';
_e('Save Changes', 'wp-ada-compliance-basic');
echo '" />
</form></div>';
echo '<a id="adascrollbutton" aria-label="Top"></a>';		
echo '</div>';
}
/***********************************************************
change default roll to edit settings
***********************************************************/
function wp_ada_compliance_basic_set_role(){
$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');	

	if($settingsuser == "") return	'manage_options';
	
return	$settingsuser;
}

// register endpoint to rescan a document 
add_action( 'rest_api_init', function () {
	
// register endpoint to scan a document using jquery 
  register_rest_route( 'wp_ada_compliance_basic/v1', '/startscan', array(
    'methods' => 'GET',
    'callback' => 'wp_ada_compliance_basic_rest_start_scan',
	  'permission_callback' => function () {
			return current_user_can( 'edit_pages' );
		}

  ) );	
	
  register_rest_route( 'wp_ada_compliance_basic/v1', '/rescan', array(
    'methods' => 'GET',
    'callback' => 'wp_ada_compliance_basic_rest_start_single_scan',
	  'permission_callback' => function () {
			return current_user_can( 'edit_pages' );
		}

  ) );
} );

// register endpoint to update report upon return
add_action( 'rest_api_init', function () {
  register_rest_route( 'wp_ada_compliance_basic/v1', '/refreshreport', array(
    'methods' => 'GET',
    'callback' => 'wp_ada_compliance_basic_refresh_report_page',
	  'permission_callback' => function () {
			return current_user_can( 'edit_pages' );
		}

  ) );
} );

// register endpoints to update ignore status
add_action( 'rest_api_init', function () {
  register_rest_route( 'wp_ada_compliance_basic/v1', '/ignore', array(
    'methods' => 'GET',
    'callback' => 'wp_ada_compliance_basic_ignore',
	  'permission_callback' => function () {
			return current_user_can( 'edit_pages' );
		}

  ) );
} );
// ignore documents
function wp_ada_compliance_basic_ignore(){
	
	check_ajax_referer('wp_rest', '_wpnonce');
	
	// ignore errors
	if(isset($_GET[ 'wpadaignore' ])){
	 $values = explode('_',sanitize_text_field($_GET[ 'wpadaignore' ])); 
    $values[1] = (int)$values[1];
    $values[2] = (int)$values[2];
	
	if($values[2] == 0) $setting = 1;
	if($values[2] == 1) $setting = 0;
	if($values[2] == 2) $setting = 1;	
	
	wp_ada_compliance_basic_jquery_ignore_error($values[1], $setting);
		
	// remove records no longer being scanned
	 wp_ada_compliance_basic_purge_records();	
	
	return $values[1];
	}
	
	
	if ( isset( $_GET[ 'wpadaignorerule' ] ) ) {
		
		$values = explode('|',sanitize_text_field($_GET[ 'wpadaignorerule' ])); 
		
		wp_ada_compliance_basic_ignore_scan_rule($values[2]);
		
			// remove records no longer being scanned
	 wp_ada_compliance_basic_purge_records();	
		
		return $values[2];

	}	

}

/************************************************************
do tabbed sections
**************************************************************/

/** Replace the call to 'do_settings_sections()' with a call to this function */
function wp_ada_compliance_basic_do_settings_sections_tabs($page){

    global $wp_settings_sections, $wp_settings_fields;

    if(!isset($wp_settings_sections[$page])) :
        return;
    endif;

    echo '<div id="abb-tabs">';
    echo '<ul>';

    foreach((array)$wp_settings_sections[$page] as $section) :

        if(!isset($section['title']))
            continue;

        printf('<li><a href="#%1$s">%2$s</a></li>',
            $section['id'],     /** %1$s - The ID of the tab */
            $section['title']   /** %2$s - The Title of the section */
        );

    endforeach;
    
    echo '</ul>';

    foreach((array)$wp_settings_sections[$page] as $section) :

        printf('<div id="%1$s">',
            $section['id']      /** %1$s - The ID of the tab */
        );

        if(!isset($section['title']))
            continue;

        if($section['callback'])
            call_user_func($section['callback'], $section);

        if(!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]))
            continue;

        echo '<div class="form-table">';
        wp_ada_compliance_basic_do_settings_fields($page, $section['id']);
        echo '</div>';

        echo '</div>';

    endforeach;
	
    echo '</div>';

}

/**********************************************************
display wordpress settings without table
***********************************************************/
function wp_ada_compliance_basic_do_settings_fields( $page, $section ) {
    global $wp_settings_fields;
 
    if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
        return;
    }
 
    foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
        $class = '';
 
        if ( ! empty( $field['args']['class'] ) ) {
            $class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
        }
 
        echo "<div{$class}>";
 
        call_user_func( $field['callback'], $field['args'] );
 
        echo '</div>';
    }
}

/*********************************************************
 * Add a settings link to Plugins screen.
**********************************************************/
function wp_ada_compliance_basic_plugin_action_links( $links ) {
	global $wp_ada_compliance_basic_plugin_basename;
	$link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'admin.php?page=wp-ada-compliance-basic-admin' ) ),
		esc_html__( 'Settings', 'wp-ada-compliance-basic' )
	);


	array_unshift( $links, $link );


	return $links;
}
add_filter( 'plugin_action_links_' . $wp_ada_compliance_basic_plugin_basename, 'wp_ada_compliance_basic_plugin_action_links' );
?>