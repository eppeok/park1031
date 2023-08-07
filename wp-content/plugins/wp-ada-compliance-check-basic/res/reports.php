<?php
/*
Plugin - WP ADA Compliance Check
functions to support dipslay or reports and reference pages
*/
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/**********************************************
REFRESH REPORT PAGE
********************************************/
function wp_ada_compliance_basic_refresh_report_page(){
	
	check_ajax_referer('wp_rest', '_wpnonce');
	
	wp_ada_compliance_basic_purge_records();
	wp_ada_compliance_basic_report_page(0);
}
/********************************************
// display stats/error report
**********************************************/
function wp_ada_compliance_basic_report_page($scaninprogress=0) {
global $wpdb, $wp_ada_compliance_basic_def;   

// check cap allowed to edit settings
$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');
	
// check auto correct settings	
$report_filtered_errors = get_option('wp_ada_compliance_basic_report_filtered_errors','scanonly');
	
// remove nounce to keep it from breaking thickbox links	
$_SERVER['REQUEST_URI'] = remove_query_arg( '_wpnonce', $_SERVER['REQUEST_URI'] );	
	
if($scaninprogress == 1 and isset($_GET['status']) and ($_GET['status'] == 'complete' or $_GET['status'] == '0' or $_GET['status'] == 'recheck')){
 $scaninprogress = 0;  
}
// if not set set to zero so results are displayed
if(!isset($scaninprogress) or $scaninprogress == '') $scaninprogress = 0;
    
    // validate inputs
    wp_ada_compliance_basic_form_values();
	
// create pagination offset	
$total = 0;
$per_page = get_option('wp_ada_compliance_basic_errors_per_page','15');
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
if ($page > 1) {
   $offset = $page * $per_page - $per_page;
} else {
    $offset = 0;
}	
	
	//filter by view
	if(isset($_GET['view']) and $_GET['view'] != '') {
		$view = (int)$_GET['view'];
	}
	else {
		$_GET['view'] = 1;
		$view = 1;
	}
	
		//sort 
	if ( isset( $_GET[ 'sort' ] ) and $_GET[ 'sort' ] != "") { 
		if($_GET[ 'sort' ] == 1) {
			$sort = 1;
			$sortby = 'date DESC, id DESC ';
		}
		if($_GET[ 'sort' ] == 2) {
			$sort = 2;
			$sortby = 'type asc';
		}
		if($_GET[ 'sort' ] == 3) {
			$sort = 3;
			$sortby = 'errorcode asc';
		}
		if($_GET[ 'sort' ] == 4) {
			$sort = 4;
			$sortby = 'ignre desc';
		}
		if($_GET[ 'sort' ] == 5) {
			$sort = 5;
			$sortby = 'posttitle asc';
		}
		if($_GET[ 'sort' ] == 6) {
			$sort = 6;
			$sortby = 'onsave desc';
		}
	} else {
		$sort = 1;
		$sortby = "date DESC, id DESC ";
	}
		
	// filter by post type
	if (isset( $_GET['type'])) {
		$type = sanitize_text_field($_GET[ 'type' ]);
	} else{
		$_GET['type'] = "";
		$type = "";
	}

	
	// filter by error code
	if (isset( $_GET['errorw']) and !isset( $_GET['error'])) $_GET['error'] = $_GET['errorw'];
	if (isset( $_GET['error'])) {
		$error = sanitize_text_field($_GET[ 'error' ]);
	} else $error = "";	

	// specific post
	if ( isset( $_GET[ 'errorid' ] ) and $_GET[ 'errorid' ] != "") { 
			$errorid = ( int )$_GET[ 'errorid' ];
	} else $errorid = "";
	
	// filter by search
	if (isset( $_GET['searchtitle'])) {
		if(is_numeric($_GET['searchtitle'])) {
			$errorid= (int)$_GET['searchtitle'];
			$searchtitle = "";
		}
		else $searchtitle = sanitize_text_field($_GET['searchtitle']);
		
		
	} else $searchtitle = "";
	
    echo '<div class="wp_ada_compliance_basic_report">';
	
	if (!isset($_GET['iframe'])){
		// display system requirement issues and additional setup:
	wp_ada_compliance_basic_check_server_requirements();
	}
			echo '<a id="adascrollbutton" aria-label="Top"></a>';
	echo '<h2>';
	echo __('Web Accessibility Report: ', 'wp-ada-compliance-basic');
	if($errorid != "") echo esc_html(get_the_title($errorid));
	elseif($searchtitle != "") echo esc_html($searchtitle);
	else echo esc_html(get_bloginfo('name'));
	echo '</h2>';

	if ($errorid != "" and isset($_GET['iframe'])) { // specific post
        
		$query = "SELECT * FROM ".$wpdb->prefix."wp_ada_compliance_basic where postid = %d and type = %s and onsave = %d ";
		
		// hide if auto filter is turned off
		if($report_filtered_errors == 'false') $query .= " and ignre != 2 "; 
		
		$query .= "order by ignre";

		$results = $wpdb->get_results( $wpdb->prepare( $query, $errorid, $type, 1), ARRAY_A );
		
		$title = get_the_title($errorid);
		$showresults ="View=PostID: $errorid".'; ';
		if($title != "") $showresults .= __('Title=','wp-ada-compliance-basic').stripslashes($title).';';

	} 
	else{ 
		$showresults ="";
		$query = 'SELECT * FROM '.$wpdb->prefix.'wp_ada_compliance_basic where %d ';
		$queryVariablesMain = array();
		$queryVariablesTotal = array();
		$queryVariablesMain[] = 1;
		
		$totalquery = "SELECT count(id) FROM ".$wpdb->prefix."wp_ada_compliance_basic where %d  ";
		$queryVariablesTotal[] = 1;
		
		// hide if auto filter is turned off
		if($report_filtered_errors == 'false') $query .= " and ignre != 2 "; 
		
		if( $view == 1 ) { // current
			$query .= " and ignre != %d ";
			$totalquery .= " and ignre != %d";

			$queryVariablesMain[] = 1;
			$queryVariablesTotal[] = 1;
			
			$showresults .= __(" View=Current; ",'wp-ada-compliance-basic');

		}
		if ( $view == 2 ) { // all
				
		if($report_filtered_errors == 'false'){
			$query .= " and ignre != %d ";
			$totalquery .= " and ignre != %d";
			$queryVariablesMain[] = 2;
			$queryVariablesTotal[] = 2;	
			}	
			$showresults .= __(" View=All; ",'wp-ada-compliance-basic');

		}

		if ( $view == 3 ) { // ignored
			$query .= " and ignre = %d ";
			$totalquery .= " and ignre = %d";

			$queryVariablesMain[] = 1;
			$queryVariablesTotal[] = 1;
			
			$showresults .=__(" View=Ignored; ",'wp-ada-compliance-basic');

		}
		if( $view == 4 ) { // auto corrected issues
			$query .= " and ignre = %d ";
			$totalquery .= " and ignre = %d";

			$queryVariablesMain[] = 2;
			$queryVariablesTotal[] = 2;
			
			$showresults .= __(" View=Auto Corrected; ",'wp-ada-compliance-basic');

		}
		if ( $errorid != '' ) { // filter by post type
			$query .= ' and postid = %d ';
			$totalquery .= " and postid = %d ";
		
			$queryVariablesMain[] = $errorid;
			$queryVariablesTotal[] = $errorid;
			
			$showresults = __(' View=PostID: ','wp-ada-compliance-basic').$errorid.';';
			}
	

		
		if ( $type != '' ) { // filter by post type
			
			$query .= ' and ( type = %s)';
			$totalquery .= '  and (type = %s)';
			$queryVariablesMain[] = $type;
			$queryVariablesTotal[] = $type;
			
			
			$showresults .= __(' Post type=','wp-ada-compliance-basic').$type.'; ';
			}
		if ( $error != '' ) { // filter by error code
			$query .= " and errorcode = %s ";			
			$totalquery .= " and errorcode = %s";
			
			$queryVariablesTotal[] = $error;
			$queryVariablesMain[] = $error;
			
			$showresults .=__(' Error type=','wp-ada-compliance-basic').$error.'; ';
		}
		
		if ( $searchtitle != '' ) { // filter by post title
			$query .= " and posttitle LIKE %s ";			
			$totalquery .= " and posttitle LIKE %s";
			
			$queryVariablesTotal[] = '%'.$searchtitle.'%';
			$queryVariablesMain[] = '%'.$searchtitle.'%';
			
			$showresults .=__(' Title=','wp-ada-compliance-basic').stripslashes($searchtitle).'; ';
		}
				
		$showresults .= __(' Sorted by=','wp-ada-compliance-basic').$sortby.';';
		
		$query .= " order by $sortby limit %d offset %d";

		$queryVariablesMain[] = $per_page;
		$queryVariablesMain[] = $offset;

		$total = $wpdb->get_var($wpdb->prepare($totalquery, $queryVariablesTotal));

		$results = $wpdb->get_results( $wpdb->prepare($query, $queryVariablesMain), ARRAY_A );
		
		// display error summary
		if (isset($_COOKIE['hide-wp-ada-summary']) or (isset($_GET['displaysummary']) and $_GET['displaysummary'] == 0)) $hidesummary = 1;
		
		if(isset($hidesummary)) 
		echo '<button type="button" class="summary-dismiss"><i class="fas fa-toggle-off"></i> '.__('Show Summary','wp-ada-compliance-basic').'</button>';
		else 
		echo '<button type="button" class="summary-dismiss"><i class="fas fa-toggle-on"></i> '.__('Hide Summary','wp-ada-compliance-basic').'</button>';
		echo '<div class="wp_ada_summary"';
		if(isset($hidesummary)) echo ' style="display:none;" ';
		echo'>';
		wp_ada_compliance_basic_error_summary($view, $type, $error, $searchtitle, $errorid);	
	 	echo '</div>';		
			
	}
	
if(!isset($_GET['iframe']) and $scaninprogress == 0){ // specific post
echo '<div class="wp-ada-compliance-buttns">';	
	
	$current_cron_count = get_option('wp_ada_compliance_basic_cron_count','0');
	if($current_cron_count != 0 and isset($_GET['startscan'])) {	
		
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&startscan=1&scanmore=1&sort='.esc_attr($sort).'" class="startscan btnwpada btnwpada-primary"><i class="fas fa-forward" aria-hidden="true"></i> ';
	_e('Scan More', 'wp-ada-compliance-basic');
	echo '</a> ';		
	}
	else{
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&startscan=1&sort='.esc_attr($sort).'" class="startscan btnwpada btnwpada-primary"><i class="fas fa-forward" aria-hidden="true"></i> ';
	_e('Start Scan', 'wp-ada-compliance-basic');
	echo '</a> ';	
	}
	
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view='.esc_attr($view).'&type='.esc_attr($type).'&error='.esc_attr($error).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle)).'&refresh=1&sort='.esc_attr($sort).'" class="btnwpada btnwpada-primary"><i class="fas fa-sync-alt" aria-hidden="true"></i> ';
	_e('Refresh View', 'wp-ada-compliance-basic');
	echo '</a> ';	
	echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view=1&type=&error=&errorid=&searchtitle=&refresh=1&sort=" class="btnwpada btnwpada-primary"><i class="fas fa-filter" aria-hidden="true"></i> ';
	_e('Clear Filters', 'wp-ada-compliance-basic');
	echo '</a> ';	
	
	if (current_user_can($settingsuser) ){ 
		echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=wp-ada-compliance-basic-admin" class="btnwpada btnwpada-primary"><i class="fas fa-cog" aria-hidden="true"></i> ';
	_e('Settings', 'wp-ada-compliance-basic');
	echo '</a> ';
	
	}
	if(count($results) > 0)	{
	echo ' <a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/send-report.php&error='.esc_attr($error).'&type='.esc_attr($type).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle)).'&view='.esc_attr($view).'&iframe=1&TB_iframe=true&width=450&height=250" class="thickbox btnwpada btnwpada-primary" name="'.__('Email this report.', 'wp-ada-compliance-basic').'"><i class="fas fa-envelope" aria-hidden="true"></i> '.__('Email', 'wp-ada-compliance-basic').'</a>';
	

		echo ' <a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/print-report.php&error='.esc_attr($error).'&type='.esc_attr($type).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle)).'&view='.esc_attr($view).'&iframe=1&TB_iframe=true&width=450&height=250" class="thickbox btnwpada btnwpada-primary" name="'.__('Print has started, close this window when the report completes.', 'wp-ada-compliance-basic').'"><i class="fas fa-print" aria-hidden="true"></i> '.__('Print', 'wp-ada-compliance-basic').'</a>';	
    }
	echo '</div>';
