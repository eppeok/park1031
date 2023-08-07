<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate skip nav links in theme files
/********************************************************************/
function wp_ada_compliance_basic_validate_skip_nav_links($content, $postinfo){

global $wp_ada_compliance_basic_def;
    
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());  

// check if being scanned
if(in_array('skip_nav_links', $wp_ada_compliance_basic_scanoptions) or ($postinfo['fullcontent'] == '')) return;    
    
$dom = str_get_html($postinfo['fullcontent']);
	
$code = '';	
$missingtargetid = '';	
$skiplinks = $dom->find('a[class*=skip], a[plaintext*=skip], a[plaintext*=Skip]');		
if(!$skiplinks) 
$code .=  __('Missing skip links. ', 'wp-ada-compliance');
     
// save error
if($code != ''){   
	
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"skip_nav_links", $code)){
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"skip_nav_links", $wp_ada_compliance_basic_def['skip_nav_links']['StoredError'], $code);	
}
}
}
?>