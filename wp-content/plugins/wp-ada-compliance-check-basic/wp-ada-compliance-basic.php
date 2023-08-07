<?php
/*
Plugin Name: WP ADA Compliance Check Basic
Description: Comply with SECTION 508 and WC3/WCAG Web Accessibility Standards. This easy to use plugin evaluates pages for the most common issues as they are published. Upgrade to the full version to unlock all the great features including complete scans of your website pages, posts, media library images and custom post types.
Version: 3.0.7
  Plugin URI: https://wordpress.org/plugins/wp-ada-compliance-check-basic/
  Author: AlumniOnline Web Services LLC
  Author URI: https://www.alumnionlineservices.com/php-scripts/wordpress-wp-ada-compliance-check/
  Text Domain: wp-ada-compliance-basic
*/
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

// set debug status
/*
$wp_ada_compliance_basic_debug =1; 
if($wp_ada_compliance_basic_debug == 1){
error_reporting(E_ALL); 
ini_set('display_errors', 1);
}*/


/*********************************************
PLUGIN INSTALLATION
*******************************************/
register_activation_hook( __FILE__, 'wp_ada_compliance_basic_install' );
register_uninstall_hook( __FILE__, 'wp_ada_compliance_basic_uninstall');
/*********************************************
IMPORT RESOURCES
*******************************************/
// define update file path and basename
$wp_ada_compliance_basic_plugin_directory = __FILE__;
$wp_ada_compliance_basic_plugin_basename = plugin_basename( __FILE__ );

// add simple dom support (need to over ride max file size, if clashes with another install of simple dom there the max file size will be dependednt upon that installation)
if(!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 6000000);
if(!class_exists ('simple_html_dom'))
include_once(plugin_dir_path( __FILE__ ).'res/simplehtmldom/simple_html_dom.php');

include(plugin_dir_path( __FILE__ ).'res/compliance_descriptions.php');
include(plugin_dir_path( __FILE__ ).'res/installation.php');
include(plugin_dir_path( __FILE__ ).'res/dashboard.php');
include(plugin_dir_path( __FILE__ ).'res/security.php');
include(plugin_dir_path( __FILE__ ).'res/settings.php'); 
include(plugin_dir_path( __FILE__ ).'res/errors.php'); 
include(plugin_dir_path( __FILE__ ).'res/purgedata.php');
foreach ($wp_ada_compliance_basic_def as $rows => $row){	
if(file_exists(plugin_dir_path( __FILE__ ).'res/rules/'.$rows.'.php'))
	include(plugin_dir_path( __FILE__ ).'res/rules/'.$rows.'.php'); 
}
include(plugin_dir_path( __FILE__ ).'res/content_validation.php'); 
include(plugin_dir_path( __FILE__ ).'res/reports.php'); 
include(plugin_dir_path( __FILE__ ).'res/sendmail.php');
include(plugin_dir_path( __FILE__ ).'res/content_posts.php'); 
include(plugin_dir_path( __FILE__ ).'res/elementor_editor.php'); 
include(plugin_dir_path( __FILE__ ).'res/beaverbuilder_editor.php'); 
include(plugin_dir_path( __FILE__ ).'res/block-editor.php');
include(plugin_dir_path( __FILE__ ).'res/vendor/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php');

