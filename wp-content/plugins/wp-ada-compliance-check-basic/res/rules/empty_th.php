<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate empty th cells
/********************************************************************/	
function wp_ada_compliance_basic_validate_empty_th($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
$errorfound = 0;
$dom = str_get_html($content);

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('empty_th', $wp_ada_compliance_basic_scanoptions)) return 1;	

$tables = $dom->find('table');

foreach ($tables as $table) {	
    
$tablecode = $table->outertext;

$headercells = $table->find('th');

foreach ($headercells as $th) {
	if (isset($th) and $th->plaintext == "") {
	
		$errorfound = 1;
		}

	}	
    
    if($errorfound == 1){         
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"empty_th", $tablecode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"empty_th",$wp_ada_compliance_basic__def['empty_th']['StoredError'], $tablecode);	
    }
}

return 1;
}
?>