// display dropdown filters		
echo wp_ada_compliance_basic_dropdown_builder($view, $error, $type, $searchtitle, $showresults, $sort);	

}
elseif(count($results) > 0 and $scaninprogress == 0)	{
		echo ' <a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/print-report.php&error='.esc_attr($error).'&type='.esc_attr($type).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle)).'&view=2&iframe=1&TB_iframe=true&width=450&height=250" class="thickbox btnwpada btnwpada-primary"><i class="fas fa-print" aria-hidden="true"></i> '.__('Print', 'wp-ada-compliance-basic').'</a>';	
	
}

	
		// display messages
echo '<div class="wp_ada_compliance_notice_container">';
    echo '<button aria-label="'.__('hide notices','wp-ada-compliance-basic').'" class="wpadahidenotices"><i class="fas fa-times" aria-hidden="true"></i></button>';
echo '<div class="wp_ada_compliance_notices">';        
if(isset($_SESSION['my_ada_important_notices'])) {
	echo $_SESSION['my_ada_important_notices'];
	unset($_SESSION['my_ada_important_notices']);
}elseif(isset($_GET['scansingle'])) {	
            echo '<i class="far fa-check-circle" aria-hidden="true"></i> ';
			if(isset($total) and $total == 0) 
			echo __('SCAN IS COMPLETE: No issues were found! ' ,'wp-ada-compliance-basic').'<p class="adaRedText">'.__( 'WP ADA Compliance Basic is limited to 15 posts or pages, includes 22 fewer error checks and will not scan your entire website. Upgrade to the full version to enable more comprehensive error checks and to identify issues in theme files, custom fields, shortcodes, widgets and other parts of your website.' ,'wp-ada-compliance-basic').'</p>';
			else
			echo __('SCAN IS COMPLETE: Results are displayed in the report below. ','wp-ada-compliance-basic').'<p class="adaRedText">'.__( ' WP ADA Compliance Basic is limited to 15 posts or pages, includes 22 fewer error checks and will not scan your entire website. Upgrade to the full version to enable more comprehensive error checks and to identify issues in theme files, custom fields, shortcodes, widgets and other parts of your website. ' ,'wp-ada-compliance-basic').'</p>';			
			}
	elseif(isset($_GET['startscan'])) {
				if(isset($_SESSION['wp_ada_compliance_message']) and $_SESSION['wp_ada_compliance_message'] != ""){
				echo esc_html($_SESSION['wp_ada_compliance_message']);
				}else{
                     echo '<i class="far fa-check-circle" aria-hidden="true"></i> ';
			_e('SCAN IS COMPLETE: A maximum of 15 items were scanned.','wp-ada-compliance-basic');
			
			_e(' Upgrade to the full version to remove the scan limit and enable unlimited deep scans which will check your entire website. The full version includes 22 additional error checks, will identify issues in theme files, custom fields, shortcodes, widgets, archives and much more. The automatic scan feature will monitor your website for issues while you are offline and send detailed email reports.','wp-ada-compliance-basic');
				}		
		}
