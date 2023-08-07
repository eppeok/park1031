<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

/***************************************
add support for Elementor Editor
*****************************************/
add_action('wp_footer', function() {
    global $post;
    
if(!is_object($post)) return;    
    
    // ignore posts not being checked
$post_object = get_post( $post->ID );
if(!is_object($post_object))  return;
$post_types = get_option('wp_ada_compliance_basic_posttypes',array('page','post'));    
if (!is_array($post_types) or !in_array($post->post_type, $post_types)) return;
    
if (!did_action( 'elementor/loaded' ) or !\Elementor\Plugin::$instance->preview->is_preview_mode() ) {
    return;
}
    
    	// include thick box
	add_thickbox();	
  ?>
<div id="ElementorADAError" class="adaError"><button type="button" aria-label="<?php _e('Dismiss error','wp-ada-compliance-basic'); ?>" class="adadismiss">X</button></div>
<button type="button" class="wp-ada-elementor-check"><i class="fas fa-universal-access" aria-hidden="true"></i> <?php _e('Check for Issues','wp-ada-compliance-basic'); ?></button>
  <script>
  jQuery( function( $ ) {
     $(document).on("click", '.adadismiss', function() {
       $('#ElementorADAError').hide();
    }); 
      
        // display message on save 
    //THIS IS DEPRECATED AND NEEDS TO BE REPLACED
if (typeof elementor !== 'undefined') {  
elementor.saver.on( "after:save",  function(){
wp_ada_compliance_basic_display_error_message();
});
}
   // display message when button clicked                      
$(document).on('click','.wp-ada-elementor-check', function() { 
    wp_ada_compliance_basic_display_error_message();
});
});
    
// display error report message  
function wp_ada_compliance_basic_display_error_message(resturl){
jQuery( function( $ ) {    
var seperator='&';
var nonce = '<?php echo wp_create_nonce( 'wp_rest' );?>';
var resturl = '<?php echo esc_url_raw(get_rest_url()); ?>';
if(resturl.search('/wp-json/')>0) seperator='?';			

url = resturl+'wp_ada_compliance_basic/v1/displaynotice/<?php echo $post->ID; ?>'+seperator+'_wpnonce='+nonce;
    
$.ajax({
url: url,

error: function(jqXHR, textStatus, errorThrown) {
  console.log(textStatus, errorThrown);
},
success: 
function(data){
if(data === '' || data == '-1') {
data = '<?php _e('No web accessibility issues were found!','wp-ada-compliance_basic');?>';    

}
 $('#ElementorADAError').html('<button type="button" aria-label="<?php _e('Dismiss error','wp-ada-compliance_basic'); ?>" class="adadismiss">X</button>'+data); 

$('#ElementorADAError').show();       
 
}

});
    });
}
      
</script>
 <?php
} );

/*****************************************************
// filter elementor content when checking for issues
*****************************************************/
function wp_ada_compliance_basic_check_elementor_content($content,$postid){
if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    
if(!is_plugin_active('elementor/elementor.php')) return $content;   
  
$contentElementor = "";

if (class_exists("\\Elementor\\Plugin")) {
    $pluginElementor = \Elementor\Plugin::instance();
    $contentElementor = $pluginElementor->frontend->get_builder_content($postid);
     if($contentElementor !='' and is_string($contentElementor)) return $contentElementor;
}
return $content;
}
?>