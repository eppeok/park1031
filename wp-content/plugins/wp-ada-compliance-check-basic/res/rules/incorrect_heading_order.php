<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate page structure, look for headings that are not in order
/********************************************************************/	
function wp_ada_compliance_basic_validate_incorrect_heading_order($content, $postinfo){

    	
global $wp_ada_compliance_basic_def;
	
	
$dom = str_get_html($content);
	
// save simple dom	
$content = $dom->save();	

if($content == "") return;   
    
$dom = str_get_html($content);		

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());
$wp_ada_compliance_basic_starting_H_level = get_option('wp_ada_compliance_basic_starting_H_level','h2');	
	
// check if being scanned
if(in_array('incorrect_heading_order', $wp_ada_compliance_basic_scanoptions)) return;	

// when scanning in editors check this	
if($postinfo['scantype'] == 'onsave'){
	
$errorcode = '';

  
// check using page structure    
$headings = $dom->find('h1,[role=heading][aria-level=1],h2,[role=heading][aria-level=2],h3,[role=heading][aria-level=3],h4,[role=heading][aria-level=4],h5,[role=heading][aria-level=5],h6,[role=heading][aria-level=6]');
$previous = '';
foreach ($headings as $heading) {
    
 if($wp_ada_compliance_basic_starting_H_level == 'h1'){    
	if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '1') 
	or strtolower($heading->tag) == 'h1') and $previous != '') {
    $errorcode .= __('more than one h1; ', 'wp-ada-compliance-basic');  
	$errorcode .= $heading->outertext;
}

if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '2') 
or strtolower($heading->tag) == 'h2') and $previous != 'h1' and $previous != 'h2' and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6' ) {
    $errorcode .= __('h2 without h1; ', 'wp-ada-compliance-basic'); 
	$errorcode .= $heading->outertext;
}
}  
elseif($wp_ada_compliance_basic_starting_H_level == 'h2'){    
	if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '2') 
	or strtolower($heading->tag) == 'h2') and $previous != 'h1' and $previous != 'h2' and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6' and $previous != '') {
    $errorcode .= __('h2 before h1; ', 'wp-ada-compliance-basic');
	$errorcode .= $heading->outertext;
}
}


if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '3') 
or strtolower($heading->tag) == 'h3') and $previous != 'h2' and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6'   ){ 
    $errorcode .= __('h3 before h2; ', 'wp-ada-compliance-basic');
	$errorcode .= $heading->outertext;
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '4') 
or strtolower($heading->tag) == 'h4') and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6' ) {
    $errorcode .= __('h4 before h3; ', 'wp-ada-compliance-basic');   
	$errorcode .= $heading->outertext;
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '5') 
or strtolower($heading->tag) == 'h5') and $previous != 'h4' and $previous != 'h5' and $previous != 'h6'){ 
    $errorcode .= __('h5 before h4; ', 'wp-ada-compliance-basic');  
	$errorcode .= $heading->outertext;
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '6') 
or strtolower($heading->tag) == 'h6') and $previous != 'h5' and $previous != 'h6') {
    $errorcode .= __('h6 before h5; ', 'wp-ada-compliance-basic');
	$errorcode .= $heading->outertext;
}
   

if($heading->getAttribute('role') == 'heading') $previous = 'h'.trim($heading->getAttribute('aria-level'));
else $previous = strtolower($heading->tag);
}

if($errorcode != ''){
$errorcode = 'Issues: '.$errorcode;	
	
// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"incorrect_heading_order", $errorcode))
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"incorrect_heading_order", $wp_ada_compliance_basic_def['incorrect_heading_order']['StoredError'], $errorcode);


}	
}
else{ // when scanning the full content do this	
$errorcode = '';	

$h1 = count($dom->find('h1,[role=heading][aria-level=1]'));	
if($h1 == 0) $errorcode .= __('no first level heading h1; ', 'wp-ada-compliance-basic');		
elseif($h1 > 1) $errorcode .= __('more than one h1; ', 'wp-ada-compliance-basic');

$h1s = $dom->find('h1,[role=heading][aria-level=1]');
$frontpage_id = get_option( 'page_on_front' );
foreach ($h1s as $h1value){

   if((trim(wp_ada_compliance_basic_replaceSpecialCharacters($postinfo['title'])) != "" and trim(wp_ada_compliance_basic_replaceSpecialCharacters($h1value->plaintext)) != "")
	and !stristr(trim(wp_ada_compliance_basic_replaceSpecialCharacters($h1value->plaintext)), trim(wp_ada_compliance_basic_replaceSpecialCharacters($postinfo['title']))) 
    and !stristr(trim(wp_ada_compliance_basic_replaceSpecialCharacters($postinfo['title'])), trim(wp_ada_compliance_basic_replaceSpecialCharacters($h1value->plaintext)))   
	 and !stristr(trim($h1value->plaintext), trim($postinfo['title'])) 
     and !stristr(trim($postinfo['title']), trim($h1value->plaintext))   
	 and !strstr($errorcode, __('h1 not page title; ', 'wp-ada-compliance-basic'))
	 and $postinfo['postid'] != $frontpage_id
	 ) 
	 $errorcode .= __('h1 not page title; ', 'wp-ada-compliance-basic');

	if(strstr($errorcode, __('h1 not page title; ', 'wp-ada-compliance-basic')) or strstr($errorcode, __('more than one h1; ', 'wp-ada-compliance-basic')))
	$errorcode .= $h1value->outertext;
}
    
// check that heading 1 is found inside the main content area
$mains = $dom->find('main,[role=main]');
foreach ($mains as $main) {
	$headings = $main->find('h1,[role=heading][aria-level=1]');
if(count($headings) < 1) $errorcode .= __('h1 not found in the main content area; ', 'wp-ada-compliance-basic');  
}        
	   
// check using page structure    
$headings = $dom->find('h1,[role=heading][aria-level=1],h2,[role=heading][aria-level=2],h3,[role=heading][aria-level=3],h4,[role=heading][aria-level=4],h5,[role=heading][aria-level=5],h6,[role=heading][aria-level=6]');
$previous = '';
foreach ($headings as $heading) {
        
	if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '1') 
	or strtolower($heading->tag) == 'h1') and $previous != ''){ 
    $errorcode .= __('more than one h1; ', 'wp-ada-compliance-basic'); 
    $errorcode .= $heading->outertext;	
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '2') 
or strtolower($heading->tag) == 'h2') and $previous != 'h1' and $previous != 'h2' and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6') {
    $errorcode .= __('h2 before h1; ', 'wp-ada-compliance-basic');
	$errorcode .= $heading->outertext;
}

