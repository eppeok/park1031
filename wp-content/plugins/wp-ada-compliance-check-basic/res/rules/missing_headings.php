<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate page structure, look for missing headings
/********************************************************************/	
function wp_ada_compliance_basic_validate_missing_headings($content, $postinfo){

// check only posts and when running a database scan
if($postinfo['scantype'] != 'onsave' 
   or $postinfo['type'] == "css" 
   or $postinfo['type'] == "term" 
   or $postinfo['type'] == "widget"
  or  $postinfo['type'] == "customfield"
  or strlen($content) < 1500) return 1;	
	
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('missing_headings', $wp_ada_compliance_basic_scanoptions)) return 1;	
	
$h1 = count($dom->find('h1,[role=heading][aria-level=1]'));
$h2 = count($dom->find('h2,[role=heading][aria-level=2]'));
$h3 = count($dom->find('h3,[role=heading][aria-level=3]'));
$h4 = count($dom->find('h4,[role=heading][aria-level=4]'));
$h5 = count($dom->find('h5,[role=heading][aria-level=5]'));
$h6 = count($dom->find('h6,[role=heading][aria-level=6]'));	

$headings = ($h1+$h2+$h3+$h4+$h5+$h6);

if($headings == 0){
$errorcode = __('Missing headings - Post ID: ', 'wp-ada-compliance-basic').$postinfo['postid'];
			
// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_headings", $errorcode))
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_headings", $wp_ada_compliance_basic_def['missing_headings']['StoredError'], $errorcode);


}	
return 1;
}
?>