<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate blinking text
/********************************************************************/	
function wp_ada_compliance_basic_validate_blinking_text($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());

	
// check if being scanned
if(in_array('blinking_text', $wp_ada_compliance_basic_scanoptions)) return 1;

// check for the blink tag	
$dom = str_get_html($content);
	
$blinks = $dom->find('blink');
foreach ($blinks as $blink) {
	

	
$blinking_text_errorcode = $blink->outertext;
			
		// save error
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"blinking_text", $blinking_text_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "blinking_text", $wp_ada_compliance_basic_def['blinking_text']['StoredError'], $blinking_text_errorcode);
		}
		
		
	
}
	
	
// check for text-decoration: blink	
$pattern = "|text\-decoration:\s*blink|i";

	if(preg_match_all($pattern, $content, $matches,PREG_OFFSET_CAPTURE)){

	$matchsize = sizeof($matches[0]);
	
	for($i=0; $i < $matchsize; $i++){
        if(isset($matches[0][$i]) and $matches[0][$i][0] != ""){
		
	
			
		$blinking_text_errorcode = htmlspecialchars($matches[0][$i][0]).__(' (char #: ','wp-ada-compliance-basic').$matches[0][$i][1].')';	

		// save error
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"blinking_text", $blinking_text_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "blinking_text", $wp_ada_compliance_basic_def['blinking_text']['StoredError'], $blinking_text_errorcode);
		}
		

	}
	}
	
}
	return 1;
} 
?>