elseif(!$results and !isset($_GET['iframe'])){
      echo '<i class="fas fa-info-circle" aria-hidden="true"></i> ';
      if(!$results) _e('Click "START SCAN" to begin','wp-ada-compliance-basic');
}
else{
     echo '<i class="fas fa-info-circle" aria-hidden="true"></i> ';
    _e('Look for status notices here.','wp-ada-compliance-basic');  
}
echo '</div>';	
echo '</div>';    
	
if (isset($_GET['startscan']) or $results) {

if ( $results and $scaninprogress == 0){				
	//display the pagination
	
$pagination = paginate_links(array(
     'base' => add_query_arg('cpage', '%#%', esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&refresh=1&view='.esc_attr($view).'&type='.esc_attr($type).'&error='.esc_attr($error).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle)).'&sort='.esc_attr($sort)),
    'format' => '',
		'mid_size'  => 2,
	'current' => 'true',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($total / $per_page),
    'current' => $page
));
if(isset($_GET['startscan'])){
echo '<p class="wp_ada_light_red">';
_e('You may have issues in your theme files. Upgrade to the full version to scan your entire website. Full scans will identify issues in theme files, custom fields,  shortcodes, widgets, archives, linked pages and PDF files. The full version also corrects many issues automatically including: missing skip links, missing landmarks, new window links, empty tags, redundant ALT text and more.','wp-ada-compliance-basic');		
echo '</p>';
}

		
if($pagination != "") echo $pagination = '<p class="hideduringscan">'.$pagination.'</p>';

echo '<table class="ada_error hideduringscan"><tr>';
	echo '<th scope="column">';
	_e('Title', 'wp-ada-compliance-basic');
	echo '</th>';
	echo '<th scope="column">';
		_e('Content Type', 'wp-ada-compliance-basic');

	echo '</th>';
	echo '<th scope="column">';
		_e('Error Type', 'wp-ada-compliance-basic');
	
	echo '</th>';
	echo '<th scope="column" >';
	_e('Error', 'wp-ada-compliance-basic');
	echo '</th>';
	echo '<th scope="column" class="printhidden">';
	_e('Affected Code', 'wp-ada-compliance-basic');
	echo '</th>';
	echo '<th scope="column" class="printhidden wp_ada_action_column">';
	_e('Actions', 'wp-ada-compliance-basic');
	echo '</th></tr>';

		foreach ( $results as $row ) {

		echo '<tr class="errorid'.esc_attr($row['errorcode']).'-'.md5($row['object']).' errorid'. esc_attr($row['id']) .' ruleid_'.esc_attr($row['errorcode']).'">';
		echo '<td>';
		if(!isset($_GET['iframe'])){ // hide in iframe	
		echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&view='. esc_attr($view).'&type='.esc_attr($type).'&error='.esc_attr($error).'&errorid='.esc_attr($errorid).'&searchtitle='.esc_attr($row['posttitle']).'&refresh=1&sort='.esc_attr($sort).'" title="'.__('filter results on this title', 'wp-ada-compliance-basic').'">';
		printf(__('%s', 'wp-ada-compliance-basic'), esc_html($row['posttitle']));
		echo '</a>';
		}
		else printf(__('%s', 'wp-ada-compliance-basic'), esc_html($row['posttitle']));
		echo '<br /><span class="adaIgnored ignore'.esc_attr($row['id']).' ignore-'.esc_attr($row['errorcode']).'-'.md5($row['object']);

			echo '"';
			if($row['ignre'] != 1) echo ' style="display:none;" ';
			echo '>';
			_e('** being ignored', 'wp-ada-compliance-basic');
			echo '</span>';
		if($row['ignre'] == 2) {
			echo '<br /><span class="adaIgnored" title="';
			_e('Upgrade to the full version to automatically correct this issue.','wp-ada-compliance-basic');	
			echo '">';
			_e('** upgrade to auto correct this issue!', 'wp-ada-compliance-basic');
			echo '</span>';
		}
  
		echo '</td>';
		echo '<td class="contenttype">';
		printf(__('%s', 'wp-ada-compliance-basic'), esc_html($row['type']));
                           echo wp_ada_compliance_basic_format_error_location($row['ignre'], $row['errorcode'],$row['type'],$row['id'], $row['themeerror'],1);
			echo '</td>';
		echo '<td>';
		printf(__('%s', 'wp-ada-compliance-basic'), str_replace("_", " ",  esc_html($row['errorcode'])));
			echo '</td>';
		echo '<td class="printfixedwidth">';
		if(strstr($wp_ada_compliance_basic_def[$row['errorcode']]['DisplayError'],"WARNING")) echo '<i class="fas fa-ban" aria-hidden="true"></i>';
		elseif(strstr($wp_ada_compliance_basic_def[$row['errorcode']]['DisplayError'],"ALERT")) echo '<i class="fas fa-exclamation-circle" aria-hidden="true"></i>';
		
		printf(__('%s', 'wp-ada-compliance-basic'),  esc_html($wp_ada_compliance_basic_def[$row['errorcode']]['StoredError']));
		echo '</div>';
		
		// display error references	
		if(is_array($wp_ada_compliance_basic_def)){
		if($wp_ada_compliance_basic_def[$row['errorcode']]['Reference'] != "") echo ' <a href="'.esc_url($wp_ada_compliance_basic_def[$row['errorcode']]['ReferenceURL']).'" target="_blank" class="adaNewWindowInfo">'.esc_html($wp_ada_compliance_basic_def[$row['errorcode']]['Reference']).' <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
		echo '<a href="#" class="adaHelpLinkToggle  adaHelpLink viewHelp' . esc_attr($row['id']) . '"><i class="fas fa-question-circle" aria-hidden="true"></i> '.__('HELP', 'wp-ada-compliance-basic').'</a>';
		echo '<div class="adaHelpText helptext' . esc_attr($row['id']) . '">'.$wp_ada_compliance_basic_def[$row['errorcode']]['HelpINSTR']; // don't escape this it will break help instructions
		if($wp_ada_compliance_basic_def[$row['errorcode']]['HelpURL'] != "") echo ' <a href="'.esc_url($wp_ada_compliance_basic_def[$row['errorcode']]['HelpURL']).'" target="_blank" class="adaViewbar adaNewWindowInfo">'.__('More Help', 'wp-ada-compliance-basic').'  <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
		echo '</div>';
		}
	
		echo '</td>';
		echo '<td class="printfixedwidth">';
		echo '<span class="viewCode'.esc_attr($row['id']).' adaViewCode"><a href="#TB_inline?width=550&height=500&inlineId=code'.esc_attr($row['id']).'" class="thickbox" name="'.__('Code View','wp-ada-compliance').'"><i class="fas fa-eye" aria-hidden="true"></i>';
		_e('View Code','wp-ada-compliance-basic');
		echo '</a></span>';
			
		echo '<div class="adaEffectedCode code'.esc_attr($row['id']).'" id="code'.esc_attr($row['id']).'">';
        
        if($row['errorcode'] != 'html_validation' and $row['errorcode'] != 'missing_landmarks' and $row['errorcode'] != 'unlabeled_landmarks' and $row['errorcode'] != 'skip_nav_links' )
       echo ' <code style="background-color: #fff;">'.wp_ada_compliance_basic_filter_autoplay_av_tags(esc_html($row['object']), "1").'</code>';
		
      $trustedtags = '<svg><metadata><g><path><button><embed><iframe><p><br /><a><img><h1><h2><h3><h4><h5><h6><input><map><area><audio><video><pre><textarea><label><select><span><blink><i><fieldset><caption><form><legend><br><div><nav><main><aside><main><header><footer>';
		if(stristr($row['object'],'<table')) {
			$row['object'] = wp_ada_compliance_basic_close_unclosed_tables($row['object']);
			$trustedtags .= '<table><tr><td><th><tbody><thead>';
		}
	
		echo '<br /><code>'.strip_tags($row['object'],$trustedtags).'</code>';	
		if($row['examplecode'] != "") {
			echo '<br /><code>'.strip_tags($row['examplecode'],'<div>').'</code>';	
		}
			
		echo '</div>';
			
		echo '</td>'; 
		echo '<td class="wp_ada_action_column printhidden"> ';
		echo '<div><a href="#" class="wp-ada-ignore-options-click wp-ada-ignore-options-click'.esc_attr($row['id']).'"><i class="fas fa-cog" aria-hidden="true"></i>'.__('Ignore','wp-ada-compliance-basic').'</a>';
			
		echo '<span class="wp-ada-ignore-options wp-ada-ignore-options'.esc_attr($row['id']).'">';
		
		if($row['ignre'] != 1){		
			// ignore this instance
		echo' <a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&wpada_ignore='.esc_attr($row['id']).'&sort='.esc_attr($sort).'&view='.esc_attr($view).'&type='.esc_attr($type).'&error='.esc_attr($error).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle));
		if(isset($_GET['iframe'])) echo '&iframe=1';
		echo '" title="'.__('Ignore this instance of the error.','wp-ada-compliance-basic').'" id="wpadaignore_'.esc_attr($row['id']).'_'.esc_attr($row['ignre']).'" class="wp_ada_compliance_basic_ignoreerror addignore"><i class="fas fa-eye-slash" aria-hidden="true"></i>';
		_e('This Error','wp-ada-compliance-basic');
		echo '</a><br />';	
			
		 echo' <a class="basicdisabled" title="'.__('Ignore all errors of this type found in this page. (Not available in the basic version) ','wp-ada-compliance-basic').'" aria-hidden="true" tabindex="-1"><i class="fas fa-list" aria-hidden="true"></i>';
		_e('In This Page','wp-ada-compliance-basic');
		echo '</a><br />';	
		
		echo ' <a class="basicdisabled" title="'.__('Ignore this issue and all errors that appear to be duplicates of it. (Not available in the basic version) ','wp-ada-compliance-basic').'" aria-hidden="true" tabindex="-1"><i class="far fa-clone" aria-hidden="true"></i>';
		_e('Duplicates','wp-ada-compliance-basic');
		echo '</a><br />';

		}
			
		if($row['ignre'] == 1){
		echo' <a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&wpada_ignore='.esc_attr($row['id']).'&sort='.esc_attr($sort).'&canxignore=1&view='.esc_attr($view).'&type='.esc_attr($type).'&error='.esc_attr($error).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle));
		if(isset($_GET['iframe'])) echo '&iframe=1';
		echo'" title="'.__('Remove ignore from this error instance.','wp-ada-compliance-basic').'" id="wpadaignore_'.esc_attr($row['id']).'_'.esc_attr($row['ignre']).'" class="wp_ada_compliance_basic_ignoreerror removeignore"><i class="fas fa-times-circle"></i>';
		_e('This Error','wp-ada-compliance-basic');
		echo '</a><br />';
			
			
		
		}	
			
	//	if(!isset($_GET['iframe'])){ // hide in iframe
		if (current_user_can($settingsuser) ){ 
		echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&wpada_ignore_rule='.esc_attr($row['errorcode']).'&type='.esc_attr($type).'&view='.esc_attr($view).'&sort='.esc_attr($sort).'&errorid='.esc_attr($errorid).'&searchtitle='.stripslashes(esc_attr($searchtitle)).'" title="'.__('Remove this error from the results as well as future scans.','wp-ada-compliance-basic').'" id="wpadaignorerule|'.esc_attr($row['id']).'|'.esc_attr($row['errorcode']).'" class="wp_ada_compliance_basic_ignorerule"><i class="fas fa-tasks" aria-hidden="true"></i>';
		_e('This Rule','wp-ada-compliance-basic');
		echo '</a><br />'; 
	//	}
		}	
			
	echo' <a title="'.__('Remove this item from the results and future scans. (Not available in the basic version) ','wp-ada-compliance-basic').'" aria-hidden="true" tabindex="-1" class="basicdisabled"><i class="far fa-file" aria-hidden="true"></i>';
		_e('This File','wp-ada-compliance-basic');
		echo '</a><br />';			
		echo '</span>';	
			echo '</div>';
		
		// display edit options
		if(!isset($_GET['iframe'])){ // hide in iframe	
		echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php&scansingle=1&errorid='.esc_attr($row['id']).'&sort='.esc_attr($sort).'&type='.esc_attr($row['type']).'&taxonomy='.esc_attr($row['taxonomy']).'&postid='.esc_attr($row['postid']).'&view='.esc_attr($view).'&searchtitle='.esc_attr($row['postid']).'" class="wp_ada_compliance_basic_recheck" id="'.esc_attr($row['type']).'|'.esc_attr($row['postid']).'"><i class="fas fa-sync-alt" aria-hidden="true"></i>';
		_e('Recheck','wp-ada-compliance-basic');
		echo '</a>'; 
			echo '<br />';
			

		echo '<a href="post.php?post='.esc_attr($row['postid']).'&action=edit"  target="_blank" class="adaNewWindowInfo"><i class="far fa-edit" aria-hidden="true" ></i>';
		_e('Edit','wp-ada-compliance-basic');
		echo ' <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
			echo '<br />';
		
		echo '<a href="'.esc_url(get_permalink($row['postid'])).'" target="_blank" class="adaNewWindowInfo"><i class="fas fa-eye" aria-hidden="true" ></i>';
		_e('View','wp-ada-compliance-basic');
		echo ' <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
			echo '<br />';
		
		}
		
		echo '<a href="#" onclick="return false;" tabindex="-1" style="margin-left:0px;" class="wp_ada_label wp_ada_label_disabled" title="'.__('Notes are not available in the basic version, upgrade to the full version to share notes with team members.','wp-ada-compliance-basic').'"><i class="far fa-sticky-note" aria-hidden="true"></i>';
		_e('Notes','wp-ada-compliance-basic');
		echo '</a>';

		echo '</td>';
		echo '</tr>';
	}
		echo '</table>';
	
		
		if(isset($pagination)) echo $pagination;	
		
		echo '<p class="wp_ada_error_key hideduringscan">';
		echo '<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ';
		_e('ALERTS - issues that MAY BE corrected to improve web accessibility, enhance a user\'s experience or avoid the possibility of inaccessible content inadvertently being introduced into a website.','wp-ada-compliance-basic');	
				echo '<br /><br />';
		echo '<i class="fas fa-ban" aria-hidden="true"></i> ';
		_e('WARNINGS - issues that MUST BE corrected to ensure compliance with Section 508 or WCAG 2.1 LEVEL A/AA Web Accessibility Standards and ensure content is accessible to users with disabilities.','wp-ada-compliance-basic');	
		echo '</p>';
		echo '</div>';
}
	}
	
	if(!$results){
	
	if(isset($_GET['startscan']) or isset($_GET['scansingle'])) {
			echo '<p class="wp_ada_compliance_basic_scanstatus">';	
        _e('No issues were found!','wp-ada-compliance-basic');	
		echo '</p>';
    }      
		echo '</div>';

	}
			// stop header sent warnings
		if (isset($_GET['_wpnonce'])) exit;
}

