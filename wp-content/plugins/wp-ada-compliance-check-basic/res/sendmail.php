<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/*****************************************************
// cron function to send notifications of new issues
****************************************************/
function wp_ada_compliance_basic_send_email_notifications() {
  	$notification_email = get_option('wp_ada_compliance_basic_notification_email','');
	
	$emailaddresses = explode(",",$notification_email);
	foreach($emailaddresses as $key => $email){
	if($email !=  '') {
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$blogname = get_bloginfo('name');
	$subject = sprintf(__('%s', 'wp-ada-compliance-basic'), esc_html($blogname));
	$subject .= __(' Web Accessibility Report', 'wp-ada-compliance-basic');
	
	$body = wp_ada_compliance_basic_create_email_report($email);
	if($body != '')
	wp_mail($email, $subject, $body, $headers );
	}
	}
}
/********************************************
// send email report
**********************************************/
function wp_ada_compliance_basic_send_report(){
    
   $error = wp_ada_compliance_basic_form_values();
    
	if(isset($_GET['view'])) {
		$postinfo['view'] = (int)$_GET['view'];
	} else $postinfo['view'] = 1;
	if(isset($_GET['sort'])) {
		$postinfo['sort'] = (int)$_GET['sort'];
	} else $postinfo['sort'] = 1;
	if(isset($_GET['errorid'])) {
		$postinfo['errorid'] = (int)$_GET['errorid'];
	} else $postinfo['errorid'] = "";
	if(isset($_GET['error'])) {
		$postinfo['error'] = sanitize_text_field($_GET['error']);
	} else $postinfo['error'] = "";
	if(isset($_GET['type'])) {
		$postinfo['type'] = sanitize_text_field($_GET[ 'type' ]);
	} else $postinfo['type'] = "";
	if(isset($_GET['searchtitle'])) {
	$postinfo['searchtitle'] = sanitize_text_field($_GET[ 'searchtitle' ]);
	} else $postinfo['searchtitle'] = "";
	
		
if(isset($_POST['email']) and $error ==''){
	$postinfo['email'] = sanitize_text_field($_POST['email']);
	$postinfo['view'] = (int)$_POST['view'];
	$postinfo['sort'] = (int)$_POST['sort'];
	$postinfo['errorid'] = (int)$_POST['errorid'];
	$postinfo['error'] = sanitize_text_field($_POST[ 'error' ]);
	$postinfo['type'] = sanitize_text_field($_POST[ 'type' ]);
    $postinfo['comments'] = sanitize_text_field($_POST[ 'comments' ]);	
	$postinfo['searchtitle'] = sanitize_text_field($_POST['searchtitle']);
	
$headers = array('Content-Type: text/html; charset=UTF-8');
	$blogname = get_bloginfo('name');
	$subject = sprintf(__('%s', 'wp-ada-compliance-basic'), esc_html($blogname));
	$subject .= __(' Web Accessibility Report', 'wp-ada-compliance-basic');
	
	$body = wp_ada_compliance_basic_create_email_report($postinfo['email'],$postinfo);
	if($body != ''){
	$body = '<p>'.$postinfo['comments'].'</p>'.$body;	
	wp_mail($postinfo['email'], $subject, $body, $headers );
	echo '<p class="adaAllGood">';
		_e('Report sent!', 'wp-ada-compliance-basic');
	echo '</p>';	
	}else{
	echo '<p class="adaRedText">';
		_e('No records to send!', 'wp-ada-compliance-basic');
	echo '</p>';		
	}
}elseif($error != '') echo '<p class="notice notice-error">'.esc_attr($error).'</p>';
echo "<style>#wpadminbar, #adminmenuwrap, #adminmenuback{display:none;} body{margin-left:15px; margin-top: -40px;}</style>";
echo '<form action="" method="post" class="wp-ada-send-report">
	<p><label for="email">'.__('Email:','wp-ada-compliance-basic').' <input type="text" id="email" name="email"></label>
	<input type="submit"></p>
<p><label for="comments">'.__('Comments:','wp-ada-compliance-basic').'<br /><textarea id="comments" name="comments" cols="45" rows="5"></textarea></label></p>
<input type="hidden" name="view" value="'.esc_attr($postinfo['view']).'" />
<input type="hidden" name="error" value="'.esc_attr($postinfo['error']).'" />
<input type="hidden" name="errorid" value="'.esc_attr($postinfo['errorid']).'" />
	<input type="hidden" name="type" value="'.esc_attr($postinfo['type']).'" />
	<input type="hidden" name="sort" value="'.esc_attr($postinfo['sort']).'" />
	<input type="hidden" name="searchtitle" value="'.esc_attr($postinfo['searchtitle']).'" />
	</form>';
}

/********************************************
// print report
**********************************************/
function wp_ada_compliance_basic_print_report(){
     wp_ada_compliance_basic_form_values();
	if(isset($_GET['view'])) {
		$postinfo['view'] = (int)$_GET['view'];
	} else $postinfo['view'] = 1;
	if(isset($_GET['sort'])) {
		$postinfo['sort'] = (int)$_GET['sort'];
	} else $postinfo['sort'] = 1;
	if(isset($_GET['errorid'])) {
		$postinfo['errorid'] = (int)$_GET['errorid'];
	} else $postinfo['errorid'] = "";
	if(isset($_GET['error'])) {
		$postinfo['error'] = sanitize_text_field($_GET['error']);
	} else $postinfo['error'] = "";
	if(isset($_GET['type'])) {
		$postinfo['type'] = sanitize_text_field($_GET[ 'type' ]);
	} else $postinfo['type'] = "";
	if(isset($_GET['search'])) {
	$postinfo['searchtitle'] = sanitize_text_field($_GET[ 'search' ]);
	} 
	elseif(isset($_GET['searchtitle'])) {
		$postinfo['searchtitle'] = sanitize_text_field($_GET[ 'searchtitle' ]);
		} 
	else $postinfo['searchtitle'] = "";
	if(isset($_GET['excludedups'])) {
		$postinfo['excludedups'] = (int)$_GET['excludedups'];
	} else $postinfo['excludedups'] = 0;
	if(isset($_GET['excludethemes'])) {
		$postinfo['excludethemes'] = (int)$_GET['excludethemes'];
	} else $postinfo['excludethemes'] = 0;
	
$headers = array('Content-Type: text/html; charset=UTF-8');
	$blogname = get_bloginfo('name');
	$subject = sprintf(__('%s', 'wp-ada-compliance-basic'), esc_html($blogname));
	$subject .= __(' Web Accessibility Report', 'wp-ada-compliance-basic');
	
	$body = wp_ada_compliance_basic_create_email_report('print',$postinfo);
	if($body != ''){
		echo '<div class="wp-ada-print_results"><h2>'.$subject.'</h2>'.$body.'</div>';
		echo __('- End of Report -', 'wp-ada-compliance-basic');
echo '<script>window.print();</script>';
	}

}
?>