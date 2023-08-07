<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// look for links without a visual cue
/********************************************************************/	
function wp_ada_compliance_basic_validate_link_without_visual_cue($content, $postinfo){
global $wp_ada_compliance_basic_def;
	
// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('link_without_visual_cue', $wp_ada_compliance_basic_scanoptions)) return 1;

if($postinfo['type'] != 'css')	{

$dom = str_get_html($content);	
	
$links = $dom->find('a');

foreach ($links as $link) {			

if(isset($link) 
   and (stristr($link->getAttribute('style'), 'text-decoration: none') 
	or stristr($link->getAttribute('style'), 'text-decoration:none'))
  and (!stristr($link->getAttribute('style'), 'font-weight:')
	   	   and !stristr($link->getAttribute('style'), 'font-style: italic') 
	   and !stristr($link->getAttribute('style'), 'font-style:italic') 
		and !stristr($link->getAttribute('style'), 'border-bottom:')  
		and !stristr($link->getAttribute('style'), 'border:'))
  ){	
	
// save error
	$link_without_visual_cue_errorcode = $link->outertext;
	
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"link_without_visual_cue", $link_without_visual_cue_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "link_without_visual_cue", $wp_ada_compliance_basic_def['link_without_visual_cue']['StoredError'], $link_without_visual_cue_errorcode);
		}
		
		
}
	
}
}
// // parse and scan style tag content in post files
if($postinfo['type'] != 'css' and stristr($content, '<style'))	{
// check links in content for style tags
$dom = str_get_html($content);

$styles = $dom->find('style');

foreach ($styles as $style) {	

	$css_array = wp_ada_compliance_basic_parce_css($style->innertext);
	
	wp_ada_compliance_basic_scan_css_content($css_array, $postinfo);
}
}
// parse and scan css file content
if($postinfo['type'] == 'css')	{
$css_array = wp_ada_compliance_basic_parce_css($content);	

wp_ada_compliance_basic_scan_css_content($css_array, $postinfo);

} 
}
	

/****************************************************************
parse css file to make it easy to search
*****************************************************************/
function wp_ada_compliance_basic_parce_css($css){
$css = preg_replace("%/\*(?:(?!\*/).)*\*/%s", " ",$css);	
$css_array = array(); // master array to hold all values
$element = explode('}', $css);
foreach ($element as $element) {
    // get the name of the CSS element
    $a_name = explode('{', $element);
    $name = $a_name[0];
    // get all the key:value pair styles
    $a_styles = explode(';', $element);
    // remove element name from first property element
    $a_styles[0] = str_replace($name . '{', '', $a_styles[0]);
    // loop through each style and split apart the key from the value
    $count = count($a_styles);
	$counter = 0;
    for ($a=0;$a<$count;$a++) {
        if ($a_styles[$a] != '') {
					$a_styles[$a] = str_ireplace('https://', '//', $a_styles[$a]);
			$a_styles[$a] = str_ireplace('http://', '//', $a_styles[$a]);	
            $a_key_value = explode(':', $a_styles[$a]);
            // build the master css array
			if(array_key_exists(1, $a_key_value))
            $css_array[trim($counter.$name)][trim(strtolower($a_key_value[0]))] = trim($a_key_value[1]);
        }
		$counter++;
    }               
}
	return $css_array;
}
	
/*********************************************************
scan the content from a css file or style tag inside a post
*********************************************************/
function wp_ada_compliance_basic_scan_css_content($css_array, $postinfo){
global $wp_ada_compliance_basic_def;	
foreach($css_array as $element => $rules){
	if((stristr($element,'a:') 
		or stristr($element,'a[') 
		or stristr($element,'a [') 
		or stristr($element,'a.')
		or stristr($element,'> a') 
		or $element == 'a' 
	
		or stristr($element, ' a ')) 
	   and array_key_exists('text-decoration', $rules)){
		
		if(('text-decoration' == array_search('none', $rules)) 
		   and 
		   (!array_key_exists('font-weight', $rules) 
			and !array_key_exists('border-bottom', $rules) 
			and !array_key_exists('font-style', $rules) 
			and !array_key_exists('border', $rules))
		  ){
			
	

				$link_without_visual_cue_errorcode = $element.'{';
					foreach($rules as $key => $value){
						$link_without_visual_cue_errorcode .= $key.': '.$value.'; ';
					}
				$link_without_visual_cue_errorcode .= '}';

		// save error
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"link_without_visual_cue", $link_without_visual_cue_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "link_without_visual_cue", $wp_ada_compliance_basic_def['link_without_visual_cue']['StoredError'], $link_without_visual_cue_errorcode);
		}
		
		
}
}
}
}
?>