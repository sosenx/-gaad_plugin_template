<?php 
namespace kamadmin;


class rest{

	public static function app_model( $data = NULL ){
		$r = array( 'plugin_name' => "kamadmin", 'handler' => "app_model", 'status' => 200 );
		return json_encode( $r );
	}

	public static function rest_test_callback( $data = NULL ){
		$r = array( 'plugin_name' => "kamadmin\\rest::rest_test_callback" );
		return json_encode( $r );
	}
	
}

?>
