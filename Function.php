<?php
/**
 * @package Disable All Updates
 * @author Websiteguy
 * @version 1.9.0
*/
/*
Plugin Name: Disable All Updates
Plugin URI: http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Version: 1.9.0
Description: A simple WordPress plugin that disables all the updating of plugins, themes, and the WordPress core. Their is no setup for this plugin.
Author: kidsguide
Author URL: http://profiles.wordpress.org/kidsguide/
Compatible with WordPress 2.3+.
*/
/*
Copyright 2013 Websiteguy (email : mpsparrow@cogeco.ca)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Disable All Updates

// Disable Plugin Updates

		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

// Disable Theme Updates

		remove_action( 'load-update-core.php', 'wp_update_themes' );
		add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

// Disable Core Updates

		remove_action( 'load-update-core.php', 'wp_update_core' );
		add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

// Remove Update Submenu Under Dashboard. The same code in our new plugin "Remove the Updates Submenu".

		add_action('admin_menu', 'remove_menus', 102);
		function remove_menus() {
		global $submenu;
		remove_submenu_page ( 'index.php', 'update-core.php' );
		}

// Hide Update Notices in Admin Dashboard

		add_action('admin_menu','hide_admin_notices');
		function hide_admin_notices() {
		remove_action( 'admin_notices', 'update_nag', 3 );
		}

// Turns off Automatic Updates in WordPress

		define( 'Automatic_Updater_Disabled', true );
                                    define('WP_AUTO_UPDATE_CORE', false);

// Removes Update E-mails (Only works with some plugins)

// Core E-mails

		apply_filters( 'auto_core_update_send_email', false, $type, $core_update, $result );

// Plugin E-mails

		apply_filters( 'auto_plugin_update_send_email', false, $type, $plugin_update, $result );

// Theme E-mails

		apply_filters( 'auto_theme_update_send_email', false, $type, $theme_update, $result );

// Remove Files From WordPress

		function admin_init() {
		if ( !function_exists("remove_action") ) return;

// Disable Plugin Updates

		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );	

// Disable Theme Updates

		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );

// Disable Core Updates

		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		wp_clear_scheduled_hook( 'wp_version_check' );
		
		wp_clear_scheduled_hook( 'wp_version_check' );
		}

// Remove Updates Agian (just in case)

		add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

		add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
		add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );

		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );

		add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		add_filter( 'pre_transient_update_themes', create_function( '$a', "return null;" ) );

		remove_action( 'load-update-core.php', 'wp_update_themes' );
		add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

		add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );") );
	
		add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );"), 2 );
		add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );"), 2 );
		add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );

		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		add_filter( 'pre_transient_update_plugins', create_function( '$a', "return null;" ) );

		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

// Admin Notices

		function thsp_admin_notices() {	
		global $current_user;	
		$userid = $current_user->ID;	
		global $pagenow;
		if ( !get_user_meta( $userid, 'ignore_sample_error_notice' ) ) {

// Text for Admin Notice

		echo '			
		<div class="updated">			
		<p>Thanks for using Disable All Updates version 1.9.0. 			
		<br /> 			
		<strong>Status:</strong> 
		<marquee direction="right" width="270px" style="border:GREY 1px Dotted">Working...</marquee>			
		<br /> 				
		<a href="?dismiss_me=yes">Hide Notice</a>
		</p>			
		</div>';	
		}
		}
		add_action( 'admin_notices', 'thsp_admin_notices' );

// Action for Hide Notice Text

		function thsp_dismiss_admin_notice() {
		global $current_user;
		$userid = $current_user->ID;	
		if ( isset($_GET['example_nag_ignore']) && '3' == $_GET['example_nag_ignore'] ) { 
		add_user_meta($user_id, 'example_ignore_notice', 'true', true); 
		}
	                  }
		add_action( 'admin_init', 'thsp_dismiss_admin_notice' );

// Plugin Page Links

		add_filter( 'plugin_row_meta', 'thsp_plugin_meta_links', 10, 2 );

		function thsp_plugin_meta_links( $links, $file ) {	
		$plugin = plugin_basename(__FILE__);	

// Create links
	
		if ( $file == $plugin ) {		
		return array_merge(			
		$links,			
		array( '<a href="http://www.wordpress.org/support/plugin/stops-core-theme-and-plugin-updates">Support</a>' ),
		array( '<a href="http://www.wordpress.org/plugins/stops-core-theme-and-plugin-updates/faq/">FAQ</a>' ),
		array( '<a href="http://www.youtube.com/watch?v=ZSJf9nwP7oA">Tutorial</a>' )
		);	
		}	
		return $links;
		}