/*********************************************
FILTERS AND ACTIONS
*******************************************/
add_action( 'admin_init', array( 'PAnD', 'init' ) ); // load persistent admin notices
add_action( 'admin_enqueue_scripts', 'wp_ada_compliance_basic_admin_scripts' ); // import admin css file
add_action( 'wp_enqueue_scripts', 'wp_ada_compliance_basic_scripts' ); // import public css file
add_action( 'add_meta_boxes', 'wp_ada_compliance_basic_report_meta_box' ); // works in gutenburg and classic editor
add_action('admin_init', 'wp_ada_compliance_basic_admin_init'); // create admin settings
add_action( 'wp_loaded', 'wp_ada_compliance_basic_preprocessing' ); // monitor and process actions
add_filter( 'save_post', 'wp_ada_compliance_basic_validate_ada_post_compliance', 10, 2 ); // validate post content when saving
add_action('admin_menu', 'wp_ada_compliance_basic_admin_add_page'); // add admin page to menu
add_action( 'admin_menu', 'wp_ada_compliance_basic_admin_menu' ); // add admin submenu links to menu
add_action('admin_menu', 'wp_ada_compliance_basic_add_external_link_admin_submenu'); // add additional links to menu
add_action( 'update_option_wp_ada_compliance_basic_scan_rules', 'wp_ada_compliance_basic_update_scan_rule_ignore_options', 10, 2 ); // if scan rules change, update ignore settings
add_action('wp_dashboard_setup', 'wp_ada_compliance_basic_dashboard_widgets'); // add dashboard widget with stats
add_filter( "option_page_capability_wp_ada_compliance_basic_options", 'wp_ada_compliance_basic_set_role' ); // set capability for settings page
//add_action( 'delete_post', 'wp_ada_compliance_basic_remove_deleted_posts' );
add_action('admin_init', 'wp_ada_compliance_basic_check_version'); // check version and update update database as required
add_action( 'admin_notices', 'wp_ada_compliance_basic_admin_notices' ); // display messages / admin notices

// add scan link to post and page list
add_filter('post_row_actions', 'wp_ada_compliance_basic_add_post_editor_link', 10, 2);
add_filter('page_row_actions', 'wp_ada_compliance_basic_add_post_editor_link', 10, 2);

// delete error when post is trashed 
add_filter( 'pre_delete_post', 'wp_ada_compliance_basic_delete_post', 10, 3 ); 

// add admin body class for jquery and iframe
add_filter( 'admin_body_class','wp_ada_compliance_basic_add_body_classes' );

// set wordpress option when importing content to disable scans during import
add_action('import_start', 'wp_ada_compliance_basic_start_import'); 
add_action('import_end', 'wp_ada_compliance_basic_end_import'); 

/************************************************
managet requests and start processes
************************************************/
function wp_ada_compliance_basic_preprocessing() {
    	// load think box and set sesssion where required
    if(is_admin() 
	   and (strstr($_SERVER['REQUEST_URI'],'admin.php?page=ada_compliance') or
		   strstr($_SERVER['REQUEST_URI'],'admin.php?page=wp-ada-compliance-basic-admin') or
	  		strstr($_SERVER['REQUEST_URI'],'/wp-admin/post.php') or 
	   		strstr($_SERVER['REQUEST_URI'],'/wp-admin/term.php'))){
           
                // ensure import monitoring is disabled
        wp_ada_compliance_basic_end_import();
        
            if ( !session_id() ) session_start();
	// include thick box
	add_thickbox();	
	}
    
	if(wp_ada_compliance_basic_check_is_admin() and strstr($_SERVER['REQUEST_URI'],'admin.php?page=ada_compliance')){
		
	// ignore error
	if ( isset( $_GET[ 'wpada_ignore' ] )) {
		wp_ada_compliance_basic_ignore_error( (int)$_GET['wpada_ignore'] );
	}
		
	// ignore rules
	elseif ( isset( $_GET['wpada_ignore_rule'] )) {
       // validate input
        wp_ada_compliance_basic_form_values();
		$_GET[ 'wpada_ignore_rule' ] = sanitize_text_field($_GET[ 'wpada_ignore_rule' ]);
		wp_ada_compliance_basic_ignore_scan_rule($_GET[ 'wpada_ignore_rule' ]);
			// remove records no longer being scanned
	 wp_ada_compliance_basic_purge_records();	
		$_SESSION['my_ada_important_notices'] = __('The selected rule is now being ignored.','wp-ada-compliance-basic');
	}	
    elseif(isset( $_GET[ 'refresh' ] ) ){
		// remove records no longer being scanned
	 wp_ada_compliance_basic_purge_records();	
	}
	// start scan of single post
	if ( isset($_GET[ 'scansingle' ]) and isset($_GET['postid']) and isset($_GET['type'])) {
	
	wp_ada_compliance_basic_start_single_scan();
	}
	// start scan of content
	if ( isset($_GET[ 'startscan' ]) and !isset($_GET['cpage'])) {
	wp_ada_compliance_basic_start_scan();
	}	
	}

}
/************************************************
manual or autoscan scan 
************************************************/
//rest full scan
function wp_ada_compliance_basic_rest_start_scan() {
	
		
	check_ajax_referer('wp_rest', '_wpnonce');
    
	 wp_ada_compliance_basic_start_scan(); 
}
/************************************************
manual or autoscan scan 
************************************************/
function wp_ada_compliance_basic_start_scan() {
	
	if(isset($_GET['adacounter'])){
		$adacount = (int)$_GET['adacounter'];
	} else $adacount = 0;
    
    update_option('wp_ada_compliance_basic_rescan_required', 0);  
    
		wp_ada_compliance_basic_set_php_ini_Settings();
    
		wp_ada_compliance_basic_purge_records();
			
		// scan post content in database
		wp_ada_compliance_basic_scan_ada_compliance_post($adacount);
			
	
}

