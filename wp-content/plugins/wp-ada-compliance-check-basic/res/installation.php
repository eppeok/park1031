<?php  
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/*********************************************
INSTALLATION FUNCTIONS
**********************************************/
function wp_ada_compliance_basic_deactivate_full_plugin() {
    if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/fl-builder.php' );     
    
    if ( is_plugin_active('wp-ada-compliance/wp-ada-compliance.php') ) {
    deactivate_plugins('wp-ada-compliance/wp-ada-compliance.php');    
    }
}

// activate plugin
function wp_ada_compliance_basic_install( $network_wide = false ) {
	
    global $wpdb;

	
    if ( is_multisite()) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
			// deactivate full plugin
			wp_ada_compliance_basic_deactivate_full_plugin();
            wp_ada_compliance_basic_create_tables();	
            restore_current_blog();
        }
    } else {
		// deactivate full plugin
		wp_ada_compliance_basic_deactivate_full_plugin();
        wp_ada_compliance_basic_create_tables();	
    }

}
// create database table
function wp_ada_compliance_basic_create_tables() {
	global $wpdb;
	
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'wp_ada_compliance_basic';

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		postid int(11) NOT NULL,
		ignre mediumint(9) NOT NULL,
		siteid text NOT NULL,
		type text NOT NULL, 
		externalsrc text NOT NULL, 
		scantype text NOT NULL, 
		onsave text NOT NULL,
		taxonomy text NOT NULL, 
		errorcode text NOT NULL, 
		object mediumtext NOT NULL, 
		posttitle text NOT NULL, 
		recordcheck mediumint(9) NOT NULL,
		date datetime NOT NULL,
		activeuser text NOT NULL,
		examplecode text NOT NULL,
        themeerror mediumint(9) NOT NULL, 
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );
	
}
/**************************************************
UNINSTALL FUNCTIONS
***************************************************/
// remove plugin
//wp_ada_compliance_uninstall();
function wp_ada_compliance_basic_uninstall() {
	global $wpdb;

    if ( is_multisite()) {
		
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
			$retain_settings = get_option('wp_ada_compliance_basic_retain_settings','false');	
           if($retain_settings == 'false')	{	
			wp_ada_compliance_basic_delete_tables();	
			wp_ada_compliance_basic_remove_options();
		   }
			
			restore_current_blog();
        }
    } else {
		$retain_settings = get_option('wp_ada_compliance_basic_retain_settings','false');	
		if($retain_settings=='false')	{
        wp_ada_compliance_basic_delete_tables();
		wp_ada_compliance_basic_remove_options();
		  }
    }
	//return;

}
// remove tables
function wp_ada_compliance_basic_delete_tables() {
global $wpdb;
	
$table_name = $wpdb->prefix . 'wp_ada_compliance_basic';	
	
$sql = "DROP TABLE IF EXISTS $table_name";
return $wpdb->query( $sql );
}

// remove options
function wp_ada_compliance_basic_remove_options(){
foreach ( wp_load_alloptions() as $option => $value ) {
if ( strpos( $option, 'wp_ada_compliance_basic_' ) === 0) {
	 delete_option( $option ); 
} 
}	
}

