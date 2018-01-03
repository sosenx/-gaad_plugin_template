<?php 
namespace plugins_main_namespace;


class rest{

	public static function app_model( $data = NULL ){
		$r = array( 'plugin_name' => "plugins_main_namespace", 'handler' => "app_model", 'status' => 200 );
		return json_encode( $r );
	}

	public static function rest_test_callback( $data = NULL ){
		$r = array( 'plugin_name' => "plugins_main_namespace\\rest::rest_test_callback" );
		return json_encode( $r );
	}
	
}

?>
