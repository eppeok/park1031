<?php 
/*
Plugin - WP ADA Compliance Check
functions to display dashboard items
*/
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/**********************************************
// add dashboard widget with stats
**********************************************/
function wp_ada_compliance_basic_dashboard_widgets() {
global $wp_meta_boxes;
if(current_user_can('edit_pages')){  
wp_add_dashboard_widget('wp_ada_compliance_basic_stats_widget', 'Web Accessibility Summary', 'wp_ada_compliance_basic_dashboard_stats');
	
}
}
function wp_ada_compliance_basic_dashboard_stats() {
echo '<div class="wp_ada_dashboard_widget">';
wp_ada_compliance_basic_dashboard_summary();
echo '<p style="text-align:center; clear:both;"><a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php" class="btnwpada btnwpada-primary">';
	_e('View Report','wp-ada-compliance-basic');
echo '</a></p>';	
echo '</div>';
}

/*********************************************
create dashboard summary
********************************************/
function wp_ada_compliance_basic_dashboard_summary(){
global $wpdb;
// count number of records	and display message
$totalitems = wp_ada_compliance_basic_count_total_scan_records();	
	
// count total number of pages with issues
$query = "SELECT count(DISTINCT postid) FROM ".$wpdb->prefix."wp_ada_compliance_basic where ignre != %d";
$totalpages = $wpdb->get_var($wpdb->prepare($query, 1));
	
// count total number of issues
$query = "SELECT count(*) FROM ".$wpdb->prefix."wp_ada_compliance_basic where ignre !=%d";
$totalissues = $wpdb->get_var($wpdb->prepare($query, 1));		

// count protected issues
$query = "SELECT count(*) FROM ".$wpdb->prefix."wp_ada_compliance_basic where ignre =%d";
$totalprotected = $wpdb->get_var($wpdb->prepare($query, 2));	

// count ignored issues	
$query = "SELECT count(*) FROM ".$wpdb->prefix."wp_ada_compliance_basic where ignre =%d";

$totalignored = $wpdb->get_var($wpdb->prepare($query, 1));		
	
$query = "SELECT count(*) FROM ".$wpdb->prefix."posts e WHERE (NOT EXISTS (SELECT null FROM ".$wpdb->prefix."postmeta d WHERE d.post_id = e.ID and meta_key = '_wp_attachment_image_alt') or EXISTS (SELECT post_id FROM ".$wpdb->prefix."postmeta d WHERE d.post_id = e.ID and meta_key = '_wp_attachment_image_alt' and meta_value = '')) and post_type = 'attachment' AND post_mime_type LIKE '%image/%'";
$mediafileswithoutalt = $wpdb->get_var($query);	

// determine grade < 20% green light  21-50% yellow 50-100% red
if($totalitems == 0){
$percent = 0;	
	$percentdisplay = 0;
}else{
$percent = round(($totalpages)/($totalitems)*100,PHP_ROUND_HALF_UP);	
$percentdisplay = round(($totalpages)/($totalitems)*100);
}
// account for last 1% bug
if($percent < 1 and $totalissues > 0) {
	$percent = 1;
	$percentdisplay = 1;
}	
if($percent < 20 ) $class= 'wp_ada_light_green';	
elseif($percent >= 20 and $percent < 50 ) $class = 'wp_ada_light_yellow';
elseif($percent >= 50 ) $class = 'wp_ada_light_red';	
else $class='';	
	
// display protected issues	
echo '<ul class="wp_ada_summary_left ">';
if($totalprotected > 0){	
echo '<li class="adaViewbar adaRedText">';
_e('Automatic protection could be zapping ', 'wp-ada-compliance-basic');
echo esc_html($totalprotected);
_e(' issues! ', 'wp-ada-compliance-basic');
echo '<br />';
echo '<a href="https://www.alumnionlineservices.com/php-scripts/wp-ada-compliance-check/" class="adaRedText">';	
_e('Upgrade to the full version to enable this option', 'wp-ada-compliance-basic');
	echo '</a>';
echo '</li>';
}
if($totalitems > 15){	
echo '<li class="adaViewbar adaRedText">';
echo esc_html($totalitems-15);
_e(' posts or pages are unprotected ', 'wp-ada-compliance-basic');
echo '<br /><a href="https://www.alumnionlineservices.com/php-scripts/wp-ada-compliance-check/" class="adaRedText">';	
_e('Upgrade to the full version to protect all your content', 'wp-ada-compliance-basic');
	echo '</a>';	
echo '</li>';
}
	

echo '<li class="adaViewbar adaRedText">';
echo esc_html($mediafileswithoutalt);
_e(' media library images are missing alt text', 'wp-ada-compliance-basic');
    echo '<br /><a href="https://www.alumnionlineservices.com/php-scripts/wp-ada-compliance-check/" class="adaRedText">';	
_e('Upgrade to the full version to scan media library images for missing alt text', 'wp-ada-compliance-basic');
	echo '</a>';
echo '</li>';	
	
echo '<li class="adaViewbar adaRedText">';
_e('No theme files, widgets or category pages have been checked ', 'wp-ada-compliance-basic');
echo '<br /><a href="https://www.alumnionlineservices.com/php-scripts/wp-ada-compliance-check/" class="adaRedText">';	
_e('Upgrade to the full version to protect all your content', 'wp-ada-compliance-basic');
	echo '</a>';	
echo '</li>';	
	
	
	
	
	
echo '<li class="adaViewbar">';
_e('Your website has ', 'wp-ada-compliance-basic');
echo esc_html($totalpages);
_e(' posts or pages with issues ', 'wp-ada-compliance-basic');
echo '</li>';
	
// display total issues	
echo '<li class="adaViewbar">';
_e('Issues: ', 'wp-ada-compliance-basic');
echo esc_html($totalissues);
_e(' issues found in ', 'wp-ada-compliance-basic');	
echo esc_html($totalitems);
_e(' items ', 'wp-ada-compliance-basic');	
echo '</li>';

// display ignored issues	
$query = "SELECT count(*) FROM ".$wpdb->prefix."wp_ada_compliance_basic where ignre =%d";
$total = $wpdb->get_var($wpdb->prepare($query, 1));	
echo '<li class="adaViewbar">';
_e('Ignored: You are ignoring ', 'wp-ada-compliance-basic');
echo esc_html($totalignored);
_e(' issues ', 'wp-ada-compliance-basic');
echo '</li>';	

echo '<li class="adaViewbar '.esc_attr($class).' wp_ada_light" style="max-width: 500px; white-space: normal;"><i class="fas fa-circle"></i><span>';

echo esc_html($percentdisplay);
_e('% of your site has issues ', 'wp-ada-compliance-basic');
echo '</span><br />';	
if($percent < 5 ) {
    _e(' The basic version is limited to 15 posts or pages, identifies 22 fewer error types and will not scan your entire website. ', 'wp-ada-compliance-basic');
    if($totalitems > 15){
    echo esc_html($totalitems-15);
_e(' posts or pages were not checked. ', 'wp-ada-compliance-basic');
        _e(' Consider upgrading to the full version to check all your content. ', 'wp-ada-compliance-basic');
}
}

elseif($percent < 10 ) _e(' You\'re doing great, strive for less than 5%! ', 'wp-ada-compliance-basic');	
elseif($percent < 20 ) _e(' You\'re doing good, but you can do better! ', 'wp-ada-compliance-basic');
elseif($percent < 40 ) _e(' You have some work to do! ', 'wp-ada-compliance-basic');	
elseif($percent > 40 ) _e(' Don\'t get discouraged, Get R Done! ', 'wp-ada-compliance-basic');	
echo '</li>';		
echo '</ul>';

}
/**************************************************
count total records
**************************************************/
function wp_ada_compliance_basic_count_total_scan_records(){
global $wpdb;
// get post types to be scanned
$posttypes = get_option('wp_ada_compliance_posttypes',array('page','post'));

if(!is_array($posttypes)) return 0;

$how_many = count($posttypes);
$placeholders = array_fill(0, $how_many, '%s');
$format = implode(', ', $placeholders);

array_unshift($posttypes,"trash");
array_unshift($posttypes,"auto-draft");
	array_unshift($posttypes,"private");
	array_unshift($posttypes,"tao_sc_publish");
    array_unshift($posttypes,"cus_sc_publish");
array_unshift($posttypes,"revision");	
	
$query = 'SELECT * FROM '.$wpdb->prefix.'posts where post_type != %s and post_status NOT IN(%s,%s, %s, %s, %s) and post_type IN('.$format.')';

$wpdb->get_results( $wpdb->prepare( $query, $posttypes ), ARRAY_A );

$rowcount = $wpdb->num_rows;
	
return $rowcount;

}


?>