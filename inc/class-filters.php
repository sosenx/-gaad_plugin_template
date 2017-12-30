<?php 
namespace plugins_main_namespace;


class filters {
  
  
  public static function woocommerce_cart_item_thumbnail( $image, $cart_item, $cart_item_key) {
    $path = '/home/klient.dhosting.pl/bsosnowski/wawakalendarze.dfirma.pl/public_html/wp-content/';
    $cuid = get_current_user_id();
    $json_file = $path . 'user_uploads/' . $cuid . '/' . $cart_item_key . '/'. $cart_item_key.'.json';
    if( is_file( $json_file) ){
      $json = file_get_contents( $json_file );
      $calendar = json_decode($json, true);
      if( !is_null( $calendar) ){
        if( $calendar['externalproject'] == 'true' ){
         // echo '<pre>'; echo var_dump( basename($calendar["externalprojectpath"]) ); echo '</pre>';
          $image = $image . '<section class="ext-project-data"><strong>Projekt klienta</strong>'." <span>nazwa pliku:" . basename($calendar["externalprojectpath"]) . '</span></section>';
          
        } else {
          $cal_header_bg = $calendar['imagesList']['header'];
          $cal_header_bg_dir = dirname($cal_header_bg);
          $cal_header_bg_file = basename($cal_header_bg);
          $cal_header_bg_thumb =  $cal_header_bg_dir.'/th80---'.$cal_header_bg_file;

          $cal_logo = $calendar['imagesList']['logo'];        
          $cal_logo_dir = dirname($cal_logo);
          $cal_logo_file = basename($cal_logo);
          $cal_logo_thumb =  $cal_logo_dir.'/th80---'.$cal_logo_file;

         /*
         rysowanie miniatur wybranych do kalendarza
         */
          $cal_header_bg_thumb_url = 'https://'.$_SERVER["HTTP_HOST"] .'/wp-content/'. explode('/wp-content/', $cal_header_bg_thumb)[1];
          $cal_header_bg_thumb_html = '<div class="cart-item-cal-h"><p>wybrane tło główki:</p><div class="img-h"><img class="cal_header_bg_thumb" src='.$cal_header_bg_thumb_url.'></div></div>';

          $cal_logo_thumb_url = 'https://'.$_SERVER["HTTP_HOST"] .'/wp-content/'. explode('/wp-content/', $cal_logo_thumb)[1];
          $cal_logo_thumb_html = '<div class="cart-item-cal-h"><p>wybrany logotyp:</p><img class="cal_logo_thumb" src="'.$cal_logo_thumb_url.'"></div>';

           $image = $image.'<div class="cart-item-cal-addons-h">'.$cal_header_bg_thumb_html.$cal_logo_thumb_html.'</div>';


        }
        
        
        
          
      }
    }
    
    
    return $image; 
  }
  
  public static function add_defer_attribute($tag, $handle) {
    // add script handles to the array below
    $scripts_to_defer = array( 
      'tether-js',
     //'vue-js',
      'bootstrap-js',
      'jquery-ui-js'
    );
    
    foreach($scripts_to_defer as $defer_script) {
      if ($defer_script === $handle) {
        return str_replace(' src', ' defer="defer" src', $tag);
      }
    }
    return $tag;
  }

  
  public static function register__() {
   
  }
  
  /*
  *  remove wp version param from any enqueued scripts
  */
  public static function remove_verion_suffix( $src ) {
      if ( strpos( $src, 'ver=' ) )
          $src = remove_query_arg( 'ver', $src );
      return $src;
  }
  
  
  
  public function _constructor(){
    
    return $this;
  }
  
  
}



?>