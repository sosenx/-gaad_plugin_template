<?php 
namespace kamadmin;




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'kamadmin/v1', '/model', array(
	    'methods' => 'GET',
	    'callback' => 'kamadmin\rest::app_model',
	  ) );
	} );




    add_action( 'rest_api_init', function () {
        register_rest_route( 'kamadmin/v1', '/test', array(
            'methods' => 'GET',
            'callback' => 'kamadmin\rest::rest_test_callback',
        ) );
    } );



    add_action( 'rest_api_init', function () {
        register_rest_route( 'kamadmin/v1', '/kl', array(
            'methods' => 'GET',
            'callback' => 'kamadmin\rest::kamera_login'
        ) );
    } );

    add_action( 'rest_api_init', function () {
        register_rest_route( 'kamadmin/v1', '/klo', array(
            'methods' => 'GET',
            'callback' => 'kamadmin\rest::kamera_lout'
        ) );
    } );


    add_action( 'rest_api_init', function () {
        register_rest_route( 'kamadmin/v1', '/kamera', array(
            'methods' => 'GET',
            'callback' => 'kamadmin\rest::kamera',
        ) );
    } );



 ?>