<?php 
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;

/***************************************
add support for beaver builder editor
*****************************************/
add_action('wp_footer', function() {
    global $post;
    
if(!is_object($post)) return;    

// need to add check for beaver builder and return if not
if ( !class_exists( 'FLBuilderModel' ) or !FLBuilderModel::is_builder_active() ) {
    return;
  }
    
    	// include thick box
	add_thickbox();	
  ?>
<div id="BeaverBuilderADAError"class="adaError"><button type="button" aria-label="<?php _e('Dismiss error','wp-ada-compliance-basic'); ?>" class="adadismiss">X</button></div>
</div>

  <script>
  jQuery( function( $ ) {
	$(document).on("click", '.adadismiss', function() {
		  $('#BeaverBuilderADAError').hide();   
		 }); 
	  
	 $(document).on("click", '[data-action="publish"]', function(event) {
		
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
   if(data !== '' && data != '-1') {
      $('#BeaverBuilderADAError').html('<button type="button" aria-label="<?php _e('Dismiss error','wp-ada-compliance-basic'); ?>" class="adadismiss">X</button>'+data); 
         
              $('#BeaverBuilderADAError').show();
			   $('.fl-builder-publish-actions-click-away-mask').hide(); 
		   }else{
			$('#BeaverBuilderADAError').hide();   
		   }
		}
        
		});
    } );

  } );
</script>
 <?php
} );

/*****************************************************
// filter beaver builder content when checking for issues
*****************************************************/
function wp_ada_compliance_basic_check_beaver_builder_content($content,$postid, $type){

    if ( ! function_exists( 'is_plugin_active' ) )
     require_once( ABSPATH . '/wp-admin/includes/fl-builder.php' );    
    
if(!is_plugin_active('beaver-builder-lite-version/fl-builder.php')) return $content;   


$contentBB = "";
   
if ( class_exists( 'FLBuilder' ) and FLBuilderModel::is_builder_active()  ) {
       // Render and return the layout.
query_posts( array(
    'post_type' => $type,
    'p' => $postid,
) );

while (have_posts()) : the_post();
ob_start();     
the_content();
$contentBB = ob_get_clean();
endwhile;

wp_reset_query();

if($contentBB !='' and is_string($contentBB)) return $contentBB;
} 
return $content;
}

?>