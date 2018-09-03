<?php 
namespace apii;




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'apii/v1', '/model', array(
	    'methods' => 'GET',
	    'callback' => 'apii\rest::app_model',
	  ) );
	} );




	add_action( 'rest_api_init', function () {
	  register_rest_route( 'apii/v1', '/test', array(
	    'methods' => 'GET',
	    'callback' => 'apii\rest::rest_test_callback',
	  ) );
	} );

add_action( 'rest_api_init', function () {
	  register_rest_route( 'apii/v1', '/option', array(
	    'methods' => 'POST',
	    'callback' => 'apii\rest::option',
	  ) );
	} );

add_action( 'rest_api_init', function () {
	  register_rest_route( 'apii/v1', '/products-download', array(
	    'methods' => 'POST',
	    'callback' => '\apii\productsDownloader::download',
	  ) );
	} );

add_action( 'rest_api_init', function () {
	  register_rest_route( 'apii/v1', '/products-add-info', array(
	    'methods' => 'POST',
	    'callback' => '\apii\productsDownloader::products_part_file_info',
	  ) );
	} );

    add_action( 'rest_api_init', function () {
        register_rest_route( 'apii/v1', '/products-add-part', array(
            'methods' => 'POST',
            'callback' => 'apii\productsDownloader::add_part_file',
        ) );
    } );

    add_action( 'rest_api_init', function () {
        register_rest_route( 'apii/v1', '/download-image', array(
            'methods' => 'POST',
            'callback' => 'apii\productsDownloader::download_image',
        ) );
    } );


    add_action( 'rest_api_init', function () {
        register_rest_route( 'apii/v1', '/images-add-info', array(
            'methods' => 'POST',
            'callback' => '\apii\productsDownloader::images_part_file_info',
        ) );
    } );


 ?>