/********************************************
// create guidelines reference page
***********************************************/
function wp_ada_compliance_basic_referencereport_page() {
global $wp_ada_compliance_basic_def;
	// check cap allowed to edit settings
$settingsuser = get_option('wp_ada_compliance_basic_settingsusers','manage_options');	
	
	echo '<div class="adaReferenceReport">';
	echo '<h2>'.__('ADA Compliance Guidelines Reference', 'wp-ada-compliance-basic').'</h2>';	
	
			echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=ada_compliance/compliancereportbasic.php" class="btnwpada btnwpada-primary"><i class="fas fa-file-alt" aria-hidden="true"></i> ';
	_e('View Report', 'wp-ada-compliance-basic');
	echo '</a> ';
	
	if (current_user_can( $settingsuser ) ){ 
		echo '<a href="'.esc_url(get_site_url()).'/wp-admin/admin.php?page=wp-ada-compliance-basic-admin" class="btnwpada btnwpada-primary"><i class="fas fa-cog" aria-hidden="true"></i> ';
	_e('Settings', 'wp-ada-compliance-basic');
	echo '</a> ';
	}
	
	echo '<a href="#" class="btnwpada btnwpada-primary" onclick="javascript:window.print(); return false;"><i class="fas fa-print" aria-hidden="true"></i> '.__('Print', 'wp-ada-compliance-basic').'</a>';
	
	echo '<p class="adamarketingtext">'.__('The WP ADA Compliance Basic Plugin evaluates content for the most common issues typically found in the Wordpress page editor. The full version will scan your entire website and includes many accessibility checks not available in the basic plugin. The full version also corrects many issues automatically. The following is a list of issues that the basic plugin will identify.', 'wp-ada-compliance-basic').'</p>';

echo '<p class="wp_ada_error_key">';
		echo '<i class="fas fa-exclamation-circle" aria-hidden="true"></i> ';
		_e('ALERTS - issues that MAY BE corrected to improve web accessibility, enhance a user\'s experience or avoid the possibility of inaccessible content inadvertently being introduced into a website.','wp-ada-compliance-basic');	
		echo '<br /><br />';
		echo '<i class="fas fa-ban" aria-hidden="true"></i> ';
		_e('WARNINGS - issues that MUST BE corrected to ensure compliance with Section 508 or WCAG 2.1 LEVEL A/AA Web Accessibility Standards and ensure content is accessible to users with disabilities.','wp-ada-compliance-basic');	
		echo '</p>';
	
echo '<a id="adascrollbutton" aria-label="Top"></a>';	
// display error references	

if(is_array($wp_ada_compliance_basic_def))
foreach ($wp_ada_compliance_basic_def as $rows => $row){	
	echo '<div class="adaReference">';
	if(strstr($row['DisplayError'],"WARNING")) echo '<i class="fas fa-ban" aria-hidden="true"></i>';
		elseif(strstr($row['DisplayError'],"ALERT")) 
			echo '<i class="fas fa-exclamation-circle" aria-hidden="true"></i>';
	echo esc_html($row['StoredError']);
	if($row['Reference'] != "") 
		echo ' <a href="'. esc_url($row['ReferenceURL']).'" target="_blank" class="adaNewWindowInfo">'. esc_html($row['Reference']).' <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
	echo '<a href="#" class="adaHelpLinkToggle adaHelpLink viewHelp' .  esc_attr($rows) . '"><i class="fas fa-question-circle" aria-hidden="true"></i> '.__('HELP', 'wp-ada-compliance-basic').' </i></a>';
	echo '<div class="adaHelpText helptext' .  esc_attr($rows) . '">'. $row['HelpINSTR']; // don't escape this it will break help instructions
	if($row['HelpURL'] != "") echo ' <a href="'. esc_url($row['HelpURL']).'" target="_blank" class="adaViewbar adaNewWindowInfo">'.__('More Help', 'wp-ada-compliance-basic').' <i class="fas fa-external-link-alt" aria-hidden="true"><span class="wp_ada_hidden">'.__('opens in a new window', 'wp-ada-compliance-basic').'</span></i></a>';
	echo '</div>';
	echo '</div>';
	
}
echo '</div>';
}

