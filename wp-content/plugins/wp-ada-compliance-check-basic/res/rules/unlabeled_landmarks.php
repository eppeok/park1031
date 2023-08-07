<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate unlabeled landmarks
/********************************************************************/
function wp_ada_compliance_basic_validate_unlabeled_landmarks($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
// ignore check when scanning database only
if($postinfo['scantype'] == 'onsave') return;		

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());

// check if being scanned
if(in_array('unlabeled_landmarks', $wp_ada_compliance_basic_scanoptions)) return;   
 
if($content == '') return;	    
    
$dom = str_get_html($content);     
$dom = wp_ada_compliance_basic_remove_hidden_elements($dom);      
$errorcode = '';
$postinfo['examplecode'] = '';
	
	
// check main	
$mains = $dom->find('main,[role=main]'); 
if(count($mains) > 1 ){
$errorcode .= __('More than one main landmark was found (i.e... &lt;main&gt; or role="main"). There should only be one main landmark. ', 'wp-ada-compliance-basic');	
$postinfo['examplecode']= wp_ada_compliance_basic_prepare_landmark_error_code($mains);	
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks');
$errorcode = '';
$postinfo['examplecode'] = '';	
} 
	
	
// check navs	
$navs = $dom->find('nav, [role=navigation]');
if(count($navs) > 1){

if(!wp_ada_compliance_basic_check_for_multiple_elements_without_labels($navs) or !wp_ada_compliance_basic_check_for_unique_labels($navs)){
$errorcode .= __('One or more navigation landmarks were found to be missing labels or include non-unique values (i.e... &lt;nav aria-label="'.__('primary menu','wp-ada-compliance-basic').'" > or role="navigation"). Use an aria-label attribute with a unique value to distinguish the purpose of each landmark. ', 'wp-ada-compliance-basic');	
$postinfo['examplecode'] = wp_ada_compliance_basic_prepare_landmark_error_code($navs);
	
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks');
$errorcode = '';
$postinfo['examplecode'] = '';	
}	
}		
	
	
// check complementary	
$complementarys = $dom->find('aside,[role=complementary]');      
if(count($complementarys) > 1 and (!wp_ada_compliance_basic_check_for_multiple_elements_without_labels($complementarys) or !wp_ada_compliance_basic_check_for_unique_labels($complementarys))){
$errorcode .= __('One or more complementary landmarks were found to be missing labels or include non-unique values (i.e... &lt;aside&gt; or role="complementary"). Use an aria-label attribute with a unique value to distinguish the purpose of each landmark. ', 'wp-ada-compliance-basic');	
$postinfo['examplecode'] = wp_ada_compliance_basic_prepare_landmark_error_code($complementarys);
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks');
$errorcode = '';
$postinfo['examplecode'] = '';
}	
	
	
// check search		
$searchs = $dom->find('[role=search]'); 
if(count($searchs) > 1 and (!wp_ada_compliance_basic_check_for_multiple_elements_without_labels($searchs)  or !wp_ada_compliance_basic_check_for_unique_labels($searchs))){
$errorcode .= __('One or more search landmarks were found to be missing labels or include non-unique values (i.e... role="search"). Use an aria-label attribute with a unique value to distinguish the purpose of each landmark. ', 'wp-ada-compliance-basic');
$postinfo['examplecode'] = wp_ada_compliance_basic_prepare_landmark_error_code($searchs);
       
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks');
$errorcode = '';
$postinfo['examplecode'] = '';
}
	
// check forms		
 $forms = wp_ada_compliance_basic_remove_search_landmarks($dom);
if(count($forms) > 0 and (!wp_ada_compliance_basic_check_for_multiple_elements_without_labels($forms) or !wp_ada_compliance_basic_check_for_unique_labels($forms))){
      
$errorcode .= __('One or more form landmarks were found to be missing labels or include non-unique values  (i.e... &lt;form&gt; or role="form"). A form landmark should have a label to help users understand its purpose. A label for the form landmark should be identified using aria-labelledby to a visible heading element (e.g. an h1-h6 element). If no heading is present an aria-label attribute may be used.', 'wp-ada-compliance-basic');	
$postinfo['examplecode'] = wp_ada_compliance_basic_prepare_landmark_error_code($forms);
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks');
$errorcode = '';
$postinfo['examplecode'] = '';
}  	
	
	
    /******************************************************
run these checks  last or elements will be stripped
******************************************************/
// check header/banner	
 $headers = wp_ada_compliance_basic_remove_tags_wrapped_in_excluded_sections($dom, 'header');	