/************************************************
rest single scan
************************************************/
function wp_ada_compliance_basic_rest_start_single_scan() {
	
	check_ajax_referer('wp_rest', '_wpnonce');
	
	if(isset($_GET['wpadarescan'])){

	 $values = explode('|',sanitize_text_field($_GET['wpadarescan'])); 
	 	
	$_GET['type'] = $values[0];	
	$_GET['postid'] = $values[1];	
			
	 wp_ada_compliance_basic_start_single_scan(); 
	
					// clear error message
	if(isset($_SESSION['my_ada_notices'])) $_SESSION['my_ada_notices'] = "";
	}
}

/************************************************
manual single scan
************************************************/
function wp_ada_compliance_basic_start_single_scan() {
	wp_ada_compliance_basic_set_php_ini_Settings();
       
		// enforce post type check
	$post_types = get_option('wp_ada_compliance_basic_posttypes',array('page','post','attachment'));	
    
	if(!in_array($_GET['type'],$post_types)) {
        return 0;
	}
	
		
	if(!isset($_GET['type']) or !isset($_GET['postid'])) return 0;
	$postid = (int)$_GET['postid'];
		
	wp_ada_compliance_basic_validate_ada_post_compliance($postid);	
	
	// clear error message if not scanning on save	
if(!isset($_GET['wpadarescan'])){		
if(isset($_GET['startscan']) or isset($_GET['scansingle']) or !array_key_exists('scantype',$postinfo)) 
	$_SESSION['my_ada_notices'] = "";
}
}

/******************************************
// include css and scripts
****************************************/
function wp_ada_compliance_basic_scripts() {	
	
wp_register_style( 'wp-ada-compliance-styles',  plugin_dir_url( __FILE__ ) .  'styles.css', array(), filemtime( plugin_dir_path( __FILE__ ) .  'styles.css' ) );	
wp_enqueue_style( 'wp-ada-compliance-styles' );		

    // font awesome  
//wp_register_style( 'wp-ada-compliance-font-awesome-styles', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css', array(), '' );	
//wp_enqueue_style( 'wp-ada-compliance-font-awesome-styles' );	 
		 
}

/******************************************
// add admin body class for jquery and iframe
****************************************/
function wp_ada_compliance_basic_add_body_classes( $classes ) {


if(is_admin() and strstr($_SERVER['REQUEST_URI'],'admin.php?page=ada_compliance/compliancereportbasic.php') and strstr($_SERVER['REQUEST_URI'],'iframe=1')){	
	$classes .= ' wpadaIframe ';
}
	
    return $classes;
     
}

