<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// check for accessibility help options
/********************************************************************/
function wp_ada_compliance_basic_validate_accessibility_help($content, $postinfo){

global $wp_ada_compliance_basic_def;

// ignore check when scanning database only
if($postinfo['scantype'] == 'onsave') return;
	
	
$dom = str_get_html($content);	
		
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());

// check if being scanned
if(in_array('accessibility_help', $wp_ada_compliance_basic_scanoptions)) return;

$links = $dom->find('a');
foreach ($links as $link) {
	if (isset($link) 
	and (stristr($link->plaintext, 'accessible') 
	or stristr($link->plaintext, 'accessibility') 
	or stristr($link->plaintext, 'contact')  
	or stristr($link->getAttribute('aria-label'), 'accessible') 
	or stristr($link->getAttribute('aria-label'), 'accessibility')  
   or stristr($link->getAttribute('aria-label'), 'contact')
   or stristr($link->getAttribute('href'), 'accessible') 
   or stristr($link->getAttribute('href'), 'accessibility')  
  or stristr($link->getAttribute('href'), 'contact')		  
  )) return;
}


$code =  __('Missing accessibility help options or contact form.', 'wp-ada-compliance-basic');

// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"accessibility_help", $code)){
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"accessibility_help", $wp_ada_compliance_basic_def['accessibility_help']['StoredError'], $code);
}
			
}	
?>
