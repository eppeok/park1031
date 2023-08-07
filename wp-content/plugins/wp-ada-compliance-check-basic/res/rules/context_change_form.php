<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/*********************************************************************************/	
// validate forms that submit automatically or trigger an unexpected context change
/*********************************************************************************/	
function wp_ada_compliance_basic_validate_context_change_form($content, $postinfo){ 

global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('context_change_form', $wp_ada_compliance_basic_scanoptions)) return 1;	
	

$forms = $dom->find('form');
	
$formAtttributestoIgnore = array();
$formAtttributestoIgnore[] = 'submit';
$formAtttributestoIgnore[] = 'hidden';	
	
foreach ($forms as $form) {
		
$fields = $form->find('input');
$selects = $form->find('select');
$textareas = $form->find('textarea');
	
	// check input fields
foreach ($fields as $field) {
$i = 0;   
// ignore 	
if(isset($field) and !in_array(strtolower($field->getAttribute('type')),$formAtttributestoIgnore)){

	if (
	($field->getAttribute('onclick') 
		 and (stristr($field->getAttribute('onclick'),'window.open') 
		 or stristr($field->getAttribute('onclick'),'.submit'))) 
	or ($field->getAttribute('onchange') 
		 and (stristr($field->getAttribute('onchange'),'window.open') 
		 or stristr($field->getAttribute('onchange'),'.submit')))
	or ($field->getAttribute('onfocus') 
		 and (stristr($field->getAttribute('onfocus'),'window.open') 
		 or stristr($field->getAttribute('onfocus'),'.submit')))
	or ($field->getAttribute('onkeydown') 
		 and (stristr($field->getAttribute('onkeydown'),'window.open') 
		 or stristr($field->getAttribute('onkeydown'),'.submit'))) 		
	or ($field->getAttribute('onkeypress') 
		 and (stristr($field->getAttribute('onkeypress'),'window.open') 
		 or stristr($field->getAttribute('onkeypress'),'.submit')))) {
			
			
			$formfieldcode = $field->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"context_change_form", $formfieldcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"context_change_form", $wp_ada_compliance_basic_def['context_change_form']['StoredError'], $formfieldcode);
			

		}
		$i++;
	}
}
	
// check select fields
foreach ($selects as $field) {
$i = 0;   

	if (
	($field->getAttribute('onclick') 
		 and (stristr($field->getAttribute('onclick'),'window.open') 
		 or stristr($field->getAttribute('onclick'),'.submit'))
         or stristr($field->getAttribute('onclick'),'jumpMenu') ) 
	or ($field->getAttribute('onchange') 
		 and (stristr($field->getAttribute('onchange'),'window.open') 
		 or stristr($field->getAttribute('onchange'),'.submit'))
         or stristr($field->getAttribute('onchange'),'jumpMenu') ) 
	or ($field->getAttribute('onfocus') 
		 and (stristr($field->getAttribute('onfocus'),'window.open') 
		 or stristr($field->getAttribute('onfocus'),'.submit'))
       or stristr($field->getAttribute('onfocus'),'jumpMenu') )	
	or ($field->getAttribute('onkeydown') 
		 and (stristr($field->getAttribute('onkeydown'),'window.open') 
		 or stristr($field->getAttribute('onkeydown'),'.submit'))
       or stristr($field->getAttribute('onkeydown'),'jumpMenu') ) 	
	or ($field->getAttribute('onkeypress') 
		 and (stristr($field->getAttribute('onkeypress'),'window.open') 
		 or stristr($field->getAttribute('onkeypress'),'.submit')
             or stristr($field->getAttribute('onkeypress'),'jumpMenu') 
             ))) {
		
			$formfieldcode = $field->outertext;
			
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"context_change_form", $formfieldcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"context_change_form", $wp_ada_compliance_basic_def['context_change_form']['StoredError'], $formfieldcode);
			

		}
		$i++;
}	
	
// check textareas fields

foreach ($textareas as $field) {
$i = 0;   
// ignore 	

	if (
	($field->getAttribute('onclick') 
		 and (stristr($field->getAttribute('onclick'),'window.open') 
		 or stristr($field->getAttribute('onclick'),'.submit'))) 
	or ($field->getAttribute('onchange') 
		 and (stristr($field->getAttribute('onchange'),'window.open') 
		 or stristr($field->getAttribute('onchange'),'.submit'))) 
	or ($field->getAttribute('onfocus') 
		 and (stristr($field->getAttribute('onfocus'),'window.open') 
		 or stristr($field->getAttribute('onfocus'),'.submit')))	
	or ($field->getAttribute('onkeydown') 
		 and (stristr($field->getAttribute('onkeydown'),'window.open') 
		 or stristr($field->getAttribute('onkeydown'),'.submit'))) 	
	or ($field->getAttribute('onkeypress') 
		 and (stristr($field->getAttribute('onkeypress'),'window.open') 
		 or stristr($field->getAttribute('onkeypress'),'.submit')))) {
			
		$formfieldcode = $field->outertext;
		
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"context_change_form", $formfieldcode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"context_change_form", $wp_ada_compliance_basic_def['context_change_form']['StoredError'], $formfieldcode);
			
			
		}
		
		$i++;
	}	


	}
	return 1;
}
?>