/*********************************************
REPORT PAGE SUPPORT FUNCTIONS
********************************************/
/*********************************************
create filter drop downs
********************************************/
function wp_ada_compliance_basic_dropdown_builder($view, $error, $type, $searchtitle, $showresults, $sort){
global $wpdb;

$report_filtered_errors = get_option('wp_ada_compliance_basic_report_filtered_errors','scanonly');	
	
$dropdownlists= '<form name="filtererrors" class="wp_ada_form hideduringscan" action="'.esc_url(get_site_url()).'/wp-admin/admin.php" method="get"><input type="hidden" name="page" value="ada_compliance/compliancereportbasic.php" />
<input type="hidden" name="sort" value="'.esc_attr($sort).'" />';
$dropdownlists .= wp_ada_compliance_basic_promotions(); 
// display view by
$dropdownlists .= '<label for="view" class="wp_ada_label">'.__('View: ', 'wp-ada-compliance-basic').'<select name="view" id="view">';
$dropdownlists.= '<option value="2"';
if($view == '2') $dropdownlists.= ' selected';
$dropdownlists .='>'.__('All','wp-ada-compliance-basic').'</option>';	
$dropdownlists.= '<option value="3"';
if($view ==  '3') $dropdownlists.= ' selected';
$dropdownlists .='>'.__('Ignored','wp-ada-compliance-basic').'</option>';	
	$dropdownlists.= '<option value="1"';
if($view == '1') $dropdownlists.= ' selected';
$dropdownlists .='>'.__('Current','wp-ada-compliance-basic').'</option>';	
$dropdownlists.= "</select></label>";	
	
// filter by error code
$query = "SELECT distinct(errorcode) FROM ".$wpdb->prefix."wp_ada_compliance_basic order by errorcode";
$results = $wpdb->get_results($query, ARRAY_A );	
$dropdownlists .= '<label for="errortype" class="wp_ada_label">'.__('Error Type: ', 'wp-ada-compliance-basic').'<select name="errorw" id="errortype">';
$dropdownlists.= '<option value="">'.__('Any','wp-ada-compliance-basic').'</option>';	
foreach ( $results as $row ) {
$dropdownlists.= '<option value="'.esc_attr($row['errorcode']).'"';
if($error == $row['errorcode']) $dropdownlists.= ' selected';
$dropdownlists.= '>'.esc_attr__(str_replace("_", " ", $row['errorcode']),'wp-ada-compliance-basic').'</option>';
}
$dropdownlists.= "</select></label>";
	
// filter by post type	
$query = "SELECT distinct(type) FROM ".$wpdb->prefix."wp_ada_compliance_basic";
$results = $wpdb->get_results( $query, ARRAY_A );
$dropdownlists .= '<label for="posttype" class="wp_ada_label">'.__('Post Type: ', 'wp-ada-compliance-basic').'<select name="type" id="posttype">';
$dropdownlists.= '<option value="">'.__('Any','wp-ada-compliance-basic').'</option>';
foreach ( $results as $row ) {
$dropdownlists.= '<option value="'.esc_attr($row['type']).'"';
if($type == $row['type']) $dropdownlists.= ' selected';
$dropdownlists.= '>'.esc_attr__($row['type'],'wp-ada-compliance-basic').'</option>';
}
$dropdownlists.= '</select></label> ';
    $dropdownlists.= '<label for="searchtitle" class="wp_ada_label">';
$dropdownlists.= __('Search:','wp-ada-compliance');
$dropdownlists.= '<input type="text" name="searchtitle" id="searchtitle" value="';

$dropdownlists.= stripslashes(esc_attr($searchtitle));	

$dropdownlists.= '" aria-label="'.__('Post Title or Post ID', 'wp-ada-compliance-basic').'" placeholder="'.__('Post Title or Post ID', 'wp-ada-compliance-basic').'" onfocus="this.value=\'\'"></label>';

// sort list
$dropdownlists.= '<label for="sort" class="wp_ada_label sortby">'.__('Sort by: ','wp-ada-compliance-basic').' <select id="sort" name="sort">
';
$dropdownlists.= '<option value="1"';
if($sort == 1) $dropdownlists.= ' selected';	
$dropdownlists.= '>';
$dropdownlists.= __('Date', 'wp-ada-compliance-basic');
$dropdownlists.= '</option>';
$dropdownlists.= '<option value="2"';
if($sort == 2) $dropdownlists.= ' selected';	
$dropdownlists.= '>';
$dropdownlists.= __('Content Type', 'wp-ada-compliance-basic');
$dropdownlists.= '</option>';
$dropdownlists.= '<option value="3"';
if($sort == 3) $dropdownlists.= ' selected';	
$dropdownlists.= '>';
$dropdownlists.= __('Error Type', 'wp-ada-compliance-basic');
$dropdownlists.= '</option>';
$dropdownlists.= '<option value="4"';
if($sort == 4) $dropdownlists.= ' selected';	
$dropdownlists.= '>';
$dropdownlists.= __('Error State (ignore, current, etc)', 'wp-ada-compliance-basic');
$dropdownlists.= '</option>';	
$dropdownlists.= ' <option value="5"';
if($sort == 5) $dropdownlists.= ' selected';	
$dropdownlists.= '>';
$dropdownlists.= __('Title', 'wp-ada-compliance-basic');
$dropdownlists.= '</option>';	
$dropdownlists.= '</select></label>';	
  $dropdownlists.= '<br><br><label for="modifieddate" class="wp_ada_label">';
$dropdownlists.= __('Modified Date: ','wp-ada-compliance-basic');
$dropdownlists.= '<input size="10" type="date" name="modifieddate" readonly id="modifieddate" value="" title="'.__('Not available in the basic version', 'wp-ada-compliance-basic').'"></label>';  	
  $dropdownlists .='<p class="wp-ada-compliance-filters">
<label for="excludethemes" class="wp_ada_label wp_ada_label_disabled" title="'.__('Not available in the basic version', 'wp-ada-compliance-basic').'"><input type="checkbox" name="excludethemes" id="excludethemes" value="1" disabled>'.__('hide theme errors', 'wp-ada-compliance').'</label><label for="excludedups" class="wp_ada_label wp_ada_label_disabled" title="'.__('Not available in the basic version', 'wp-ada-compliance-basic').'"><input disabled type="checkbox" name="excludedups" id="excludedups" value="1">'.__('hide duplicates', 'wp-ada-compliance').'</label>';
          
	// submit	
$dropdownlists.= '<p><input type="submit" value="'.__('Filter', 'wp-ada-compliance-basic').'" class=" wp_ada_label btnwpada btnwpada-primary filterbtn" /></p>';
    
 

    
  
	
	// display filter message
if(isset($showresults)){
$dropdownlists .='<p class="adashowingmessage">';
$dropdownlists .=__('Filters: ', 'wp-ada-compliance-basic');
$dropdownlists .= sprintf(__('%s', 'wp-ada-compliance-basic'), esc_attr($showresults));
$dropdownlists .='</p>';	
	}	
$dropdownlists .='</form>';		
return $dropdownlists;	
}
/*********************************************
create error summary
********************************************/
function wp_ada_compliance_basic_error_summary($view, $type, $error, $searchtitle, $errorid){
global $wpdb;

	
$query = "SELECT * FROM ".$wpdb->prefix."wp_ada_compliance_basic where %d";
$queryVariables = array();	
$queryVariables[] = 1;	
$totalquery ='';	
	
// hide if auto filter is turned off
$report_filtered_errors = get_option('wp_ada_compliance_basic_report_filtered_errors','scanonly');
if($report_filtered_errors == 'false') $totalquery .= " and ignre != 2 "; 	
	
if( $view == 1 ) { // current
$totalquery .= " and ignre != %d";
$queryVariables[] = 1;
}
if ( $view == 3 ) { // ignored
$totalquery .= " and ignre = %d";
$queryVariables[] = 1;
}
if( $view == 4 ) { // auto corrected issues
$totalquery .= " and ignre = %d";
$queryVariables[] = 2;
}
if ( $type != '' ) { // filter by post type
$totalquery .= '  and (type = %s)';
$queryVariables[] = $type;
}
if ( $error != '' ) { // filter by error code
$totalquery .= " and errorcode = %s";
$queryVariables[] = $error;
}
if ( $searchtitle != '' ) { // filter by error code	
$totalquery .= " and posttitle LIKE %s";		
$queryVariables[] = '%'.$searchtitle.'%';
}
if ( $errorid != '' and $errorid != 0 ) { // filter by error code	
$totalquery .= " and postid = %d ";
$queryVariables[] = $errorid;
}	
	
$query.=$totalquery;

$records = $wpdb->get_results($wpdb->prepare($query, $queryVariables),ARRAY_A);	

$total = count($records);	

wp_ada_compliance_basic_dashboard_summary();
echo '<div class="wp_ada_summary_right">';
echo '<h2 class="wp_ada_summary_header">'; 
_e('Issue Summary','wp-ada-compliance-basic');	
echo '</h2>'; 	
echo '<p class="wp_ada_issue_sum">';
echo '<span class="adaViewbar">';
_e('total issues: ', 'wp-ada-compliance-basic');
	echo '</span>';
echo esc_html($total);
echo '</p>';
if ( $error == '' ) {	
$query = "SELECT distinct(errorcode) FROM ".$wpdb->prefix."wp_ada_compliance_basic ";
$results = $wpdb->get_results($query, ARRAY_A );	

foreach ( $results as $row ) {
echo '<p class="wp_ada_issue_sum">';	
echo  wp_ada_compliance_basic_error_count($row['errorcode'], $totalquery, $queryVariables);

echo '</p>';
}
}

    // display additional checks links
 /*   echo '<p class="ada_manual_checks_prompt">';
	echo __('Using WP ADA Compliance Basic is not enough to ensure ADA Compliance. ','wp-ada-compliance-basic');
    echo '<a href="https://www.alumnionlineservices.com/docs/getting-started/will-the-wp-ada-compliance-plugin-make-my-website-completely-compliant/">';
	echo __('Learn more about how to reach full compliance.','wp-ada-compliance-basic');
	echo '</a>';
    echo '</p>';*/
	echo '</div>';

}