/******************************************
// include css and scripts for admin features
****************************************/
function wp_ada_compliance_basic_admin_scripts() {
    
// start sessions to display messages
if(is_admin() and !strstr($_SERVER['REQUEST_URI'],'theme-editor.php') and !strstr($_SERVER['REQUEST_URI'],'plugin-editor.php') and !strstr($_SERVER['REQUEST_URI'],'site-health.php') 
   and !strstr($_SERVER['REQUEST_URI'],'admin-ajax.php')){    
	
wp_register_style( 'wp-ada-compliance-basic-styles',  plugin_dir_url( __FILE__ ) .  'styles.css', array(), filemtime( plugin_dir_path( __FILE__ ) .  'styles.css' ) );	
wp_enqueue_style( 'wp-ada-compliance-basic-styles' );		


// font awesome  
wp_register_style( 'wp-ada-compliance-basic-fontawesome-styles',  'https://use.fontawesome.com/releases/v5.15.4/css/all.css', array(), '' );	
wp_enqueue_style( 'wp-ada-compliance-basic-fontawesome-styles' );	    
	
/* tabs for admin page */
 wp_enqueue_script( 'jquery-ui-tabs' );		
	
// scripts to use jquery to ignore		
wp_register_script( 'wp-ada-compliance-basic-scripts',  plugin_dir_url( __FILE__ ) .  'res/scripts.js', array('jquery'), filemtime( plugin_dir_path( __FILE__ ) .  'res/scripts.js' ) );
wp_enqueue_script( 'wp-ada-compliance-basic-scripts' );	
	
wp_localize_script( 'wp-ada-compliance-basic-scripts', 'wpadacompliancebasicVariables',array(
	'showsummary' =>'<i class="fas fa-toggle-off"></i> '.__('Show Summary','wp-ada-compliance-basic'),
	'hidesummary' =>'<i class="fas fa-toggle-on"></i> '.__('Hide Summary','wp-ada-compliance-basic'),
	'working' => __( '<span class="adaworking">&nbsp;</span> Please wait while the report is being refreshed' ,'wp-ada-compliance-basic'),
   'inprogress' => '<span class="adaworking">&nbsp;</span> '.__( 'SCAN IN PROGRESS - Upgrade to the full version to scan your entire website. Full scans will identify issues in theme files, shortcodes, widgets, archives, linked pages and PDF files. The automatic scan feature will monitor your website for issues while you are offline and send detailed email reports. The full version has no limit on the number of pages or posts that may be scanned.','wp-ada-compliance-basic'),
	'refresh' => '<i class="far fa-check-circle" aria-hidden="true"></i> '.__( 'The report data has been refreshed.' ,'wp-ada-compliance-basic'),
'ignoreerror' => '<i class="fas fa-info-circle" aria-hidden="true"></i> '.__( 'The selected item is now being ignored.' ,'wp-ada-compliance-basic'),
    'ignorestatus' => '<i class="fas fa-info-circle" aria-hidden="true"></i> '.__( 'Ignore status has been saved.' ,'wp-ada-compliance-basic'),
    		'recheck' => '<i class="far fa-check-circle" aria-hidden="true"></i> '.__( 'Scan is complete and error results have been updated. ' ,'wp-ada-compliance-basic').'<p class="adaRedText">'.__( ' The basic version is limited to scanning content in the Wordpress Editor only and includes 22 fewer error checks. Upgrade to the full version to identify issues in theme files, custom fields, shortcodes, widgets and other parts of your website.' ,'wp-ada-compliance-basic').'</p>',
		'unignoreerror' => '<i class="fas fa-info-circle" aria-hidden="true"></i> '.__( 'The selected item has been removed from the ignore list.' ,'wp-ada-compliance-basic'),
	'ignoreerrorthis' => __( 'This Error' ,'wp-ada-compliance-basic'),
		'ignoreerrortitle' => '<i class="fas fa-info-circle" aria-hidden="true"></i> '.__( 'Ignore this instance of the error.' ,'wp-ada-compliance-basic'),
	'unignoreerrortitle' => '<i class="fas fa-info-circle" aria-hidden="true"></i> '.__( 'Remove ignore from this error instance.' ,'wp-ada-compliance-basic'),
	'ignorerule' => '<i class="fas fa-info-circle" aria-hidden="true"></i> '.__('The selected rule is now being ignored. You may re-enable this rule under plugin settings.','wp-ada-compliance-basic'),
	'ignoreruleconfirm' => __('By continuing this error will be removed from the results and all future scans. You may re-enable this scan rule under plugin settings.','wp-ada-compliance-basic'),
	'scanning' => '<span class="adaworking">&nbsp;</span> '.__( 'Scan in Progress... WP ADA Compliance Basic includes a limited version of deep scan. It is slower but more through. Please be patient while the scan is completed.' ,'wp-ada-compliance-basic'),
		'scancompleteoverage' => '<i class="far fa-check-circle" aria-hidden="true"></i> '.__( 'SCAN IS COMPLETE: A maximum of 15 items were scanned. The basic version is limited to scanning content in the Wordpress Editor only and includes 22 fewer error checks. Upgrade to the full version to identify issues in theme files, custom fields, shortcodes, widgets and other parts of your website. The automatic scan feature will monitor your website for issues while you are offline and send detailed email reports.' ,'wp-ada-compliance-basic'),
	'scanstatus1' => '<span class="adaworking">&nbsp;</span> '.__( 'Scan in Progress... 5 of '.wp_ada_compliance_basic_count_total_scan_records().' completed' ,'wp-ada-compliance-basic'),
	'scanstatus2' => '<span class="adaworking">&nbsp;</span> '.__( 'Scan in Progress... 10 of '.wp_ada_compliance_basic_count_total_scan_records().' completed' ,'wp-ada-compliance-basic'),
	
	 'resturl' => esc_url_raw(get_rest_url()),
	'nonce' => wp_create_nonce( 'wp_rest' ) 
	));	
    
}
}

