<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

/********************************************************************/	
// links in content that are lot included in an anchor tag
/********************************************************************/	
function wp_ada_compliance_basic_validate_unlinked_anchors($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
     
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
		
// check if being scanned
if(in_array('unlinked_anchors', $wp_ada_compliance_basic_scanoptions)) return 1;

    
$dom = str_get_html($content);	
$tags = array('p','h1','h2','h3','h4','h5','h6','td', 'li','blockquote','address','dd','pre');
foreach($tags as $value){	
$tag = $dom->find($value);
	
$patterns = array();	
$patterns[] = "/<a.*?<\/a>(*SKIP)(*F)|http(s)?:\/\/+/i";
$patterns[] = '/<a.*?<\/a>(*SKIP)(*F)|[\!#\$%&\'\*\+-\/=\?^_`\{\|\}~,:;\@\[\]\.\w-]+@([\w-]+\.)+[\w-]+/i'; 	 
	
foreach ($tag as $element) {
if($element->tag=='td'){
$code = $element->innertext;
} else $code = $element->outertext;
 
foreach($patterns as $pattern){    
    
	if(preg_match_all($pattern, $code, $matches,PREG_OFFSET_CAPTURE)){
	$matchsize = sizeof($matches[0]);
	
	for($i=0; $i < $matchsize; $i++){
        if(isset($matches[0][$i][0])  and $matches[0][$i][0] != "" 
           and stristr($element->plaintext, $matches[0][$i][0])
          and !wp_ada_compliance_basic_check_excluded($code, $matches[0][$i][0])){
		
		$unlinked_anchors_errorcode = $code;	

		// save error
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"unlinked_anchors", $unlinked_anchors_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "unlinked_anchors", $wp_ada_compliance_basic_def['unlinked_anchors']['StoredError'], $unlinked_anchors_errorcode);
		}
		
		
	}
	}
    }
}
} 
}
	return 1;
} 

/********************************************************
exclude anchors wrapped in quotes
********************************************************/
function wp_ada_compliance_basic_check_excluded($code, $match){
    $code = html_entity_decode ($code,ENT_QUOTES);	


if(stristr($code,"='".$match)) return 1;
if(stristr($code,"=”".$match)) return 1;    
if(stristr($code,"=".$match)) return 1; 
    if(stristr($code,"=\"".$match)) return 1; 

return 0;
}
?>