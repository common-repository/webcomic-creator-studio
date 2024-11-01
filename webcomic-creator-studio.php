<?php

/*

Plugin Name: Webcomic Creator Studio

Plugin URI: http://www.wordpress.org

Description: *UNSUPPORTED* Create and publish web comics from your wordpress site. Take advantage of shortcodes to display comics, navigation, and other features.

Version: 1.0.0.7

Author: Mike

Author URI: http://www.wordpress.org

Licence: GPL

*Any Modifications to this file must preserve this information*

*/


include_once dirname( __FILE__ ) . '/webcomic-creator-class.php';
$wccs = new Webcomic_Creator_Studio;


register_activation_hook( __FILE__, array( $wccs,'webcomic_activation' ) );
register_deactivation_hook( __FILE__, array( $wccs,'webcomic_deactivation' ) );
register_uninstall_hook( __FILE__, array( $wccs,'webcomic_uninstall' ) );

add_filter( 'cron_schedules', array( $wccs, 'webcomic_add_weekly_chron' ) ); 
add_filter( 'the_content', array( $wccs, 'webcomic_display_comic_img' ) );

add_action( 'admin_menu', array( $wccs, 'create_admin_menu' ) );
add_action( 'admin_init', array( $wccs, 'plugin_admin_init' ) );
add_action( 'admin_enqueue_scripts', array( $wccs, 'admin_assets' ) );
add_action( 'wp_enqueue_scripts', array( $wccs, 'public_assets' ) );
add_action( 'init', array( $wccs, 'create_webcomic_post_type' ) );

add_action( 'webcomic_register_update_schedule', array( $wccs, 'webcomic_register_update' ) );

add_action( 'wp_head', array( $wccs, 'add_og_image' ) );

add_shortcode( 'webcomic-preview', array( $wccs, 'webcomic_display_preview' ) );
add_shortcode( 'webcomic_preview', array( $wccs, 'webcomic_display_preview' ) ); //alternate spelling of shortcode because people get dashes and underscores mixed up
add_shortcode( 'webcomic-display-comic', array( $wccs, 'webcomic_display_latest_comic' ) );
add_shortcode( 'webcomic_display_comic', array( $wccs, 'webcomic_display_latest_comic' ) ); //alternate spelling of shortcode because people get dashes and underscores mixed up
add_shortcode( 'webcomic-display-comic-no-nav', array( $wccs, 'webcomic_display_latest_comic_no_nav' ) );
add_shortcode( 'webcomic_display_comic_no_nav', array( $wccs, 'webcomic_display_latest_comic_no_nav' ) ); //alternate spelling of shortcode because people get dashes and underscores mixed up


add_action( 'wp_ajax_' . 'webcomic_add_comic_to_inactive_queue', array($wccs, 'webcomic_add_comic_to_inactive_queue') );			//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_add_comic_to_inactive_queue', array($wccs, 'webcomic_add_comic_to_inactive_queue') );	//ajax

add_action( 'wp_ajax_' . 'webcomic_add_comic_to_active_queue', array($wccs, 'webcomic_add_comic_to_active_queue') );			//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_add_comic_to_active_queue', array($wccs, 'webcomic_add_comic_to_active_queue') );	//ajax

add_action( 'wp_ajax_' . 'webcomic_move_queue', array($wccs, 'webcomic_move_queue') );							//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_move_queue', array($wccs, 'webcomic_move_queue') );					//ajax

add_action( 'wp_ajax_' . 'webcomic_remove_comic_from_active_queue', array($wccs, 'webcomic_remove_comic_from_active_queue') );			//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_remove_comic_from_active_queue', array($wccs, 'webcomic_remove_comic_from_active_queue') );	//ajax


add_action( 'wp_ajax_' . 'webcomic_update_admin_tab', array($wccs, 'webcomic_tab_change') ); 					//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_update_admin_tab', array($wccs, 'webcomic_tab_change') ); 			//ajax

add_action( 'wp_ajax_' . 'webcomic_delete_bg_image', array($wccs, 'webcomic_delete_bg_image') );				//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_delete_bg_image', array($wccs, 'webcomic_delete_bg_image') );			//ajax

add_action( 'wp_ajax_' . 'webcomic_delete_char_image', array($wccs, 'webcomic_delete_char_image') );			//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_delete_char_image', array($wccs, 'webcomic_delete_char_image') );		//ajax

add_action( 'wp_ajax_' . 'webcomic_delete_character', array($wccs, 'webcomic_delete_character') );				//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_delete_character', array($wccs, 'webcomic_delete_character') );		//ajax


add_action( 'wp_ajax_' . 'webcomic_get_bg_title', array($wccs, 'webcomic_get_bg_title') );						//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_get_bg_title', array($wccs, 'webcomic_get_bg_title') );				//ajax

add_action( 'wp_ajax_' . 'webcomic_add_new_character', array($wccs, 'webcomic_add_new_character') );			//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_add_new_character', array($wccs, 'webcomic_add_new_character') );		//ajax

add_action( 'wp_ajax_' . 'webcomic_add_char_option', array($wccs, 'webcomic_add_char_option') );				//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_add_char_option', array($wccs, 'webcomic_add_char_option') );			//ajax

add_action( 'wp_ajax_' . 'webcomic_delete_queued_comic', array($wccs, 'webcomic_delete_queued_comic') );		//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_delete_queued_comic', array($wccs, 'webcomic_delete_queued_comic') );	//ajax

add_action( 'wp_ajax_' . 'webcomic_force_post_comic', array($wccs, 'webcomic_force_post_comic') );				//ajax
add_action( 'wp_ajax_nopriv_' . 'webcomic_force_post_comic', array($wccs, 'webcomic_force_post_comic') );		//ajax


add_action( 'add_attachment', array($wccs, 'process_attachment') );
add_action( 'edit_attachment', array($wccs, 'update_attachment') );
add_action( 'delete_attachment', array($wccs, 'remove_attachment') );
add_action( 'admin_enqueue_scripts', array($wccs, 'webcomic_admin_scripts_init') );



?>