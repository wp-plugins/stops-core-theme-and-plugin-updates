<?php
/**
 * @package Disable All Updates
 * @author Websiteguy
 * @version 1.0
*/
/*
Plugin Name: Disable All Updates
Plugin URI: http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Version: 1.0
Description: A Simple Wordpress Plugin That Deletes All Updating of Plugins, Themes, and Even The Wordpress Core.
Author: <a href="http://profiles.wordpress.org/kidsguide">Websiteguy</a>
Author URL: http://profiles.wordpress.org/kidsguide
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

// Disable Plugin Updates
remove_action( 'load-update-core.php', 'wp_update_plugins' );
add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

// Disable Theme Updates
remove_action( 'load-update-core.php', 'wp_update_themes' );
add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

// Disable Core Updates
remove_action( 'load-update-core.php', 'wp_update_core' );
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

// Remove Updates Menu
add_action('admin_menu', 'remove_menus', 102);
function remove_menus() {
	global $submenu;
	remove_submenu_page ( 'index.php', 'update-core.php' );
}

// Hides Update Notices in Dashboard
add_action('admin_menu','hide_admin_notices');
function hide_admin_notices() {
    remove_action( 'admin_notices', 'update_nag', 3 );
}

// Turn Off Automatic Updates
define( 'Automatic_Updater_Disabled', true );

// Remove Files From WordPress
	function admin_init() {
		if ( !function_exists("remove_action") ) return;

		/*
		 * Disable Plugin Updates
		 */
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );	

		/*
		 * Disable Theme Updates
		 */
		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		/*
		 * Disable Core Updates
		 */
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		wp_clear_scheduled_hook( 'wp_version_check' );
		
		wp_clear_scheduled_hook( 'wp_version_check' );
	}