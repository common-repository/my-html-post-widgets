<?php
/*
Plugin Name: Below Post Title and Below Post Content Ads
Plugin URI: https://www.allwebtuts.com/below-post-title-and-below-post-content/
Description: Simple Plugin for insert your HTML Widgets and Ads on the Below Post Title and Below Post content.
Version: 1.9
Author: Santhosh veer
Author URI: http://www.santhoshveer.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


//not necessary to define, but helps!
if (!defined('WP__MYHL_BOX')){ //define plugin name
    define('WP__MYHL_BOX', trim(dirname(plugin_basename(__FILE__)), '/'));
}
if (!defined('WP__MYHL_BOX_PLUGIN_DIR')){ //define plugin dir
    define('WP__MYHL_BOX_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WP__MYHL_BOX);
}
if (!defined('WP__MYHL_BOX_VERSION_NUM')){ //define plugin version
    define('WP__MYHL_BOX_VERSION_NUM', '1.8');
}

//Plugin CSS File!
function my_html_posts_style() 
{
    wp_register_style("my-html-posts-style-file", plugin_dir_url(__FILE__) . "style.css");
    wp_enqueue_style("my-html-posts-style-file");
}

add_action("wp_enqueue_scripts", "my_html_posts_style");

################## WordPress Admin Page ###############

add_action('admin_menu', 'wp_myhl_box_create_page_menu'); //add a menu in admin settings
function wp_myhl_box_create_page_menu() {
	if (function_exists('add_options_page')) 
	{
		//add_options_page('Page Title', 'Menu Title', capability, 'Menu slug', 'Create Page Function');
		add_options_page('Below Post Title and Below Post Content Ads', 'Post Widget Settings', 'manage_options','my-html-post-Widgets', 'wp_myhl_box_settings_page');
	}
}

//Create widget settings page in admin
function wp_myhl_box_settings_page() 
{
	echo '<div id="post-body" class="metabox-holder columns-2">
             <div id="post-body-content">
             <div class="postbox">
             <div class="inside"><form method="post" action="options.php" >';
		settings_fields( 'wp-myhl-box-option-group' );
		do_settings_sections( 'wp-myhl-box' );
		submit_button();
	echo '</form></div></div></div></div>';
}


//create widget fields for admin page
add_action( 'admin_init', 'wp_myhl_box_register_filds' );

function wp_myhl_box_register_filds() {
	
	//register settings for our admin pages
	register_setting( 'wp-myhl-box-option-group', 'wp_myhl_box_code_top', 'wp_myhl_box_input_sanitize');
	register_setting( 'wp-myhl-box-option-group', 'wp_myhl_box_code_bottom', 'wp_myhl_box_input_sanitize');
	
	//add fields group section
	add_settings_section( 'section-one', __('Post Widget Settings'), '', 'wp-myhl-box' );
	//add a field to admin pages
	add_settings_field( 'wp_myhl_box_code_top', __('Below Post Title'), 'wp_myhl_box_code_field_top', 'wp-myhl-box', 'section-one' );
	add_settings_field( 'wp_myhl_box_code_bottom', __('Below Post content'), 'wp_myhl_box_code_field_bottom', 'wp-myhl-box', 'section-one' );
}

function wp_myhl_box_code_field_top() { //text field
	$wp_myhl_box_code_top = esc_attr( get_option( 'wp_myhl_box_code_top' ) );
	echo '<textarea name="wp_myhl_box_code_top" class="widefat" rows="8" style="font-family:Courier New;">'.$wp_myhl_box_code_top.'</textarea>';
}
function wp_myhl_box_code_field_bottom() { //text field
	$wp_myhl_box_code_bottom = esc_attr( get_option( 'wp_myhl_box_code_bottom' ) );
	echo '<textarea name="wp_myhl_box_code_bottom" class="widefat" rows="8" style="font-family:Courier New;">'.$wp_myhl_box_code_bottom.'</textarea>';
}

//sanitize field function
function wp_myhl_box_input_sanitize( $input ) {
    return htmlentities($input);
}

################## WordPress Front Pages ###############

add_filter( 'the_content', 'wp_myhl_box_content' ); //Add a filter to 'the_content' to hook ad_content() function

function wp_myhl_box_content($content)
{
    if (is_single()) //only apply to single posts, not homepage or category
    {
        $wp_myhl_box_code_top = html_entity_decode( get_option( 'wp_myhl_box_code_top' ) ); //get Widget code
		$wp_myhl_box_code_bottom = html_entity_decode( get_option( 'wp_myhl_box_code_bottom' ) ); //get widget code
		
        $content =$wp_myhl_box_code_top . $content . $wp_myhl_box_code_bottom; //Attach code before and after content 
        return $content; // return content with widget Code
 
    }else{
        //for homepage, category or other pages, just return original content
        return $content;
    }
}


/* plugin menu link*/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'belowabove_optnpge_links' );

function belowabove_optnpge_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'options-general.php?page=my-html-post-Widgets' ) . '">Plugin Settings</a>',
 );
return array_merge( $links, $mylinks );
}