// Deleting the table whenever a blog is deleted
function wp_ada_compliance_basic_delete_blog( $tables ) {
    global $wpdb;
    $tables[] = $wpdb->prefix . 'wp_ada_compliance_basic';
    
	return $tables;
}
add_filter( 'wpmu_drop_tables', 'wp_ada_compliance_basic_delete_blog' );
/*********************************************
// update scan options to ignore
**********************************************/
function wp_ada_compliance_basic_update_scan_rule_ignore_options() {
	global $wp_ada_compliance_basic_def;
	$ignore_rules = array('');
$scan_rules = get_option('wp_ada_compliance_basic_scan_rules');
if($scan_rules != ""){	
foreach ($wp_ada_compliance_basic_def as $rows => $row){	
	if(!in_array($rows,$scan_rules)) $ignore_rules[] = $rows;		
}	
}
update_option('wp_ada_compliance_basic_ignore_scan_rules', $ignore_rules);
}
/*********************************************
// check version number for database updates
**********************************************/
function wp_ada_compliance_basic_check_version() {
	
	if ( ! function_exists( 'get_plugins' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
	
$current_version = get_plugin_data(plugin_dir_path( __DIR__ ).'wp-ada-compliance-basic.php');
$stored_option_version = get_option('wp_ada_compliance_basic_version');

if ($current_version['Version'] != $stored_option_version){
    
update_option('wp_ada_compliance_basic_rescan_required', 1);    

wp_ada_compliance_basic_install();
    
if($stored_option_version != "" and version_compare($stored_option_version, '2.3', '<='))    
wp_ada_compliance_basic_set_onsave_status_when_notset();

if($stored_option_version != "" and version_compare($stored_option_version, '2.2', '<='))
wp_ada_compliance_basic_convert_old_table();
    
if($stored_option_version != "" and version_compare($stored_option_version, '3.0', '<='))
wp_ada_compliance_basic_upgrade_to_version_3_0();  
    
if($stored_option_version != "" and version_compare($stored_option_version, '3.0.1', '<='))
wp_ada_compliance_basic_upgrade_to_version_3_0_1();     


update_option('wp_ada_compliance_basic_version', $current_version['Version']);
}
}
/*********************************************
// set default scan rule settings
**********************************************/
function wp_ada_compliance_basic_set_scan_rule_options() {
global $wp_ada_compliance_basic_def;

$scan_rules = get_option('wp_ada_compliance_basic_scan_rules', '');
$ignore_rules = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());	
if(!is_array($ignore_rules)) $ignore_rules = array();		
	
foreach ($wp_ada_compliance_basic_def as $rows => $row){	
	if(!in_array($rows,$ignore_rules)) $scan_rule_options[] = $rows;		
}
	update_option('wp_ada_compliance_basic_scan_rules', $scan_rule_options);

}

/*********************************************
// ignore a rule
**********************************************/
function wp_ada_compliance_basic_ignore_scan_rule($rule) {
	// secure rules
$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');
if (!current_user_can($settingsuser) ) return 1; 	
	
$ignore_rules = get_option('wp_ada_compliance_basic_ignore_scan_rules', array());
	
if(!is_array($ignore_rules)) $ignore_rules = array();
	
$ignore_rules[] = $rule;
	
update_option('wp_ada_compliance_basic_ignore_scan_rules', $ignore_rules);

}

/*********************************************************
set onsave status for records that are not set. 
*******************************************************/
function wp_ada_compliance_basic_set_onsave_status_when_notset() {
global $wpdb;
	
if ( is_multisite()) {	
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );    
    
$wpdb->query($wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic SET onsave = %s, scantype = %s where onsave = %s', 1, 'onsave', '') );
        restore_current_blog();
        }
}
else{
  $wpdb->query($wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic SET onsave = %s, scantype = %s where onsave = %s', 1, 'onsave', '') );  
}
}
/*********************************************************
FUNCTIONS TO CONVERT TABLE TO PREFIX FORMAT
set onsave status for records that are not set. 
*******************************************************/
function wp_ada_compliance_basic_convert_old_table() {
global $wpdb;
	
$querycheck = "SELECT EXISTS(SELECT siteid FROM wp_ada_compliance_basic)";
$results = $wpdb->get_results($querycheck, ARRAY_A);
if($results) {
	
	// process single site
if (!is_multisite()){	
//delete table
$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix . 'wp_ada_compliance_basic;';
$wpdb->query( $sql );
	
$query = 'RENAME TABLE wp_ada_compliance_basic TO '.$wpdb->prefix . 'wp_ada_compliance_basic;';	
$wpdb->query( $query );	
return;
}

// process multisite	
if (is_multisite()) {	
$query = 'SELECT * FROM wp_ada_compliance_basic';
$results = $wpdb->get_results($query, ARRAY_A );
foreach ( $results as $row ) {
	
switch_to_blog( $row['siteid'] );

//check if error exists first 
if(!wp_ada_compliance_basic_error_record_exists_check( $row['type'], $row['postid'], $row['errorcode'], $row['object'], $row['siteid'], $row['externalsrc'], $row['scantype'], $row['date'],$row['posttitle'])){

$table_name = $wpdb->prefix . 'wp_ada_compliance_basic';
$wpdb->query($wpdb->prepare('INSERT INTO '.$table_name.' (postid, ignre, siteid, type, externalsrc, scantype, onsave, taxonomy, errorcode, object, posttitle, recordcheck, date, activeuser, examplecode) VALUES(%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s,%s)',$row['postid'], $row['ignre'], $row['siteid'], $row['type'], $row['externalsrc'], $row['scantype'], $row['onsave'], $row['taxonomy'], $row['errorcode'], $row['object'], $row['posttitle'], $row['recordcheck'], $row['date'], $row['activeuser'], $row['examplecode']));
}

restore_current_blog();		
}
}
//delete table
$sql = "DROP TABLE IF EXISTS wp_ada_compliance_basic;";
$wpdb->query( $sql );
}
	return;
}
/**********************************************
// version 3.0 upgrade code
**********************************************/
function wp_ada_compliance_basic_upgrade_to_version_3_0(){
global $wpdb;

if ( is_multisite()) {
// Get all blogs in the network and activate plugin on each one
$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
foreach ( $blog_ids as $blog_id ) {
switch_to_blog( $blog_id );
	
    // change image alt file name errors to invalid alt
$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic set errorcode = %s where errorcode = %s',  'img_alt_invalid', 'img_alt_filename') );  

restore_current_blog();
}
}
else{   
// change image alt file name errors to invalid alt
$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->prefix.'wp_ada_compliance_basic set errorcode = %s where errorcode = %s',  'img_alt_invalid', 'img_alt_filename') );  
}
    
}
/**********************************************
// version 3.0 upgrade code
**********************************************/
function wp_ada_compliance_basic_upgrade_to_version_3_0_1(){
global $wpdb, $wp_ada_compliance_basic_def;
    

if ( is_multisite()) {
// Get all blogs in the network and activate plugin on each one
$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
foreach ( $blog_ids as $blog_id ) {
switch_to_blog( $blog_id );    
	
$table_name = $wpdb->prefix . 'wp_ada_compliance_basic';
$results = $wpdb->get_results('SELECT errorcode FROM '.$table_name, ARRAY_A );
foreach ( $results as $row ) {
if(!array_key_exists($row['errorcode'],$wp_ada_compliance_basic_def)){
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic where errorcode = %s',  $row['errorcode']) );   
}
}

restore_current_blog();
}
}
else{   
$table_name = $wpdb->prefix . 'wp_ada_compliance_basic';
$results = $wpdb->get_results('SELECT errorcode FROM '.$table_name, ARRAY_A );
foreach ( $results as $row ) {
if(!array_key_exists($row['errorcode'],$wp_ada_compliance_basic_def)){
$wpdb->query( $wpdb->prepare( 'DELETE FROM '.$wpdb->prefix.'wp_ada_compliance_basic where errorcode = %s',  $row['errorcode']) );    
}
}
}
    
}