/**********************************************
display notices
********************************************/
function wp_ada_compliance_basic_admin_notices(){
$allowed_html = array(
  'a' => array('href' => array(), 'target' => array(), 'class' => array()),
  'p' => array('class' => array()),
'span' => array('class' => array()),	
	'i' => array('class' => array(), 'aria-hidden' => array()),
  'strong' => array(),
  'br' => array(),
	'h2' => array(),
);	
	
	// clear on iframe display
if(isset($_GET['iframe']) or !strstr($_SERVER['REQUEST_URI'],'/wp-admin/post.php?post=')){
	$_SESSION['my_ada_notices'] = "";
}	
if(!empty($_SESSION['my_ada_notices']) and (stristr($_SERVER['PHP_SELF'], "post.php"))) {
	 echo '<div class="adaError"><h2>';
	_e('Web Accessibility Compliance Issues:','wp-ada-compliance-basic');
	echo '</h2>';
	echo '<p class="wp_ada_version_message">';
	_e('Upgrade to the full version to enable deep scans on the editor screen, to auto correct issues and enable many time saving features. ','wp-ada-compliance-basic');
	echo '<a href="https://www.alumnionlineservices.com/php-scripts/wp-ada-compliance-check/">';
	_e('Learn more or upgrade to unlock time saving features. ','wp-ada-compliance-basic');
	echo'</a>';
	echo '</p>';
	echo wp_kses($_SESSION['my_ada_notices'], $allowed_html);
	echo '</div>';
  }
  elseif(!empty($_SESSION['my_ada_notices']) and stristr($_SERVER['PHP_SELF'], "admin.php")) {
	  echo '<div class="adaAllGood">';
	  echo wp_kses($_SESSION['my_ada_notices'], $allowed_html);
	  echo '</div>';
  }	
  if ( session_id() )	{
	unset ($_SESSION['my_ada_notices']);
  }
}