if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '3') 
or strtolower($heading->tag) == 'h3')  and $previous != 'h2' and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6'   ){ 
    $errorcode .= __('h3 before h2; ', 'wp-ada-compliance-basic');
	$errorcode .= $heading->outertext;
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '4') 
or strtolower($heading->tag) == 'h4')  and $previous != 'h3' and $previous != 'h4' and $previous != 'h5' and $previous != 'h6' ) {
    $errorcode .= __('h4 before h3; ', 'wp-ada-compliance-basic');   
	$errorcode .= $heading->outertext;
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '5') 
or strtolower($heading->tag) == 'h5')  and $previous != 'h4' and $previous != 'h5' and $previous != 'h6'){ 
    $errorcode .= __('h5 before h4; ', 'wp-ada-compliance-basic');  
	$errorcode .= $heading->outertext;
}
    
if((($heading->getAttribute('role') == 'heading' and $heading->getAttribute('aria-level') == '6') 
or strtolower($heading->tag) == 'h6')  and $previous != 'h5' and $previous != 'h6') {
    $errorcode .= __('h6 before h5; ', 'wp-ada-compliance-basic');
	$errorcode .= $heading->outertext;
}

if($heading->getAttribute('role') == 'heading') $previous = 'h'.trim($heading->getAttribute('aria-level'));
else $previous = strtolower($heading->tag);
}    
    

if($errorcode != ''){
$errorcode = 'Issues: '.$errorcode;			
// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"incorrect_heading_order", $errorcode))
$insertid = wp_ada_compliance_basic_insert_error($postinfo,"incorrect_heading_order", $wp_ada_compliance_basic_def['incorrect_heading_order']['StoredError'], $errorcode);


}
	$errorcode = '';

}
return;
}
/********************************************************************
replace special Characters
*********************************************************************/
function wp_ada_compliance_basic_replaceSpecialCharacters($value){
    $value = trim($value);

    $value = str_ireplace("   ", " ", $value);
    $value = str_ireplace("  ", " ", $value);
   $value = str_ireplace("‘", "'", $value);
    $value = str_ireplace("&amp;", "&", $value); 
	$value = str_ireplace("‘", "'", $value);
	$value = str_ireplace("’", "'", $value);
	$value = str_ireplace("”", '"', $value);
	$value = str_ireplace("“", '"', $value);
	$value = str_ireplace("&ldquo;", '"', $value);
	$value = str_ireplace("&rdquo;", '"', $value);
	$value = str_ireplace("&rsquo;", '"', $value);
	$value = str_ireplace("&ndash;", '"', $value);
	$value = str_ireplace("–", "-", $value);
	$value = str_ireplace("…", "...", $value);
	$value = str_ireplace("&oacute;", '', $value);
	$value = str_ireplace("&eacute;", '', $value);
	$value = str_ireplace("&aacute;", '', $value);
	$value = str_ireplace("&middot;", '', $value);
	$value = str_ireplace("&uacute;", '', $value);
	$value = str_ireplace("&uuml;", '', $value);
	$value = str_ireplace("&ograve;", '', $value);
	$value = str_ireplace("&egrave;", '', $value);
	$value = str_ireplace("&iacute;", '', $value);
	$value = str_ireplace("&ccedil;", '', $value);
	$value = str_ireplace("&agrave;", '', $value);
	
	$value = preg_replace("/[^a-zA-Z ]/u","", $value); 

 //  echo $value;
return $value;
}

?>