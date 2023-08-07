<?php
// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) die;
/********************************************************************/	
// validate animated images
/********************************************************************/
function wp_ada_compliance_basic_validate_animated_image($content, $postinfo){
	
global $wp_ada_compliance_basic_def;
	
$dom = str_get_html($content);	

// get options
$wp_ada_compliance_basic_scanoptions = get_option('wp_ada_compliance_basic_ignore_scan_rules',array());
	
// check if being scanned
if(in_array('animated_image', $wp_ada_compliance_basic_scanoptions)) return 1;		

$images = $dom->find('img');
foreach ($images as $image) {
      if (isset($image) and (stristr($image->getAttribute('src'),".gif") or stristr($image->getAttribute('src'),".png"))){

	if(wp_ada_compliance_basic_isAnimatedGif($image->getAttribute('src'))){			
			
				
			$imagecode = $image->outertext;
		 
			// save error
			if(!$insertid = wp_ada_compliance_basic_error_check($postinfo,"animated_image", $imagecode))
			$insertid = wp_ada_compliance_basic_insert_error($postinfo,"animated_image", $wp_ada_compliance_basic_def['animated_image']['StoredError'], $imagecode);
			

			}
		}
}
	return 1;
}
/***************************************************
check for animated gif
**************************************************/
function wp_ada_compliance_basic_isAnimatedGif($filename)
  {
    $filecontents=wp_ada_compliance_basic_get_content($filename);
   if($filecontents == "") return 0;
	   
    // animated png
        if(strpos(substr( $filecontents, 0, strpos( $filecontents, 'IDAT')), 
                 'acTL')!==false)
            {
        return true;
        }
    
    $str_loc=0;
    $count=0;
 
    // There is no point in continuing after we find a 2nd frame
    while ($count < 2) 
    {
   
        
        // animated gif
      $where1=strpos($filecontents,"\x00\x21\xF9\x04", $str_loc);
      if ($where1 === FALSE) {
        break;
      }
 
      $str_loc = $where1+1;
      $where2  = strpos($filecontents,"\x00\x2C",$str_loc);
      if ($where2 === FALSE) {
        break;
      }
      else {
        if ($where1+8 == $where2) {
          $count++;
        }
        $str_loc = $where2+1;
      }
    }
 
    // gif is animated when it has two or more frames
    return ($count >= 2); 
  }
/**************************************************
get file content
**************************************************/
function wp_ada_compliance_basic_get_content($url){
	
$args = array('timeout'     => 25,'redirection' => 25,'sslverify'   => false,);
$content = wp_remote_retrieve_body(wp_remote_get(esc_url_raw($url), $args));

return $content;
}

?>