/*******************************************************
display html_validation notification
*********************************************************/
add_action( 'admin_notices', 'wp_ada_compliance_basic_html_validation_notification' );
function wp_ada_compliance_basic_html_validation_notification() {
    
if(!strstr($_SERVER['REQUEST_URI'],'admin.php?page=ada_compliance') and !strstr($_SERVER['REQUEST_URI'],'page=wp-ada-compliance-basic-admin') and !strstr($_SERVER['REQUEST_URI'],'/wp-admin/index.php')) return;
    
if (! PAnD::is_admin_notice_active( 'notice-wpadabasichtmlvalidation-30' ) ) {
		return;
	}	    

// html validation
if (!is_plugin_active('html-validation/html-validation.php') ) {
$notice = __('HTML code validation is an important part of ensuring ADA compliance of your website. Install the ', 'wp-ada-compliance-basic');
$notice .= '<a href="https://www.alumnionlineservices.com/php-scripts/html-validation/">';
$notice .= __('FREE HTML Validation plugin', 'wp-ada-compliance-basic');
$notice .= '</a>';
$notice .= __(' to find and correct HTML code issues.', 'wp-ada-compliance-basic');   

   
echo '<div data-dismissible="notice-wpadabasichtmlvalidation-30" class="notice notice-error is-dismissible wp-ada-compliance-notification" >';
echo $notice;
echo '</div>';
}
}

/*******************************************************
display facebook notification
*********************************************************/
add_action( 'admin_notices', 'wp_ada_compliance_basic_notification' );
function wp_ada_compliance_basic_notification() {
    
if(!strstr($_SERVER['REQUEST_URI'],'admin.php?page=ada_compliance')) return;	
    
if (! PAnD::is_admin_notice_active( 'notice-wpadabasicfacebook-30' ) ) {
		return;
	}	    
    
$wpa_ada_compliance_notice = __('WP ADA Compliance Basic is limited to 15 posts or pages, includes 22 fewer error checks and will not scan your entire website. For custom post types, archives, terms and content outside of Wordpress upgrade to the full version, use the WAVE web accessibility evaluation tool or our free single page scanner to identify issues.','wp-ada-compliance-basic');    
    
if(get_option('wp_ada_compliance_basic_rescan_required', 0) == 1){
    $wpa_ada_compliance_notice = '<span style="font-weight:bold">'.__('A new version of WP ADA Compliance Basic has been installed. Please rescan your website to take advantage of the new features and compliance checks that were added. ','wp-ada-compliance-basic').'</span> '.$wpa_ada_compliance_notice;
}   


   
echo '<div data-dismissible="notice-wpadabasicfacebook-30" class="notice notice-error is-dismissible wp-ada-facebook-like" >';
echo $wpa_ada_compliance_notice;
echo '</div>';

}
/************************************************
add links to post/page list edit.php
***********************************************/
function wp_ada_compliance_basic_add_post_editor_link($actions, $post){
if ( !current_user_can( "edit_pages" ) ) return $actions;	
	
$post_types = get_option('wp_ada_compliance_basic_posttypes',array('page','post','attachment'));	
if(!in_array($post->post_type,$post_types)) return $actions;	
	
$title = __('Scan for Web Accessibility Issues','wp-ada-compliance-basic');	
$actions['scanpost'] = '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance%2Fcompliancereportbasic.php&scansingle=1&postid=' . esc_attr($post->ID) . '&view=1&searchtitle=' . esc_attr($post->ID) . '&type=' . esc_attr($post->post_type) . '" title="'.esc_attr($title).'" >'.__('Check For Issues', 'wp-ada-compliance-basic').'</a>';
    
    $enablewave = get_option('wp_ada_compliance_basic_enablewave','true');
if($enablewave == 'true'){
$actions['evaluatewavepost'] = '<a href="http://wave.webaim.org/report#/'.esc_url(site_url()).'/?p='.esc_attr($post->ID).'">'.__('Evaluate with Wave','wp-ada-compliance-basic').'</a>';
    
// if html validation plugin not installed
if(!array_key_exists('htmlvalidate',$actions))    
$actions['w3cvalidatepost'] = '<a href="https://validator.w3.org/nu/?doc='.esc_url(site_url()).'/?p='.esc_attr($post->ID).'">'.__('Validate HTML','wp-ada-compliance-basic').'</a>';    
}
    
return $actions;
}

