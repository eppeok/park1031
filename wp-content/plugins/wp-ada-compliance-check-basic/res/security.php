<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

/**********************************************
// validate deep scan
**********************************************/
function wp_ada_compliance_basic_validate_deep_scan($value) {
$accepted = array('basic','deep');

if(in_array($value,$accepted)) return wp_ada_compliance_basic_sanitize_text_or_array_field($value);   

return 'basic';
}

/**********************************************
// validate post types
**********************************************/
function wp_ada_compliance_basic_validate_posttypes($values) {
global $wpdb;
$post_type_list = array();    
$results = $wpdb->get_results("SELECT distinct(post_type) FROM ".$wpdb->prefix."posts", ARRAY_A);	
if($results){
foreach($results as $row){ 
	if(!in_array($row['post_type'], $post_type_list )) $post_type_list[] = $row['post_type'];
}
}
 if(is_array($values) or $values == '') {
    foreach($values as $key => $value) {
     if(!in_array($value,$post_type_list)) unset($values[$key]);  
    }
     return wp_ada_compliance_basic_sanitize_text_or_array_field($values);
 }
return array('page','post');
}

/**********************************************
// validate scan rules
**********************************************/
function wp_ada_compliance_basic_validate_scan_rules($values){
    global $wp_ada_compliance_basic_def;
    
$allowed = array();    
    
    if(is_array($wp_ada_compliance_basic_def))
foreach ($wp_ada_compliance_basic_def as $rows => $row){
    if(!in_array($rows, $allowed )) $allowed[] = $rows;
}
    
 if(is_array($values) or $values == '') {
    foreach($values as $key => $value) {
     if(!in_array($value,$allowed)) unset($values[$key]);  
    }
     return wp_ada_compliance_basic_sanitize_text_or_array_field($values);
 } 
return $allowed;    
}

/**********************************************
// validate errors per page
**********************************************/
function wp_ada_compliance_basic_validate_errors_per_page($value){

 $value = (int)$value;
    
if($value == 0) return 15;
else return $value;
}

/**********************************************
// validate settings users
**********************************************/
function wp_ada_compliance_basic_validate_settingsusers($value){
if(in_array($value,array('edit_pages','manage_options'))) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return 'manage_options';
}

/**********************************************
// validate language code
**********************************************/
function wp_ada_compliance_basic_validate_language_code($value){
if(preg_match('/^[a-z]{2}(-[A-Z]{2})?$/i',$value)) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return 'en';
}

/**********************************************
// validate background color
**********************************************/
function wp_ada_compliance_basic_validate_background_color($value){
if(preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})\b$/i',$value)) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return '#ffffff';
}

/**********************************************
// validate foreground color
**********************************************/
function wp_ada_compliance_basic_validate_foreground_color($value){
if(preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})\b$/i',$value)) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return '#000000';
}

/**********************************************
// validate starting H level
**********************************************/
function wp_ada_compliance_basic_validate_starting_H_level($value){
if(in_array($value,array('h1','h2'))) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return 'h2';
}

/**********************************************
// validate true false values DEFAULT to true
**********************************************/
function wp_ada_compliance_basic_validate_false_default_true($value){
if(in_array($value,array('true','false'))) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return 'true';
}

/**********************************************
// validate true false values DEFAULT to false
**********************************************/
function wp_ada_compliance_basic_validate_true_default_false($value){
if(in_array($value,array('true','false'))) return wp_ada_compliance_basic_sanitize_text_or_array_field($value); 
else return 'false';
}

/*********************************************************
sanitize array
*********************************************************/
function wp_ada_compliance_basic_sanitize_text_or_array_field($array_or_string) {
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = sanitize_text_or_array_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }

    return $array_or_string;
}

/*********************************************
validate input
********************************************/
function wp_ada_compliance_basic_form_values(){
global $wpdb, $wp_ada_compliance_basic_def;    
$error = '';
    
foreach($_POST as $key => $value){ 
  
$_POST[$key] = sanitize_text_field($_POST[$key]);    
    
//error email
if($key == 'email' and !filter_var($value, FILTER_VALIDATE_EMAIL)){
$error = __('Email address is invalid','wp-ada-compliance');
}
    
    //error types
if($key == 'comments' and !preg_match("/^([[:alnum:]]|-|[[:space:]]|[[:punct:]]|')+$/D", $value) and $value != ''){
$error = __('Comments contain invalid text.','wp-ada-compliance');
}
    
elseif($key == 'error' and !array_key_exists($value,$wp_ada_compliance_basic_def) and $value != ''){
$_POST['error'] = '';
}     
     
// check title search
if($key == 'searchtitle' and !preg_match("/^([[:alnum:]]|-|[[:space:]]|[[:punct:]]|')+$/D",$value) and $value != ''){
$_POST['searchtitle'] = '';
}        
    
//post types
if($key == 'type'){
$accepted =  array('');
$query = "SELECT distinct(type) FROM ".$wpdb->prefix."wp_ada_compliance_basic";
$results = $wpdb->get_results($query, ARRAY_A );
foreach ( $results as $row ) {
$accepted[] = $row['type'];
}
 if(!in_array($value,$accepted) and $value != '') 
$_GET['type'] = '';  
}      
}
    
foreach($_GET as $key => $value){
 
$_GET[$key] = sanitize_text_field($_GET[$key]);
    
//error types
if($key == 'errorw' and !array_key_exists($value,$wp_ada_compliance_basic_def) and $value != ''){
$_GET['errorw'] = '';
}
elseif($key == 'error' and !array_key_exists($value,$wp_ada_compliance_basic_def) and $value != ''){
$_GET['error'] = '';
}     
     
// check title search
if($key == 'searchtitle' and !preg_match("/^([[:alnum:]]|-|[[:space:]]|[[:punct:]]|')+$/D",$value) and $value != ''){
$_GET['searchtitle'] = '';
}        
    
//post types
if($key == 'type'){
$accepted =  array('');
$query = "SELECT distinct(type) FROM ".$wpdb->prefix."wp_ada_compliance_basic";
$results = $wpdb->get_results($query, ARRAY_A );
foreach ( $results as $row ) {
$accepted[] = $row['type'];
}
 if(!in_array($value,$accepted) and $value != '') 
$_GET['type'] = '';  
}  
        
 // ignore rule
 if($key == 'wpada_ignore_rule'){
   if(!array_key_exists($value,$wp_ada_compliance_basic_def)){
    $_GET['wpada_ignore_rule'] = '';
}
}
    
} // end get loop
    
return $error;      
}
?>