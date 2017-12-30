<?php 
namespace plugins_main_namespace;


class shortcodes {

	public static function kredytslider( $atts, $content = "" ) {
		$defaults = array( 
			'id' => uniqid( 'gslider_' ),
			'kwotamin' => 100,
			'kwotamax' => -1,
			'kwotaskok' => 100,
			'dnimin' => 7,
			'dnimax' => -1,
			'dniskok' => 7, 
			'podsumowaniesufix' => "PLN",
			'dzialanie' => "a / b",
			'brakpodsumowania' => false,
			'podsumowanieprefix' => '',
			'podsumowaniesufix' => 'PLN',
			'podsumowanieikona' => 'fa-list-alt',
			'dniikona' => 'fa-calendar',
			'kwotaikona' => 'fa-money',
			'dnitytul' => 'Termin spłaty',
			'dniwartoscsufix' => 'dni',

			'kwotatytul' => 'Kwota pożyczki',
			'kwotawartoscsufix' => 'PLN',
			'podsumowanietytul' => 'Wysokość raty',
			
			'poprawdnimax' => false,
			'poprawdnimaxwgore' => false,
			'popawkwotamax' => false,
			'popawkwotamaxwgore' => false
		);
		$defaults['dnimax'] = $defaults['dnimin'] * $defaults['dniskok'];
		$defaults['kwotamax'] = $defaults['kwotamin'] * $defaults['kwotaskok'];

		$attributes = is_array( $atts ) ? array_merge( $defaults,$atts ) : $defaults;		
		$attributes['brakpodsumowania'] = filter_var( $attributes['brakpodsumowania'], FILTER_VALIDATE_BOOLEAN);
		$attributes['poprawdnimax'] = filter_var( $attributes['poprawdnimax'], FILTER_VALIDATE_BOOLEAN);
		$attributes['poprawdnimaxwgore'] = filter_var( $attributes['poprawdnimaxwgore'], FILTER_VALIDATE_BOOLEAN);
		$attributes['popawkwotamax'] = filter_var( $attributes['popawkwotamax'], FILTER_VALIDATE_BOOLEAN);
		$attributes['popawkwotamaxwgore'] = filter_var( $attributes['popawkwotamaxwgore'], FILTER_VALIDATE_BOOLEAN);

		$attributes['podsumowanieikona'] = '<i class="fa '. $attributes['podsumowanieikona'] .'" aria-hidden="true"></i>';
		$attributes['dniikona'] = '<i class="fa '. $attributes['dniikona'] .'" aria-hidden="true"></i>';		
		$attributes['kwotaikona'] = '<i class="fa '. $attributes['kwotaikona'] .'" aria-hidden="true"></i>';

		$attributes['poprawdnimax'] = !$attributes['poprawdnimax'] ? $attributes['poprawdnimaxwgore'] : $attributes['poprawdnimax'];
		$attributes['popawkwotamax'] = !$attributes['popawkwotamax'] ? $attributes['popawkwotamaxwgore'] : $attributes['popawkwotamax'];

		if ( $attributes['poprawdnimax'] ) {
			$attributes['dnimax'] = (int)($attributes['dnimax'] / $attributes['dniskok']) * $attributes['dniskok'] 
				+( $attributes['poprawdnimaxwgore'] ? $attributes['dniskok'] : 0 );
		}
		
		if ( $attributes['popawkwotamax'] ) {
			$attributes['kwotamax'] = (int)($attributes['kwotamax'] / $attributes['kwotaskok']) * $attributes['kwotaskok']
			+ ( $attributes['popawkwotamaxwgore'] ? $attributes['kwotaskok'] : 0 );
		}

		$attributes_json = json_encode( $attributes );

		$amount_text = $attributes['kwotatytul'];
		$amount_days = $attributes['dnitytul'];
		$summary_title = $attributes['podsumowanietytul'];

		$summary_html_title = '<section>'.$attributes['podsumowanieikona'].'<p>'. $summary_title .'</p></section>';
		$summary_html_prefix = '<span>'.$attributes['podsumowanieprefix'].'</span>';
		$summary_html_sufix = '<span>'.$attributes['podsumowaniesufix'].'</span>';

		$amount_slider_attr = array(
			'range' => "max",
		    'min' => (int)$attributes['kwotamin'],
		  	'max' => (int)$attributes['kwotamax'],		    
		    'value' => (int)$attributes['kwotamin'],
		    'step' => (int)$attributes['kwotaskok']
		);

		$days_slider_attr = array(
			'range' => "max",
		    'min' => (int)$attributes['dnimin'],
		    'max' => (int)$attributes['dnimax'],		    
		    'value' => (int)$attributes['dnimin'],
		    'step' => (int)$attributes['dniskok']
		);

		$output = array();
		$output[] = '<script type="text/javascript">';
		$output[] = 'var model_' . $attributes['id'] . ' = ' . $attributes_json . ';' ;
		$output[] = '</script>';		

		$output[] = '<div class="gks-root" id="'. $attributes['id'] .'">';
			$output[] = '<div class="text-bar">';
				$output[] = '<section>';					
					$output[] = $attributes['kwotaikona'];
					$output[] = '<p>'. $amount_text .'</p>';
				$output[] = '</section>';

				$output[] = '<section class="gks-amount-val">';					
					$output[] = '<p>'. (int)$attributes['kwotamin'] .'</p>';
					$output[] = '<span>'. $attributes['kwotawartoscsufix'] .'</span>';
				$output[] = '</section>';	

			$output[] = '</div>';

			$output[] = '<div class="gks-amount"></div>';
			$output[] = '<div class="gks-slider-caption">';
				$output[] = '<ul>';
					$output[] = '<li>';
						$output[] = $attributes['kwotamin'];
					$output[] = '</li>';
					$output[] = '<li>';
						$output[] = $attributes['kwotamax'];
					$output[] = '</li>';
				$output[] = '</ul>';
			$output[] = '</div>';

			$output[] = '<div class="text-bar">';
				$output[] = '<section>';					
					$output[] = $attributes['dniikona'];					
					$output[] = '<p>'. $amount_days .'</p>';
				$output[] = '</section>';

				$output[] = '<section class="gks-days-val">';					
					$output[] = '<p>'. (int)$attributes['dnimin'] .'</p>';
					$output[] = '<span>'. $attributes['dniwartoscsufix'] .'</span>';
				$output[] = '</section>';	

			$output[] = '</div>';
			
			$output[] = '<div class="gks-days"></div>';
			$output[] = '<div class="gks-slider-caption">';
				$output[] = '<ul>';
					$output[] = '<li>';
						$output[] = $attributes['dnimin'];
					$output[] = '</li>';
					$output[] = '<li>';
						$output[] = $attributes['dnimax'];
					$output[] = '</li>';
				$output[] = '</ul>';
			$output[] = '</div>';

			if ( !$attributes['brakpodsumowania'] ) {
				$output[] = '<div class="gks-summary">';
					$output[] = '<div class="gks-summary-val text-bar"></div>';				
				$output[] = '</div>';
			}
		$output[] = '</div>';

		$output[] = '<script type="text/javascript" id="script-'. $attributes['id'] .'">';
			$output[] = '(function ( $, w ) {';

				$output[] = 'function setSummary( ui ) {';


					$output[] = 'var gks = $( ui.handle ).parents(\'[id]\').eq(0)';
					$output[] = 'var amount_val = parseInt( $( "#' . $attributes['id'] .' .gks-amount-val p" ).html() );';
					$output[] = 'var days_val = parseInt($( "#' . $attributes['id'] .' .gks-days-val p" ).html() );';
					$output[] = 'var summary_html = Array( \''. $summary_html_title .'\', \'<section>'. $summary_html_prefix .'\', \'<span>\'+calc( amount_val, days_val )+\'</span>\', \''. $summary_html_sufix .'</section>\' );';			
					$output[] = 'var gks_summary = $( gks ).find(\'.gks-summary-val\').html( summary_html.join(\'\') )';				
				$output[] = '}';

				$output[] = 'function calc( a, b ) {';					
					$output[] = 'var model = model_' . $attributes['id'] .';';
					$output[] = 'var calculated = Math.round( eval( model.dzialanie ) * 100) / 100;';
					$output[] = 'var calculated_parts = calculated.toString().split(".");';
					$output[] = 'if( typeof calculated_parts !== "undefined"){';
						$output[] = 'calculated_parts[1] = typeof calculated_parts[1] !== \'undefined\' && calculated_parts[1] !== "" ? calculated_parts[1] : "00"';
						$output[] = 'calculated_parts[1] += calculated_parts[1].length == 1 ? "0" : "";';
						
						$output[] = 'return calculated_parts.join(",")';

					$output[] = '}';

					
					$output[] = 'return  Math.round( eval( model.dzialanie ) * 100) / 100;';
				$output[] = '}';



    			$output[] = '$( document ).ready( function(){';					
					$output[] = 'var amount_ = $( "#' . $attributes['id'] .' .gks-amount" );';
					$output[] = 'var days_ = $( "#' . $attributes['id'] .' .gks-days" );';
					
					$output[] = 'var amount_slider_attr = '. json_encode( $amount_slider_attr ) .';';
					$output[] = 'amount_slider_attr.slide = function( event, ui ) { ';
						$output[] = '$( "#' . $attributes['id'] .' .gks-amount-val p" ).html( ui.value );';
						if ( !$attributes['brakpodsumowania'] ) {
							$output[] = 'setSummary( ui );';
						}
						
					$output[] = '}';


					$output[] = 'var days_slider_attr = '. json_encode( $days_slider_attr ) .';';
					$output[] = 'days_slider_attr.slide = function( event, ui ) { $( "#' . $attributes['id'] .' .gks-days-val p" ).html( ui.value );      ';
					if ( !$attributes['brakpodsumowania'] ) {
						$output[] = 'setSummary( ui );';
					}
					$output[] = '}';
					$output[] = 'days_slider_attr.create = function( event, ui ) { ';
						
					if ( !$attributes['brakpodsumowania'] ) {
						$output[] = 'setSummary( { handle: event.target  } );';
					}

					$output[] = '}';
					$output[] = 'amount_.slider( amount_slider_attr );';
					$output[] = 'days_.slider( days_slider_attr ) ;';

    			$output[] = '} );';
			$output[] = '})( jQuery, window );' ;
		$output[] = '</script>';

		$r = implode( "\n", $output );
		return $r;
	}
}


?>