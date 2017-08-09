<?php
/* 
 * Plugin Name:   5 Vote Free Photos
 * Version:       1.1.0
 * Plugin URI:    http://www.dimbal.com/
 * Description:   Integrate a premade collection of photos in your blog.  Allow users to share and interact with the photos that they like.  A great way to add fresh content for free.
 * Author:        5 Vote
 * Author URI:    http://www.5vote.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit();	// sanity check

//error_reporting(E_ALL);
//ini_set('display_errors','On');


//Register the activation function
register_activation_hook(__FILE__, 'fivevote_activate');

//init process for Button Control
add_action('init', 'fivevote_add_interface');


// FUNCTIONS ///////////////////////////

/*
 * Function that is run when the plugin is activated
 */
function fivevote_activate(){
	//Nothing for now
}
	
/*
 * Adds all of the appropriate stuff to WordPress
 */
function fivevote_add_interface(){
	//Would be nice to let the user choose which tool bar level to have it display in (future release)
	
	//Taking this out because it is broken currently
	add_filter("mce_external_plugins", "fivevote_add_tinymce_plugin");
	add_filter("mce_buttons", "fivevote_register_tinymce_button");
}

/*
 * Adds an HTML button to the editor so that a user can select it while editing a post
 */
function fivevote_register_tinymce_button($buttons){
	array_push($buttons, "fivevote");
	return $buttons;
}

/*
 * Adds an js callback hook for when the button is pressed by the user
 */
function fivevote_add_tinymce_plugin($plugin_array) {
	$plugin_array['fivevote'] = plugin_dir_url(__file__).'editor_plugin.js';
   	return $plugin_array;
}

/*
 * This will be the function that translates the shortcode into something useful.  
 * In this case it will be a placeholder DIV and proper element information so that the JS can render it via AJAX
 * RETURN the HTML to be put in place of the shortcode
 */
function fivevote_shortcode_handler($atts){
	//Enqueu the render script which will interpret the content
	wp_enqueue_script('fivevoterender','http://www.5vote.com/wpp/v1/render.js');
	extract( shortcode_atts( array(
		'slug' => 'default-slug',
		'title' => '5 Vote Photos',
		'hide_sharing' => 'default',
		'hide_link' => 'default',
		'max_display_count' => 0
		), $atts ) );
	
	$rand = rand(0,10000);
    $title = str_replace("-", " ", $title);
    $title = ucfirst($title);  
    //At this point I now have the variables from the shortcode... now what do I want to do with them?? Build the HTML?
    $html = '<div id="fivevote_embed_container_'.$rand.'" class="fivevote_embed_container" fivevote-slug="'.$slug.'" fivevote-hidesharing="'.$hide_sharing.'" fivevote-hidelink="'.$hide_link.'" fivevote-maxdisplaycount="'.$max_display_count.'"><a href="http://www.5vote.com/photos/'.$slug.'">'.$title.'</a></div>';
    return $html;   
    
}

/* 
 * Enque scripts and make any other needed calls
 */

//wp_enqueue_script('fivevoteslugs','http://www.5vote.com/wpp/v1/slugs.js');
add_shortcode( 'fivevote', 'fivevote_shortcode_handler' );

//ADMIN MENU PAGE
add_action( 'admin_menu', 'fivevote_plugin_menu' );
function fivevote_plugin_menu() {
	add_options_page( '5 Vote Settings and Options', '5 Vote Photos', 'manage_options', 'fivevote-plugin-options', 'fivevote_plugin_options' );
}
function fivevote_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	?>
	<div style="float:right; margin: 10px 10px 0 0"><a href="http://www.dimbal.com"><img src="http://www.dimbal.com/images/logo_300.png" alt="Dimbal Software" /></a></div>
	<h1>5 Vote Photos</h1>
	<p style="font-style:italic; font-size:larger;">Easily add pre-made collections of photos to your website in 3 simple steps.</p>
	<hr />
	<div style="display:table; width:100%;">
		<div style="display:table-cell; width:auto;">
			<!-- LEFT SIDE CONTENT -->
			<h4>Usage Instructions</h4>
			<p>This plugin comes ready to use right out of the box.  Below are some tips to help you include a fivevote collection in your posts.</p>
			<p>1. On your Post Editor find the fivevote button and click.  An Editor Popup will Appear.</p>
			<p><img src="http://www.5vote.com/images/wpp-fivevote-mcebar.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p><img src="http://www.5vote.com/images/wpp-fivevote-popup.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>2. Select the list you want to use, then check any optional settings for that list.  Click the "Insert" button to include the shortcode for the fivevote list in your post.</p>
			<p><img src="http://www.5vote.com/images/wpp-fivevote-shortcode.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>3. Once your post is saved your content will render on the fly.</p>
			<p><img src="http://www.5vote.com/images/wpp-fivevote-finalresults.png" style="vertical-align:middle; margin:10px; border:1px solid black;" /></p>
			<p>That's it!  That sure was easy - wasn't it.</p>
		</div>
		<div style="display:table-cell; width:300px; padding-left:20px;">
			<!-- RIGHT SIDE CONTENT -->
			<h4>Did you like this Plugin?  Please help it grow.</h4>
			<div style="text-align:center;"><a href="http://wordpress.org/support/view/plugin-reviews/five-vote">Rate this Plugin on Wordpress</a></div>
			<br />
			<div style="text-align:center;">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="5GMXFKZ79EJFA">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
			<hr />
			<h4>Follow us for Free Giveaways and more...</h4>
			<div id="fb-root"></div>
			<script type="text/javascript">
			  // Additional JS functions here
			  window.fbAsyncInit = function() {
			    FB.init({
			      appId      : '539348092746687', // App ID
			      //channelUrl : '//<?=(URL_ROOT)?>channel.html', // Channel File
			      status     : true, // check login status
			      cookie     : true, // enable cookies to allow the server to access the session
			      xfbml      : true,  // parse XFBML
			      frictionlessRequests: true,  //Enable Frictionless requests
			    });
			  };

			  // Load the SDK Asynchronously
			  (function(d){
			     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			     if (d.getElementById(id)) {return;}
			     js = d.createElement('script'); js.id = id; js.async = true;
			     js.src = "//connect.facebook.net/en_US/all.js";
			     ref.parentNode.insertBefore(js, ref);
			   }(document));
			</script>
			<div style="text-align:center;"><div class="fb-like" data-href="https://www.facebook.com/dimbalsoftware" data-send="false" data-layout="standard" data-show-faces="false" data-width="200"></div></div>
			<hr />
			<h4>Questions?  Support?  Record a Bug?</h4>
			<p>Need help with this plugin? Visit...</p>
			<p><a href="http://www.dimbal.com/support">http://www.dimbal.com/support</a></p>
			<hr />
			<h4>Other great Dimbal Products</h4>
			<div class="dbmWidgetWrapper" dbmZone="18"></div>
			<div class="dbmWidgetWrapper" dbmZone="19"></div>
			<div class="dbmWidgetWrapper" dbmZone="20"></div>
			<a href="http://www.dimbal.com">Powered by the Dimbal Banner Manager</a>
		</div>
	</div>
	<?
	wp_enqueue_script('dbmScript','http://www.dimbal.com/dbm/banner/dbm.js', false);
}