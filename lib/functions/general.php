<?php
/**
 * Functions
 *
 * This file contains general functions
 *
 * @author    Project Name
 * @copyright 2019 Project Name
 * @version   1.0
 */

define( 'THEME_DIRECTORY', get_template_directory() );

/*
Body classes
- add more classes to the body to enable more specific targeting if needed
*/
function ambrosite_body_class($classes) {$post_name_prefix = 'postname-';$page_name_prefix = 'pagename-';$single_term_prefix = 'single-';$single_parent_prefix = 'parent-';$category_parent_prefix = 'parent-category-';$term_parent_prefix = 'parent-term-';$site_prefix = 'site-';global $wp_query;if ( is_single() ) {$wp_query->post = $wp_query->posts[0];setup_postdata($wp_query->post);$classes[] = $post_name_prefix . $wp_query->post->post_name;$taxonomies = array_filter( get_post_taxonomies($wp_query->post->ID), "is_taxonomy_hierarchical" );foreach ( $taxonomies as $taxonomy ) {$tax_name = ( $taxonomy != 'category') ? $taxonomy . '-' : '';$terms = get_the_terms($wp_query->post->ID, $taxonomy);if ( $terms ) {foreach( $terms as $term ) {if ( !empty($term->slug ) )$classes[] = $single_term_prefix . $tax_name . sanitize_html_class($term->slug, $term->term_id);while ( $term->parent ) {$term = &get_term( (int) $term->parent, $taxonomy );if ( !empty( $term->slug ) )$classes[] = $single_parent_prefix . $tax_name . sanitize_html_class($term->slug, $term->term_id);}}}}} elseif ( is_archive() ) {if ( is_category() ) {$cat = $wp_query->get_queried_object();while ( $cat->parent ) {$cat = &get_category( (int) $cat->parent);if ( !empty( $cat->slug ) )$classes[] = $category_parent_prefix . sanitize_html_class($cat->slug, $cat->cat_ID);}} elseif ( is_tax() ) {$term = $wp_query->get_queried_object();while ( $term->parent ) {$term = &get_term( (int) $term->parent, $term->taxonomy );if ( !empty( $term->slug ) )$classes[] = $term_parent_prefix . sanitize_html_class($term->slug, $term->term_id);}}} elseif ( is_page() ) {$wp_query->post = $wp_query->posts[0];setup_postdata($wp_query->post);$classes[] = $page_name_prefix . $wp_query->post->post_name;}if ( is_multisite() ) {global $blog_id;$classes[] = $site_prefix . $blog_id;}return $classes;} add_filter('body_class', 'ambrosite_body_class');

/*
Disable the theme editor
- stop clients from breaking their website
*/
define('DISALLOW_FILE_EDIT', true);

/*
Remove version info
- makes it that little bit harder for hackers
*/
function complete_version_removal() {
    return '';
}
add_filter('the_generator', 'complete_version_removal');

/*
Remove info from headers
- remove the stuff we don't need
*/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7 );
remove_action('wp_print_styles', 'print_emoji_styles' );

/*
Excerpt
- this theme supports excerpts
*/
add_post_type_support( 'page', 'excerpt' );

function new_excerpt_more($more) {
     global $post;
	 return '...';
}

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'...';
  } else {
	$excerpt = implode(" ",$excerpt);
  } 
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

/*
Thumbnails
- this theme supports thumbnails
*/
add_theme_support( 'post-thumbnails' );
add_image_size ( 'full', 3000, 3000, true );

/*
Scripts & Styles
- include the scripts and stylesheets
*/
add_action( 'wp_enqueue_scripts', 'script_enqueues' );

add_filter('gform_init_scripts_footer', function() {
	return true;
});

function script_enqueues() {
			
	if ( wp_script_is( 'jquery', 'registered' ) ) {
		
		wp_deregister_script( 'jquery' );
		
	}
	wp_enqueue_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.1/jquery.min.js', array(), '2.2.1', false );
	wp_enqueue_script( 'custom', get_template_directory_uri() . '/dist/js/all.min.js', array(), null, false );
	wp_enqueue_style( 'style', get_template_directory_uri() . '/dist/css/style.min.css', false, null, 'all' );
	// wp_enqueue_script( 'maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAbAJ6I-QRrOcqR0V_87NgYnHLMdqn6Kuk', array(), null, true);
	
}

/*
Maps
*/ 

// function my_acf_init() {
  
// 	acf_update_setting('google_api_key', 'AIzaSyDlii-Zod8Zzb2Wonyyzuc2HSgK4OArY08');
//   }
  
//   add_action('acf/init', 'my_acf_init');

/*
Admin Bar
- hide the admin bar
*/
add_filter('show_admin_bar', '__return_false');

/*
Menus
- enable WordPress Menus
*/
if (function_exists('register_nav_menus')){register_nav_menus(array('primary' => __( 'Header Navigation' )));}

/*
Menu Classes
- add first and last to menu items
*/
function wpdev_first_and_last_menu_class($items) {
	$items[1]->classes[] = 'first';
	$items[count($items)]->classes[] = 'last';
	return $items;
}
add_filter('wp_nav_menu_objects', 'wpdev_first_and_last_menu_class');

/*
Admin Menus
- hide unused menu items
*/
function remove_menus(){
  
  remove_menu_page( 'edit-comments.php' );
  
}
add_action( 'admin_menu', 'remove_menus' );


// Change avatar

// add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
// function wpb_new_gravatar ($avatar_defaults) {
// $myavatar = 'http://example.com/wp-content/uploads/2017/01/wpb-default-gravatar.png';
// $avatar_defaults[$myavatar] = "Default Gravatar";
// return $avatar_defaults;
// }

function no_wordpress_errors(){
	return 'Something is wrong!';
  }
  add_filter( 'login_errors', 'no_wordpress_errors' );


  remove_action('welcome_panel', 'wp_welcome_panel');


  function my_myme_types($mime_types){
    $mime_types['svg'] = 'image/svg+xml'; //Adding svg extension
    $mime_types['psd'] = 'image/vnd.adobe.photoshop'; //Adding photoshop files
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);

?>