/*********************************************
count errors
********************************************/
function wp_ada_compliance_basic_error_count($errorcode, $totalquery, $queryVariables){
global $wpdb;

$query = "SELECT * FROM ".$wpdb->prefix."wp_ada_compliance_basic where %d ";
$query.=$totalquery;	
	
$query .= " and errorcode = %s ";
$queryVariables[] = $errorcode;	

$results = $wpdb->get_results( $wpdb->prepare( $query, $queryVariables), ARRAY_A );	

$total = count($results);	
	
foreach ( $results as $row ) {
return '<span class="adaViewbar">'.str_replace("_", " ", esc_attr($errorcode)).':</span> '.esc_attr($total).'<br />';
}
}

/********************************************
// create email report
**********************************************/
function wp_ada_compliance_basic_create_email_report($email, $postinfo=0) {
global $wpdb, $wp_ada_compliance_basic_def;
	$showresults = "";
	$queryVariablesMain = array();
	if(is_array($postinfo)){
	$query = 'SELECT * FROM '.$wpdb->prefix.'wp_ada_compliance_basic where %d ';
	// hide if auto filter is turned off
	$report_filtered_errors = get_option('wp_ada_compliance_basic_report_filtered_errors','scanonly');
	if($report_filtered_errors == 'false') $query .= " and ignre != 2 "; 
		
	$queryVariablesMain[] = 1;
        
	if(array_key_exists("view",$postinfo)){	
	if($postinfo['view'] == 1 ) {
		$query .= " and ignre != %d ";
		$queryVariablesMain[] = 1;
		$showresults .= __(" View=Current; ",'wp-ada-compliance-basic');
		}
	if($postinfo['view'] == 2 ) {
		$showresults .= __(' View=All; ','wp-ada-compliance-basic');
		}	
    if ( $postinfo['view'] == 3 ) { // ignored
			$query .= " and ignre = %d ";
			$queryVariablesMain[] = 1;
			$showresults .= __(" View=Ignored; ",'wp-ada-compliance-basic');

		}
	if( $postinfo['view'] == 4 ) { // auto corrected issues
			$query .= " and ignre = %d ";
			$queryVariablesMain[] = 2;
		$showresults .= __(" View=Auto Corrected; ",'wp-ada-compliance-basic');
		}
	}		
	if (array_key_exists("errorid",$postinfo) and $postinfo['errorid'] != '' and $postinfo['errorid'] != '0' ) { // filter by post type
			$query .= ' and postid = %d ';
			$queryVariablesMain[] = $postinfo['errorid'];
		$showresults = __(' View=PostID: ','wp-ada-compliance-basic').$postinfo['errorid'].';';
			
		}
	if (array_key_exists("type",$postinfo) and  $postinfo['type'] != '' ) { // filter by post type
			
			$query .= ' and (type = %s)';
			$queryVariablesMain[] = $postinfo['type'];
	
		
			$showresults .= __(' Post type=','wp-ada-compliance-basic').$postinfo['type'].'; ';
			}
	if (array_key_exists("error",$postinfo) and  $postinfo['error'] != '' ) { // filter by error code
			$query .= " and errorcode = %s ";			
			$queryVariablesMain[] = $postinfo['error'];
			$showresults .=__('Error type=','wp-ada-compliance-basic').$postinfo['error'].'; ';
		}
	
		if (array_key_exists("searchtitle",$postinfo) and  $postinfo['searchtitle'] != '' ) { // filter by post title
			$query .= " and posttitle LIKE %s ";			
			$queryVariablesMain[] = '%'.$postinfo['searchtitle'].'%';
			$showresults .=__('Title=','wp-ada-compliance-basic').stripslashes($postinfo['searchtitle']).'; ';
		}	
		
	if (array_key_exists("sort",$postinfo)) {
	if($postinfo['sort'] == 1) $sortby = 'date DESC, id DESC ';
	if($postinfo['sort'] == 2) $sortby = 'type asc';
	if($postinfo['sort'] == 3) $sortby = 'errorcode asc';
	if($postinfo['sort'] == 4) $sortby = 'ignre desc';
	if($postinfo['sort'] == 5)$sortby = 'posttitle asc';
	} else $sortby = "date DESC, id DESC ";
	
	$query .= " order by $sortby ";
		
	}
else{
	$notification_frequency = get_option('wp_ada_compliance_basic_notification_frequency','daily');

	
	// create interval based on frequency
	if($notification_frequency == 'monthly') $interval = '30 DAY';	
	if($notification_frequency == 'weekly') $interval = '7 DAY';	
	if($notification_frequency == 'daily') $interval = '1 DAY';	
	if($notification_frequency == 'twicedaily') $interval = '12 HOUR';	
	if($notification_frequency == 'hourly') $interval = '1 HOUR';	

	$query = 'SELECT * FROM '.$wpdb->prefix.'wp_ada_compliance_basic where ignre = %d ';
	
	// hide if auto filter is turned off
	$report_filtered_errors = get_option('wp_ada_compliance_basic_report_filtered_errors','scanonly');
	if($report_filtered_errors == 'false') $query .= " and ignre != 2 "; 
	
	$query .= 'and date >= DATE_SUB(NOW(),INTERVAL '.$interval.') order by date DESC, id DESC ';
	$queryVariablesMain[] = 0;
	}

	
	$results = $wpdb->get_results( $wpdb->prepare($query, $queryVariablesMain), ARRAY_A );

	  echo '<div class="wp_ada_compliance_report">';

	if ( sizeof($results) > 0 ) {	
	$report = '<h2>'.__('Web Accessibility Report', 'wp-ada-compliance-basic').'</h2>';
	if(isset($interval)) $report .= '<p>'.__('This report includes issues identified in the past '.$interval.'.', 'wp-ada-compliance-basic'). '</p>';
	elseif($showresults != "") $report .= '<p>'.esc_html($showresults).'</p>';	
    $report .= '<table class="ada_error" border="1"><tr>';
	$report .=  '<th scope="column" >';
	$report .= __('Title', 'wp-ada-compliance-basic');
	$report .=  '</th>';
	$report .=  '<th scope="column">';
	$report .= __('Content Type', 'wp-ada-compliance-basic');
	$report .=  '</th>';
	$report .=  '<th scope="column" >';
	$report .= __('Error Type', 'wp-ada-compliance-basic');
	$report .=  '</th>';
	$report .=  '<th scope="column" >';
	$report .= __('Error', 'wp-ada-compliance-basic');
	$report .=  '</th></tr>';

	foreach ( $results as $row ) {
	
	$userid = get_userdata($row['activeuser']);
		
			
	if((is_array($postinfo) and ($email != "" or $email == 'print')) or ($email != "" and $email != $userid->user_email)){	
		$issuefound = 1;
		
	$report .=  '<tr>';
		$report .=  '<td>';
		$report .= sprintf(__('%s', 'wp-ada-compliance-basic'), esc_html($row['posttitle']));
		if($row['ignre'] == 1) {
			$report .='<br /><span class="adaIgnored">';
			$report .=__('** being ignored', 'wp-ada-compliance-basic');
			$report .= '</span>';
		}
		elseif($row['ignre'] == 2) {
			$report .='<br /><span class="adaIgnored">';
			$report .=__('** upgrade to auto correct this issue!', 'wp-ada-compliance-basic');
			$report .= '</span>';
		}
		$report .=  '</td>';
		$report .=  '<td>';
		$report .= sprintf(__('%s', 'wp-ada-compliance-basic'), esc_html($row['type']));
		
		$report .=  '</td>';
		$report .=  '<td>';
		$report .= sprintf(__('%s', 'wp-ada-compliance-basic'), str_replace("_", " ", esc_html($row['errorcode'])));
		$report .=  '</td>';
		$report .=  '<td>';
		$report .= sprintf(__('%s', 'wp-ada-compliance-basic'), esc_html($wp_ada_compliance_basic_def[$row['errorcode']]['StoredError']));
		
		
		// if printing display error
		if($email == 'print'){
		$report .= '<div class="adaEffectedCode code'.esc_attr($row['id']).'" id="code'.esc_attr($row['id']).'">';
             
            if($row['errorcode'] != 'html_validation' and $row['errorcode'] != 'missing_landmarks' and $row['errorcode'] != 'unlabeled_landmarks' and $row['errorcode'] != 'skip_nav_links' )  
           $report .= '<code>'.wp_ada_compliance_basic_filter_autoplay_av_tags(esc_html($row['object']), "1").'</code>';
            
   $trustedtags = '<svg><metadata><g><path><button><embed><iframe><p><br /><a><img><h1><h2><h3><h4><h5><h6><input><map><area><audio><video><pre><textarea><label><select><span><blink><i><fieldset><caption><form><legend><br><div><nav><main><aside><main><header><footer>';
		if(stristr($row['object'],'<table')) {
			$row['object'] = wp_ada_compliance_basic_close_unclosed_tables($row['object']);
			$trustedtags .= '<table><tr><td><th><tbody><thead>';
		}
		$report .= '<br /><code>'.strip_tags($row['object'],$trustedtags).'</code>';	
        
        if($row['examplecode'] != "") {
			$report .= '<br /><br /><div style="background-color: #fff;">'.strip_tags($row['examplecode'],$trustedtags) .'</div>';	
		}	    
            
		$report .= '</div>';
		}
		
		$report .=  '</td>';
		$report .=  '</tr>';
		}			
		}
		$report .=  '</table>';

		$report .= '
		<style>
		table{
		border-collapse: collapse;
		}
		table.ada_error td, table.ada_error th
		{
		padding: 5px; 
		color: #000; 
		min-width: 75px; 
		background-color:#ccc;
		}
		table.ada_error td
		{
		background-color:#fff;
		}
		</style>';
	}
	
	if(isset($issuefound))	return $report;
	else return "";
}

