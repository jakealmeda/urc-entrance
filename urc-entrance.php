<?php
/**
 * Plugin Name: URC Entrance
 * Description: Extends the MemberPress plugin and customize its registration form.
 * Version: 1.4.1
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
        'plans',
    );

}


// INCLUDE LEAKY'S FUNCTION
//include_once( 'lib/urc-leaky-regform.php' );
//include_once( 'urc-entrance-bar.php' );


$priority = 30;

// LOGGED OUT
//add_action( 'genesis_before_content', 'urc_mb_logged_out', $priority );
add_action( 'genesis_before_content', 'urc_mb_logged_out', $priority );
// LOGGED IN
add_action( 'genesis_before_content', 'urc_mb_logged_in', $priority );



// SIDEBAR | SHOW LOGGED IN DETAILS | LOGGED IN
function urc_mb_logged_in() {

    global $post;

    if( is_object( $post ) && !in_array( $post->post_name, hide_from_these_pages() ) ) :

        if( is_user_logged_in() ) :

            // opening tags
            ?><aside class="module loginfo"><?php

            global $current_user; wp_get_current_user();

            // set URL with WP NONCE
            $logout = wp_nonce_url( get_site_url().'/wp-login.php?action=logout' );

            echo    '<div class="widget">
                    <h3 class="widgettitle widget-title">Members Area</h3>
                    <div class="items text-sm"><a href="'.get_site_url().'/members-section">Members Section</a> | <a href="'.get_site_url().'/account">Account</a> | <a href="'.$logout.'">Logout</a></div>
                    <div class="text-xs margin-top">Username</div>
                    <div>'.$current_user->user_login.'</div>
                    <div class="text-xs margin-top">Info</div>
                    <div class="text-sm">'.$current_user->display_name.'</div>
                    <div class="text-sm"><a href="'.get_site_url().'/account?action=newpassword">Change Password</a></div>
                    </div>';
            // closing tags
            ?></aside><?php

        endif;

    endif;

}


// SIDEBAR | MAIN SUBSCRIBE FORM | LOGGED OUT
function urc_mb_logged_out() {

    global $post;

    if( is_object( $post ) && !in_array( $post->post_name, hide_from_these_pages() ) ) :

        if( !is_user_logged_in() ) {

            /*
                First Name (will be called Name)
                Email (will also be username)
                Password
            */
            //56841
            //57276 for TEST
            $forms = do_shortcode( '[mepr-membership-registration-form id="68387"]' );

            $outs = '<div class="pretitle"><span class="fontsize-xsml">For A</span> <span class="fontsize-sml">LIMITED TIME ONLY</span><br><span class="fontsize-xsml">Get </span> <span class="fontsize-sml">FREE</span> <span class="fontsize-xsml">Copies Of My</span></div>
                            <div class="photo"></div>
                            <div class="title"><span class="fontsize-med">Enter Your Name &amp; Email Below for Instant Access:</span></div>

                    '.$forms.'

                    <div class="margin-bottom" style="text-align:center;">Already A Member? <a href="'.get_site_url().'/login">Click Here</a> To Login</div>
            
                    <div class="disclaimer margin-bottom">Enter your name &amp; email and password in the boxes above to gain access to FREE Digital Online Versions of my popular eBooks &amp; audio course. When you subscribe, you will gain access to ALL articles behind the paywall. You will also be redirected to the members area of my website to read my eBooks, &amp; listen to the audio lessons right in your web browser! You’ll also get my best pickup, dating, relationship &amp; life success secrets &amp; strategies in my FREE newsletter. All information is 100% confidential. “Employ your time in improving yourself by other men’s writings, so that you shall gain easily what others have labored hard for.” ~ Socrates. “The man who doesn’t read good books has no advantage over the man who can’t read them.” ~ Mark Twain</div>
                    
                    '.urc_actual_gooads();

        }

        if( !empty( $outs ) )
            echo    '<aside class="module subscribe">
                        <div class="item-subscribe widget_text widget">'.$outs.'</div>
                    </aside>';
        
    endif;
}


function urc_actual_gooads() {

    global $post;

    $hide_in_pages = array(
        'free-ebook',
    );

    if( is_object( $post ) && !in_array( $post->post_name, $hide_in_pages ) ) :

        return '<div>
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-0947746501358966"
                         crossorigin="anonymous"></script>
                    <!-- Page & Post Article Body Resposive Ad -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-0947746501358966"
                         data-ad-slot="7597430493"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                    <script>
                         (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>';

    endif;
    
}
