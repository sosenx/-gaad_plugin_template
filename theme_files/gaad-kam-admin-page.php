<?php 
/* Template Name: Gaad Kam Admin  */ 


wp_head();

global $post;

echo do_shortcode( $post->post_content);


wp_footer();



?>



