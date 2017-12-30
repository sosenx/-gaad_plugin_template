<?php 
namespace plugins_main_namespace;


class hooks_mng{
  
  var $label;
  public $hooks; 
  
   
  public function __construct($label, $hooks = NULL ){
    $this->label = !is_string( $label ) ? 'default' : $label;
    $this->hooks = is_null( $hooks ) || !is_array( $hooks ) ? array() : $hooks;
    
    return $this;
  }
  
  public function apply_hooks(){
    $max = count( $this->hooks );
    for( $i = 0; $i<$max; $i++){
      
      switch( $this->hooks[$i]['type'] ){
          
        case 'action' :  
          add_action( $this->hooks[$i]['label'], $this->hooks[$i]['attr'][0],
                     isset( $this->hooks[$i]['attr'][1] ) ? $this->hooks[$i]['attr'][1] : 10,
                     isset( $this->hooks[$i]['attr'][2] ) ? $this->hooks[$i]['attr'][2] : 0
                    );
          break;
          
        case 'filter' :  
          add_filter( $this->hooks[$i]['label'], $this->hooks[$i]['attr'][0],
                     isset( $this->hooks[$i]['attr'][1] ) ? $this->hooks[$i]['attr'][1] : 10,
                     isset( $this->hooks[$i]['attr'][2] ) ? $this->hooks[$i]['attr'][2] : 0
                    );
          break;
          
          
      }
    }
    
    
  }
  
  public function add_hook( $hook_type, $hook_label, $attr ){
    $attr = is_string( $attr ) ? array( $attr ) : ( is_array( $attr ) ? $attr : false );
    $hook_label = ( is_string( $hook_label ) && strlen( $hook_label ) > 0 ) || is_array( $hook_label ) ? $hook_label : false;
    if( !$attr || !$hook_label ){
      return $attr;
    } else {
      
      if( is_array( $hook_label ) ){
        
        $max = count ( $hook_label );
        for( $i=0; $i<$max; $i++ ){
            $this->hooks[] = array(
              'label' => $hook_label[ $i ], 
              'type' => $hook_type,         
              'attr' => $attr, 
            );

          $GLOBALS['wcm-hooks'][ $this->label[ $i ] ] = $this->hooks;
        }
        
      } else {
         $this->hooks[] = array(
          'label' => $hook_label, 
          'type' => $hook_type,         
          'attr' => $attr, 
        );

      $GLOBALS['wcm-hooks'][ $this->label ] = $this->hooks;
    }
      
     
    }
    
    
    
  }
  
}



?>