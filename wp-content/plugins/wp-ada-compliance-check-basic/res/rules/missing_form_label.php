<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate form fields for labels
/********************************************************************/	
function wp_ada_compliance_basic_validate_missing_form_label($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);		

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('missing_form_label', $wp_ada_compliance_basic_scanoptions)) return 1;	
	
		
$fields = $dom->find('input');
$selects = $dom->find('select');
$textareas = $dom->find('textarea');

// store label values in array
if(isset($labelfors)) unset($labelfors);
$labelfors = array();	
$labels = $dom->find('label');
foreach ($labels as $label) {
if(isset($label)) $labelfors[] = $label->getAttribute('for');
}
if(!isset($labelfors)) $labelfors[] = "%^&*";

$formAtttributestoIgnore = array();	
$formAtttributestoIgnore[] = 'submit';
$formAtttributestoIgnore[] = 'hidden';
     $formAtttributestoIgnore[] = 'button';
	
	// check input fields
foreach ($fields as $field) {
   
// ignore 	
if(isset($field) and !in_array(strtolower($field->getAttribute('type')),$formAtttributestoIgnore) and !preg_match('/display:\s?none;/i',$field->parent()->getAttribute('style')) and !preg_match('/display:\s?none;/i',$field->getAttribute('style'))){
	

	if (($field->getAttribute('id')== "" or !in_array($field->getAttribute('id'),$labelfors))
			and $field->parent()->tag != "label"
			and $field->parent()->parent()->tag != "label"
		and (($field->getAttribute('aria-labelledby') == "" 
              and $field->getAttribute('aria-describedby') == ""  
			and $field->getAttribute('aria-label') == "" 
			  and $field->getAttribute('tabindex') != "-1" 
			and $field->getAttribute('title') == "" 
			and $field->getAttribute('type') != "image") 
		    or ($field->getAttribute('type') == "image" 
			and $field->getAttribute('alt') == ""))){
			
			$formfieldcode = $field->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_form_label", $formfieldcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_form_label", $wp_ada_compliance_basic_def['missing_form_label']['StoredError'], $formfieldcode);
			

	
		}
		
	}
}
	
// check select fields
foreach ($selects as $field) {

		
if(isset($field) and !preg_match('/display:\s?none;/i',$field->parent()->getAttribute('style')) and !preg_match('/display:\s?none;/i',$field->getAttribute('style'))){		    
    
	if (isset($field))
		$nodetext = trim(strip_tags(preg_replace('/<select[^>]*>([\s\S]*?)<\/select[^>]*>/', '', $field->parent()->outertext)));
	
		$nodetextparent = trim(strip_tags(preg_replace('/<select[^>]*>([\s\S]*?)<\/select[^>]*>/', '', $field->parent()->parent()->outertext)));
	
	
		if (isset($field)  
			and ($field->getAttribute('id')== "" or !in_array($field->getAttribute('id'),$labelfors))
			and ($field->parent()->tag != "label" 
				 or ($field->parent()->tag == "label" and $nodetext == ""))
			and ($field->parent()->parent()->tag != "label" 
				 or ($field->parent()->parent()->tag == "label" and $nodetextparent == ""))
			and $field->getAttribute('aria-labelledby') == "" 
			and $field->getAttribute('aria-label') == "" 
			and $field->getAttribute('tabindex') != "-1"
			and $field->getAttribute('title') == "") {
		
			$formfieldcode = $field->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_form_label", $formfieldcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_form_label", $wp_ada_compliance_basic_def['missing_form_label']['StoredError'], $formfieldcode);
			

			
		}
}
}	
	
// check textareas fields

foreach ($textareas as $field) {

// ignore 	
    if(isset($field) and !preg_match('/display:\s?none;/i',$field->parent()->getAttribute('style')) and !preg_match('/display:\s?none;/i',$field->getAttribute('style'))){		
        
		if (isset($field) 
			and ($field->getAttribute('id')== "" or !in_array($field->getAttribute('id'),$labelfors))
			and $field->parent()->tag != "label"
			and $field->parent()->parent()->tag != "label"
			and $field->getAttribute('aria-labelledby') == "" 
			and $field->getAttribute('aria-label') == "" 
			and $field->getAttribute('tabindex') != "-1"
			and $field->getAttribute('title') == "") {
			$formfieldcode = $field->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_form_label", $formfieldcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"missing_form_label", $wp_ada_compliance_basic_def['missing_form_label']['StoredError'], $formfieldcode);
			
			
		}
		
    }
	}	

	
	// IDENTIFY EMPTY LABELS	
$labels = $dom->find('label');
foreach ($labels as $label) {
if(isset($label) and trim(strip_tags(preg_replace('/<select[^>]*>([\s\S]*?)<\/select[^>]*>/', '', $label->outertext))) == ""
  ){
		
$missing_form_label_errorcode = $label->outertext;
		
	// if not hidden from screen readers
	if(!stristr($missing_form_label_errorcode,'tabindex="-1"') and !stristr($missing_form_label_errorcode,"tabindex='-1'")){
		// save error
		if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"missing_form_label", $missing_form_label_errorcode)){
			
		$insertid = wp_ada_compliance_basic_insert_error($postinfo, "missing_form_label", $wp_ada_compliance_basic_def['missing_form_label']['StoredError'], $missing_form_label_errorcode);
		}
		
		
	}
	
	
}
}
    
	return 1;
}
?>