/**********************************************
// check if record already exists
**********************************************/
function wp_ada_compliance_basic_error_record_exists_check( $type, $postid, $errorcode, $object, $siteid, $externalsrc, $scantype, $date, $posttitle ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_ada_compliance_basic';
	$results = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.$table_name.' where type= %s and postid = %d and errorcode = %s and object = %s and siteid = %d and externalsrc= %s and scantype= %s and date = %s and posttitle = %s', $type, $postid, $errorcode, $object, $siteid,$externalsrc, $scantype, $date, $posttitle), ARRAY_A );

if($results) {
		return 1;
	}
}
/*************************************************
check server requirements
***************************************************/
function wp_ada_compliance_basic_check_server_requirements() {
	$notice = '';

		// php version
	if(!version_compare(phpversion(), '5.5')) {
		$notice .= '<p>'.__('PHP version: Your server is using an unsupported version of PHP. Upgrade PHP to at least version 5.5.','wp-ada-compliance-basic').'</p>';
	}
	// wordpress version	
	if(!wp_version_check() > '4.6') {
		$notice .= '<p>'.__('You are using an unsupported version of Wordpress. Upgrade to at least version 4.6.','wp-ada-compliance-basic').'</p>';
	}
    
        // check for beaver builder and elementor editor clash
    if (function_exists('is_plugin_active') and (is_plugin_active('beaver-builder-lite-version/fl-builder.php') or is_plugin_active('bb-plugin/fl-builder.php')) and  is_plugin_active('elementor/elementor.php')) {
     $notice .= '<p>'.__('The Beaver Builder and Elementor editors should not be active at the same time when using the WP ADA Compliance plugin.', 'wp-ada-compliance-basic').'</p>';
    }

	// detect browser support
	$agent = $_SERVER['HTTP_USER_AGENT'];
   if(strpos($agent, 'Windows') 
	  and !(strpos($agent, 'Opera') or strpos($agent, 'Edge') or strpos($agent, 'Chrome') or strpos($agent, 'Firefox'))
	  or strpos($agent, 'Macintosh') 
	  and !(strpos($agent, 'Opera') or strpos($agent, 'Edge') or strpos($agent, 'Chrome') or strpos($agent, 'Firefox') or strpos($agent, 'Safari'))
	  or strpos($agent, 'Edge/15') or strpos($agent, 'Firefox/5') 
	 ){

	$notice .= '<p>'.__('Unsupported Browser: The browser you are using has know issues when using features of this plugin. Please upgrade to the latest version of Edge, Chrome, Firefox, Opera or on Mac Safari is also supported.', 'wp-ada-compliance-basic').'</p>';	
		}

	if($notice != '') {
	$noticeheader = '<h2>';
	$noticeheader .=__('WP ADA Compliance Basic - Additional Setup','wp-ada-compliance-basic');	
	$noticeheader .= '</h2>';	
	$notice = '<div class="notice notice-error wp-ada-compliance-additionalsetup">'.$noticeheader.$notice.'</div>';
	
  	echo $notice;
	}

}
?>