if(count($headers) > 1 and !wp_ada_compliance_basic_check_for_multiple_elements_without_labels($headers)){
$errorcode .= __('More than one banner/header landmark was found (i.e... &lt;header&gt; or role="banner"). There should normally be only one banner landmark.', 'wp-ada-compliance-basic');	
$postinfo['examplecode'] = wp_ada_compliance_basic_prepare_landmark_error_code($headers);	
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks');
$errorcode = '';
$postinfo['examplecode'] = '';		
}     
	
// check contentinfo	
 $contentinfo = wp_ada_compliance_basic_remove_tags_wrapped_in_excluded_sections($dom, 'footer');	 
if(count($contentinfo) > 1 and (!wp_ada_compliance_basic_check_for_multiple_elements_without_labels($contentinfo) or !wp_ada_compliance_basic_check_for_unique_labels($contentinfo))){
       
$errorcode .= __('One or more footer landmarks were found to be missing labels or include non-unique values (i.e... &lt;footer&gt; or role="contentinfo"). There should normally be only one footer/contentinfo landmark.', 'wp-ada-compliance-basic');
$postinfo['examplecode'] = wp_ada_compliance_basic_prepare_landmark_error_code($contentinfo);
wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def,'unlabeled_landmarks'); 
$errorcode = '';
$postinfo['examplecode'] = '';
}	  

}	

/***************************************
insert error
*****************************************/
function wp_ada_compliance_basic_insert_unlabeled_landmark_error($errorcode, $postinfo, $wp_ada_compliance_basic_def, $errortype){
 
// save error
if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,$errortype, $errorcode)){
$insertid = wp_ada_compliance_basic_insert_error($postinfo,$errortype, $wp_ada_compliance_basic_def[$errortype]['StoredError'], $errorcode);
}
}

/***************************************
check multiple elements without labels
*****************************************/
function wp_ada_compliance_basic_check_for_multiple_elements_without_labels($elements){

foreach($elements as $element){    
if($element->getAttribute('aria-label') == '' 
   and $element->getAttribute('aria-labelledby') == '' 
   and $element->getAttribute('aria-describedby') == ''
   and $element->getAttribute('aria-hidden') != 'true'
   
  ){
return 0;
}
}

return 1;
}

/***************************************
check for unique labels
*****************************************/
function wp_ada_compliance_basic_check_for_unique_labels($elements){
$labels = array();
    
    
foreach($elements as $element){
if($element->getAttribute('aria-label') != '')
$labels[] = $element->getAttribute('aria-label'); 
}
    
if(count($labels) == 0) return 1; 
   
$result = max(array_count_values($labels));

if($result > 1) {
return 0;
}

return 1;
}

/***********************************************
remove search elements
**********************************************/
function wp_ada_compliance_basic_remove_search_landmarks($dom){
	
 start:	   
$elements = $dom->find('form[role=search]');
    
foreach($elements as $element){
$element->parent()->removeChild($element);
goto start;		
}
    
$newelements = $dom->find('form, [role=form]'); 

return $newelements;
}


/***************************************
prepare error code
*****************************************/
function wp_ada_compliance_basic_prepare_landmark_error_code($elements, $exclude='') {
$errorcode = '';
$examplecode = '';	
$count = 1;
foreach($elements as $element){
if($exclude == '' or $element->getAttribute('role') != $exclude){
$arialabel = $element->getAttribute('aria-label');
if($arialabel == '') $arialabel = __('NONE', 'wp-ada-compliance-basic');	
$arialabelledby = $element->getAttribute('aria-labelledby');
$ariadescribedby = $element->getAttribute('aria-describedby');
if($ariadescribedby == '' and ($arialabel == __('NONE', 'wp-ada-compliance-basic') or !wp_ada_compliance_basic_check_for_unique_labels($elements)) and $arialabelledby == '') {   
$examplecode .= '<div style="margin: 20px; background-color: #eee; padding: 10px;"><span style="font-weight:bold;">'.__('Landmark #', 'wp-ada-compliance-basic').$count.' ';
if($arialabelledby == '' and $ariadescribedby == '') $examplecode .=__('aria-label: ', 'wp-ada-compliance-basic').$arialabel.' ';
elseif($arialabelledby != '') $examplecode .=__('aria-labelledby: ', 'wp-ada-compliance-basic').$arialabelledby.' ';	
elseif($ariadescribedby != '') $examplecode .=__('aria-describedby: ', 'wp-ada-compliance-basic').$ariadescribedby.' ';		
$examplecode .= '</span> ';
$examplecode .= esc_attr($element->outertext);	
$examplecode .= $element->outertext.'</div>'; 	
}
$count++;
}
}
    
return $examplecode;
}
?>