/*********************************************
Modify media library to add filter for images missing alternate text
**********************************************/
add_action('restrict_manage_posts', 'wp_ada_compliance_basic_media_library_dropdown');
add_action('pre_get_posts','wp_ada_compliance_basic_media_filter');

function wp_ada_compliance_basic_media_library_dropdown()
{
$scr = get_current_screen();
if ( $scr->base !== 'upload' ) return;
	
if (isset($_GET['accessibility-filter'])){
  $value = sanitize_text_field($_GET['accessibility-filter']);
} else $value = "";
	
echo '
	<label for="accessibility-filter" class="screen-reader-text">Filter by accessibility</label>
	<select class="accessibility-filter" name="accessibility-filter" id="accessibility-filter">
	<option value=""';
	if($value == "") echo ' selected';
	echo '>';
	_e('Accessibility Issues', 'wp-ada-compliance-basic');
	echo '</option>';
        	echo '<option value="viewall"';
	if($value == "viewall") echo ' selected';
	echo '>';
	_e('View All Images With Accessibility Issues', 'wp-ada-compliance');
	echo '</option>';
	echo '<option value="missingalt"';
	if($value == "missingalt") echo ' selected';
	echo '>';
	_e('Missing Alternate Text', 'wp-ada-compliancebasic');
	echo '</option>';
	echo '<option value="invalidalt"';
	if($value == "invalidalt") echo ' selected';
	echo '>';
	_e('Invalid Alternate Text', 'wp-ada-compliance-basic');
	echo '</option>';
	echo '</select>';
}
function wp_ada_compliance_basic_media_filter($query) {
    if ( is_admin() && $query->is_main_query() ) {
        if (isset($_GET['accessibility-filter'])){ 
			if($_GET['accessibility-filter'] == "missingalt") {
            $query->set('meta_query', array(
			'relation' => 'OR',
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '',
			'compare' => '='
		),
			array(
			'key' => '_wp_attachment_image_alt',
			'compare' => 'NOT EXISTS'
		)
			));
        }
	    if ($_GET['accessibility-filter'] == "invalidalt") {
            $query->set('meta_query', array(
			'relation' => 'OR',
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.jpg',
			'compare' => 'LIKE'
			),
                array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.jpeg',
			'compare' => 'LIKE'
			),
				array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.png',
			'compare' => 'LIKE'
			),
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.gif',
			'compare' => 'LIKE'
			),	
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '_',
			'compare' => 'LIKE'
			),	
				array(
			'key' => '_wp_attachment_image_alt',
			'value' => 'photo of',
			'compare' => 'LIKE'
			),
			array(
				'key' => '_wp_attachment_image_alt',
			'value' => 'image of',
			'compare' => 'LIKE'
		),
		array(
				'key' => '_wp_attachment_image_alt',
			'value' => 'graphic of',
			'compare' => 'LIKE'
		)
		)
		);
        }
		
        
     if($_GET['accessibility-filter'] == 'viewall') {
            $query->set('meta_query', array(
			'relation' => 'OR',
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '',
			'compare' => '='
		),
			array(
			'key' => '_wp_attachment_image_alt',
			'compare' => 'NOT EXISTS'
		),
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.jpg',
			'compare' => 'LIKE'
			),
                array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.jpeg',
			'compare' => 'LIKE'
			),
				array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.png',
			'compare' => 'LIKE'
			),
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '.gif',
			'compare' => 'LIKE'
			),	
			array(
			'key' => '_wp_attachment_image_alt',
			'value' => '_',
			'compare' => 'LIKE'
			),	
				array(
			'key' => '_wp_attachment_image_alt',
			'value' => 'photo of',
			'compare' => 'LIKE'
			),
			array(
				'key' => '_wp_attachment_image_alt',
			'value' => 'image of',
			'compare' => 'LIKE'
		),
		array(
				'key' => '_wp_attachment_image_alt',
			'value' => 'graphic of',
			'compare' => 'LIKE'
		)
		)
		);
		}

    }
    }
}

