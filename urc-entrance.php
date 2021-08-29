<?php
/**
 * Plugin Name: URC Entrance
 * Description: Extends the Leaky Paywall plugin and use a custom registration form.
 * Version: 1.0
 * Author: Jake Almeda
 * Author URI: http://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// LIST OF PAGES WHERE NOT TO SHOW DETAILS
function hide_from_these_pages() {

    return array(
        'profile',
        'login',
        'register',
    );

}


// INCLUDE LEAKY'S FUNCTION
include_once( 'lib/urc-leaky-regform.php' );
//include_once( 'urc-entrance-bar.php' );


$priority = 30;

// LOGGED OUT
add_action( 'genesis_before_content', 'urc_leaky_logged_out', $priority );
// LOGGED IN
add_action( 'genesis_before_content', 'urc_leaky_logged_in', $priority );



// SIDEBAR | SHOW LOGGED IN DETAILS | LOGGED IN
function urc_leaky_logged_in() {

    global $post;

    if( !in_array( $post->post_name, hide_from_these_pages() ) ) :

        if( is_user_logged_in() ) :

            // opening tags
            ?><aside class="module loginfo"><?php

            global $current_user; wp_get_current_user();

            // set URL with WP NONCE
            $logout = wp_nonce_url( get_site_url().'/wp-login.php?action=logout' );

            echo    '<div class="widget">
                    <h3 class="widgettitle widget-title">Members Area</h3>
                    <div class="items text-sm"><a href="'.get_site_url().'/profile">Edit Profile</a> | <a href="'.$logout.'">Logout</a></div>
                    <div class="text-xs margin-top">Username</div>
                    <div>'.$current_user->user_login.'</div>
                    <div class="text-xs margin-top">Info</div>
                    <div class="text-sm">'.$current_user->display_name.'</div>
                    <div class="text-sm"><a href="'.get_site_url().'/profile">Change Password</a></div>
                    </div>';

            // closing tags
            ?></aside><?php

        endif;

    endif;

}


// SIDEBAR | MAIN SUBSCRIBE FORM | LOGGED OUT
function urc_leaky_logged_out() {

    /**
     * Custom codes from here...
     */

    if( !is_user_logged_in() ) {

        /*global $post;

        if( !in_array( $post->post_name, hide_from_these_pages() ) ) :

            $outs = do_shortcode( '[leaky_paywall_profile]' );

        endif;

    } else {*/

        $forms = do_shortcode( '[urc_leaky_paywall_register_form level_id=0]' );

        $outs = '<div class="pretitle"><span class="fontsize-xsml">For A</span> <span class="fontsize-sml">LIMITED TIME ONLY</span><br><span class="fontsize-xsml">Get </span> <span class="fontsize-sml">FREE</span> <span class="fontsize-xsml">Copies Of My</span></div>
                        <div class="photo"></div>
                        <div class="title"><span class="fontsize-med">Enter Your Name &amp; Email Below for Instant Access:</span></div>

                '.$forms.'

                <div class="margin-bottom" style="text-align:center;">Already A Member? <a href="'.get_site_url().'/login">Click Here</a> To Login</div>
        
                <div class="disclaimer">Enter your name &amp; email and password in the boxes above to gain access to FREE Digital Online Versions of my popular eBooks &amp; audio course. When you subscribe, you will gain access to ALL articles behind the paywall. You will also be redirected to the members area of my website to read my eBooks, &amp; listen to the audio lessons right in your web browser! You’ll also get my best pickup, dating, relationship &amp; life success secrets &amp; strategies in my FREE newsletter. All information is 100% confidential. “Employ your time in improving yourself by other men’s writings, so that you shall gain easily what others have labored hard for.” ~ Socrates. “The man who doesn’t read good books has no advantage over the man who can’t read them.” ~ Mark Twain</div>
                ';

    }

    if( !empty( $outs ) )
        echo    '<aside class="module subscribe">
                    <div class="item-subscribe widget_text widget">'.$outs.'</div>
                </aside>';

}




