<?php

/**

 * @package Disable All Updates
 * @author Websiteguy

 * @version 2.8
*/

/*

Plugin Name: Disable All Updates

Plugin URI: http://websiteguyplugins.wordpress.com/
Version: 2.8
Description: A Simple Wordpress Plugin That Deletes All Updating of Plugins, Themes, and Even The Wordpress Core.

Author: <a href="http://profiles.wordpress.org/kidsguide">Websiteguy</a>
Author URL: http://profiles.wordpress.org/kidsguide
Donate URI: https://www.websiteguyplugins.wordpress.com
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


add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );



# 2.3 to 2.7:

add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );

add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );



# 2.8 to 3.0:
remove_action( 'wp_version_check', 'wp_version_check' );

remove_action( 'admin_init', '_maybe_update_core' );

add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );



# 3.0:

add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );



# 2.8 to 3.0:

remove_action( 'load-themes.php', 'wp_update_themes' );

remove_action( 'load-update.php', 'wp_update_themes' );

remove_action( 'admin_init', '_maybe_update_themes' );

remove_action( 'wp_update_themes', 'wp_update_themes' );

add_filter( 'pre_transient_update_themes', create_function( '$a', "return null;" ) );



# 3.0:

remove_action( 'load-update-core.php', 'wp_update_themes' );

add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );



# 2.3 to 2.7:

add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );") );
	

# Why use the admin_menu hook? It's the only one available between the above hook being added and being applied
add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );"), 2 );

add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );"), 2 );

add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );



# 2.8 to 3.0:

remove_action( 'load-plugins.php', 'wp_update_plugins' );

remove_action( 'load-update.php', 'wp_update_plugins' );

remove_action( 'admin_init', '_maybe_update_plugins' );

remove_action( 'wp_update_plugins', 'wp_update_plugins' );

add_filter( 'pre_transient_update_plugins', create_function( '$a', "return null;" ) );



# 3.0:

remove_action( 'load-update-core.php', 'wp_update_plugins' );

add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
