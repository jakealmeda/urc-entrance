<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


//add_action( 'genesis_before_content', 'urc_leaky_validation_bar' );
//add_action( 'genesis_before_loop', 'urc_leaky_validation_bar' );
function urc_leaky_validation_bar() {

	global $post;

	if( !in_array( $post->post_name, hide_from_these_pages() ) ) :

		?><div><?php

		if( is_user_logged_in() ) :

			echo '<div><a href="'.$logout.'">Logout</a> | <a href="'.get_site_url().'/profile">Edit Profile</a></div>';

		else :

			echo '<a href="'.get_site_url().'/login">Login</a> | <a href="'.get_site_url().'/wp-login.php?action=lostpassword">Forgot Password</a> | <a href="'.get_site_url().'/register?level_id=0">Register</a>';

		endif;

		?></div><?php

	endif;

}