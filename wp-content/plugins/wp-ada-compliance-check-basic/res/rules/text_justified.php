<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate justified text
/********************************************************************/	
function wp_ada_compliance_basic_validate_text_justified($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
// get options
$changetext = false;	
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
$report_filtered_errors = 'true';
	
// check if being scanned
if(in_array('text_justified', $wp_ada_compliance_basic_scanoptions)) return 1;
$fontsearchpatterns = array();
$fontsearchpatterns[] = "|(text-)?align:\s?justify|i";
//$fontsearchpatterns[] = "|text-align:\s?justify|i";

foreach($fontsearchpatterns as $key => $pattern){

	if(preg_match_all($pattern, $content, $matches,PREG_OFFSET_CAPTURE)){
	$matchsize = sizeof($matches[0]);
	
	for($i=0; $i < $matchsize; $i++){
        if(isset($matches[0][$i][0]) and $matches[0][$i][0] != ""){
		
			
		$text_justified_errorcode = htmlspecialchars($matches[0][$i][0]).__(' (char #: ','wp-ada-compliance-basic').$matches[0][$i][1].')';	

		// save error
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"text_justified", $text_justified_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "text_justified", $wp_ada_compliance_basic_def['text_justified']['StoredError'], $text_justified_errorcode);
		}
		
		
	}
	}
	}
	
}
	return 1;
} 
?>