/******************************************
// remove startscan from paginate links
****************************************/
add_filter( 'paginate_links', 'wp_ada_compliance_basic_remove_query_args');
function wp_ada_compliance_basic_remove_query_args( $link ){
      
$link = filter_input( INPUT_GET, 'scansingle' )  ? remove_query_arg( 'scansingle', $link ): $link;
$link = filter_input( INPUT_GET, 'startscan' )  ? remove_query_arg( 'startscan', $link ): $link;      
    
    return $link;
}

/***********************************************************************************
// remove child nodes with simple dom
**********************************************************************************/
function wp_ada_compliance_basic_SimpleDOMRemoveChild(simple_html_dom_node $parentNode) {
$parentNode->innertext = '';
$error = $parentNode->save();
return $error;
}
/***********************************************************************************
// remove child nodes with php dom
**********************************************************************************/
function wp_ada_compliance_basic_DOMRemoveChild(DOMNode $parentNode) {

$newdoc = new DOMDocument();
libxml_use_internal_errors(true);
$newdoc->loadHTML("<ada></ada>");	
$cloned = $parentNode->cloneNode(TRUE);
	
 while ($cloned->hasChildNodes()) {
    $cloned->removeChild($cloned->firstChild);
  } 
$newdoc->appendChild($newdoc->importNode($cloned,TRUE));	
$error = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>','<ada>', '</ada>'), array('', '', '', '','',''), $newdoc->saveHTML()));
return $error;
}

/*************************************************************
get the inner html of a DOM element
*************************************************************/
function wp_ada_compliance_basic_GETinnerHTML(\DOMElement $element)
{
    $doc = $element->ownerDocument;

    $html = '';

    foreach ($element->childNodes as $node) {
        $html .= $doc->saveHTML($node);
    }

    return $html;
}

//check if is_admin or rest api for new editor
function wp_ada_compliance_basic_check_is_admin($ignore_is_admin = 0) {
if($ignore_is_admin == 0 and function_exists('is_admin') and is_admin()) return 1;	
  $prefix = rest_get_url_prefix( );
        if (defined('REST_REQUEST') && REST_REQUEST // (#1)
            || isset($_GET['rest_route']) // (#2)
                && strpos( trim( $_GET['rest_route'], '\\/' ), $prefix , 0 ) === 0)
            return true;

        // (#3)
        $rest_url = wp_parse_url( site_url( $prefix ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );

        if(!is_array($current_url) or !is_array($rest_url) or !array_key_exists('path',$current_url) or !array_key_exists('path',$rest_url)){
            return false;
        }

        return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	
}

/***************************************
support various editors guttenberg, beaver builder, elementor
*****************************************/
// register endpoints to update button on editor screens
add_action( 'rest_api_init', function () {
  register_rest_route( 'wp_ada_compliance_basic/v1', '/errorstatus/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'wp_ada_compliance_basic_update_report_button',
	   'permission_callback' => function () {
			return current_user_can( 'edit_pages' );
		}
  ) );
	// dispaly error notice
register_rest_route( 'wp_ada_compliance_basic/v1', '/displaynotice/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'wp_ada_compliance_basic_admin_notices_editor',
	 'permission_callback' => function () {
			return current_user_can( 'edit_pages' );
		}
  ) );
} );