/***********************************************************************************
// remove autoplay attributes from audio and video embeds
**********************************************************************************/
function wp_ada_compliance_basic_filter_autoplay_av_tags($content, $run=0){
	$strip_autoplay = get_option('wp_ada_compliance_strip_autoplay','true');

	if($strip_autoplay == 'true' or $run == 1){
	
	$content= str_ireplace('autostart="true"', "", $content);	
	$content= str_ireplace("autostart='true'", "", $content);	
	$content= str_ireplace('&autoPlay=true', "", $content);
	$content= str_ireplace('&amp;autoPlay=true', "", $content);	
	$content = preg_replace('/<param(\s)*name=(\'|")+(autoplay)(\'|")+(\/)*(\s)*value=(\'|")+(true)(\'|")+(\s*\/*>)(<\/param>)*/i','', $content);
	$content= str_ireplace('autoPlay="autoplay"', "", $content);
		$content= str_ireplace('autoplay=""', "", $content);
		$content= str_ireplace("autoplay=''", "", $content);	
	$content= str_ireplace("autoPlay='autoplay'", "", $content);
		$content= str_ireplace('autoPlay="1"', "", $content);
	$content= str_ireplace("autoPlay='1'", "", $content);	
	$content = preg_replace('/(<audio+.+)((\s)((\S)+=("|\')(\w|\s|-|_)*("|\'))*(\s))*(autoplay)+(((\s)*((\S)+=("|\')(\w|\s|-|_)*("|\'))*(\s)*)*>)/i','$1$11', $content);
		$content = preg_replace('/(<video+.+)((\s)((\S)+=("|\')(\w|\s|-|_)*("|\'))*(\s))*(autoplay)+(((\s)*((\S)+=("|\')(\w|\s|-|_)*("|\'))*(\s)*)*>)/i','$1$11', $content);
	}
	
	return $content;
}
/*************************************************
closed unclosed tags to keep from breaking report
*************************************************/
function wp_ada_compliance_basic_close_unclosed_tables($object){ 
	$tabletags = substr_count($object,'<table');
	$tableclosetags = substr_count($object,'</table>');
	if($tabletags > $tableclosetags){
	$numbertoadd = ($tabletags - $tabletags);
		for($i = 1; $i <= $numbertoadd; $i++){
		$object.="</table>";	
		}
	}
	return $object;
}


?>