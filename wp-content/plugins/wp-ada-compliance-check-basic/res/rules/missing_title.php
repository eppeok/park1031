<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/****************************************
check missing page titles
****************************************/
function wp_ada_compliance_basic_validate_missing_title($content, $postinfo){
global $wp_ada_compliance_basic_def;
	
if($postinfo['type'] == 'widget') return 1; 	
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('missing_title', $wp_ada_compliance_basic_scanoptions)) return 1;	

	
  

   if($postinfo['title'] == "" 
	/*  or strtolower($postinfo['title']) == __('untitled document','wp-ada-compliance-basic')
	 or strtolower($postinfo['title']) == __('enter the title of your html document here,','wp-ada-compliance-basic')
	  or strtolower($postinfo['title']) == __('no title','wp-ada-compliance-basic')
	  or strtolower($postinfo['title']) == __('untitled page','wp-ada-compliance-basic')
	  or strtolower($postinfo['title']) == __('untitled','wp-ada-compliance-basic')
	  or stristr($postinfo['title'], __('.html','wp-ada-compliance-basic'))
	  or stristr($postinfo['title'],__('New Page','wp-ada-compliance-basic'))*/
	  	  	  or stristr($postinfo['title'], __('untitled document','wp-ada-compliance-basic'))
	 or stristr($postinfo['title'], __('enter the title of your html document here,','wp-ada-compliance-basic'))
	  or stristr($postinfo['title'], __('no title','wp-ada-compliance-basic'))
	  or stristr($postinfo['title'], __('untitled page','wp-ada-compliance-basic'))
	   or strtolower($postinfo['title']) == __('untitled','wp-ada-compliance')
	  or stristr($postinfo['title'], __('.html','wp-ada-compliance-basic'))
	  or stristr($postinfo['title'],__('New Page','wp-ada-compliance-basic'))
	 ){		
	
	$missing_title_errorcode = '<title>'.$postinfo['title'].'</title>';
	// save error
	if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_title", $missing_title_errorcode)){	
		$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_title", $wp_ada_compliance_basic_def['missing_title']['StoredError'], $missing_title_errorcode);
		}
	
   }
	
}
?>