// update button
function wp_ada_compliance_basic_update_report_button($data) {  
	
check_ajax_referer('wp_rest', '_wpnonce');	
	
$post = get_post((int)$data['id']);

if(wp_ada_compliance_basic_reported_errors_check($post->ID, $post->post_type, 1)){
    $button =  '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view=1&errorid='.esc_attr($post->ID).'&type='.esc_attr($post->post_type).'&iframe=1&TB_iframe=true&width=900&height=550" class="thickbox btnwpada btnwpada-warning adareportlink">';
	$button .= __('Accessibility Report','wp-ada-compliance-basic');
	$button .= '</a>';
	}  
	else{
	 $button =  '<i class="fas fa-thumbs-up btnwpada" style="font-size: 200%; background-color: #005700; color: #fff; width: 100px; padding: 5px;" aria-hidden="true" title="'.__('No Issues','wp-ada-compliance-basic').'"></i>';	
	}
	
	return $button;
}

// display error notice
function wp_ada_compliance_basic_admin_notices_editor($data) {  
	
check_ajax_referer('wp_rest', '_wpnonce');
	
$post = get_post((int)$data['id']);
$notice = '';
if(wp_ada_compliance_basic_reported_errors_check($post->ID, $post->post_type, 1)){
   $notice .= __('This page has one or more web accessibility problems. View the ','wp-ada-compliance-basic');
	$notice .=  '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view=1&errorid='.esc_attr($post->ID).'&type='.esc_attr($post->post_type).'&iframe=1&TB_iframe=true&width=900&height=550" class="thickbox adareportlink">';
	$notice .= __('Accessibility Report','wp-ada-compliance-basic');
	$notice .= '</a>';
		$notice .= '<p class="wp_ada_version_message">';
	$notice .= __('Upgrade to the full version to enable deep scans on the editor screen, to auto correct issues and enable many time saving features. ','wp-ada-compliance-basic');
	$notice .= '<a href="https://www.alumnionlineservices.com/php-scripts/wp-ada-compliance-check/">';
	$notice .= __('Learn more or upgrade to unlock time saving features. ','wp-ada-compliance-basic');
	$notice .='</a>';
	$notice .= '</p>';
	}  	
	return $notice;
}

/**************************************************
// set max execution time and memory limit
**************************************************/
function wp_ada_compliance_basic_set_php_ini_Settings($restart = 0) {
    
    if($restart == '1'){
        set_time_limit(300);
    }
    // set required php settings	
	if(ini_get('max_execution_time') < 300){
	ini_set('max_execution_time', 600); 
	}
    
if(wp_ada_compliance_basic_return_bytes(ini_get('memory_limit')) < 536870912){
	ini_set('memory_limit', '512M');	
    }
}

/*****************************************
// convert memory limit to bytes
*******************************************/
function wp_ada_compliance_basic_return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
         case 'g':
            $val *= 1024;
        case 'm':
           $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}


/******************************************
display promotion
*****************************************/
function wp_ada_compliance_basic_promotions() {
$promo = '';

/*   
// validate license once per week
$wp_ada_compliance_basic_promo_date = get_option('wp_ada_compliance_basic_promo_date','');
if($wp_ada_compliance_basic_promo_date == '') update_option('wp_ada_compliance_basic_promo_date',strtotime('15 days'));
    
if($wp_ada_compliance_basic_promo_date < strtotime('-30 days')) {
update_option('wp_ada_compliance_basic_promo_date',strtotime('30 days'));
}
if($wp_ada_compliance_basic_promo_date < time()) {    
$promo .='<h3 class="adaRedText"> <a href="https://www.alumnionlineservices.com/promotions/?basicpromo=1">';
$promo .=__('Upgrade today and save $10. ', 'wp-ada-compliance-basic');	
$promo .='</a>';
$promo .=__('This promo is available for a very limited time to new customers only.', 'wp-ada-compliance-basic');
$promo .='</h3>';	
}else{*/
$promo = '<h3 class="adaRedText"><a href="https://www.alumnionlineservices.com/php-scripts/wordpress-wp-ada-compliance-check/">';
$promo .= __('Upgrade to the full version ', 'wp-ada-compliance-basic');
$promo .='</a>';
$promo .=__('to unlock all the great features of this plugin! ', 'wp-ada-compliance-basic');
$promo .='</h3>'; 
/*} */   

return $promo; 
}
?>