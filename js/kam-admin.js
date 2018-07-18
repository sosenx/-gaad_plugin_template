(function( $, w, d){
    showModalWindow = function( data ){

        jQuery( JSON.parse( data ).html ).prependTo( jQuery('body') );
        //jQuery('.kam-login').addClass( 'disabled' );
    }

    showCameraStream = function( e ){
        e.preventDefault();
        var restUrl = jQuery(e.currentTarget).data().rest;
        jQuery.ajax( { url: restUrl } ).then( showModalWindow );
    }

    closeCameraStream = function( e ){
        var target = jQuery( e.currentTarget ).parent();
        var data = JSON.parse ( atob( target.data( 'params' ) ) );
            jQuery.ajax( {
                url : data.url
            } ).then( function( data ){
                window.location.reload();
            } );

        target.remove();
    }

    $( d ).ready( function(){

        $( d ).on( 'click', '.close-modal', closeCameraStream );
        $('.kam-login').on( 'click', showCameraStream );


    } );


})( jQuery, window, document)