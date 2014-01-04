<?php
/**
 * @package Disable All Updates
 * @author Websiteguy
 * @version 2.0.0
*/
/*
Plugin Name: Disable All Updates
Plugin URI: http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Version: 2.0.0
Description: A simple WordPress plugin that disables all the updating of plugins, themes, and the WordPress core. Just fill out the settings.
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

class Update_Notifications {

	
	private $status = array(); // Set $status in array
	private $checkboxes = array(); // Set $checkboxes in array
	
	function Update_Notifications() 
	{
		

		// Add translations
		if (function_exists('load_plugin_textdomain'))
			load_plugin_textdomain( 'update-notifications-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
		
		// Add menu page
		add_action('admin_menu', array(&$this, 'add_submenu'));
		
		// Settings API
		add_action('admin_init', array(&$this, 'register_setting'));
		
		
		// load the values recorded
		$this->load_update_notifications();
		
	}
	
	
	
	/* Register settings via the WP Settings API */
	function register_setting() 
	{
		register_setting('_update_notifications', '_update_notifications', array(&$this, 'validate_settings'));		
	}

	function validate_settings( $input ) {

		$options = get_option( '_update_notifications' );
		
		foreach ( $this->checkboxes as $id ) {
			if ( isset( $options[$id] ) && !isset( $input[$id] ) )
				unset( $options[$id] );
		}
		
		return $input;
	}

	function add_submenu() 
	{
		
		// Add submenu in menu "Settings"
		add_submenu_page( 'index.php', 'Disable All Updates', __('Disable All Updates','update-notifications-manager'), 'administrator', __FILE__, array(&$this, 'display_page') );
	}

	function load_update_notifications()
	{

		$this->status = get_option('_update_notifications');
		
		if( !$this->status ) return;
		
		foreach( $this->status as $id => $value ) {
		
			switch( $id ) {
				
				case 'plugin' :
					
// Disable plugin updates
// Disable Updates
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

// Update E-mails
		apply_filters( 'auto_plugin_update_send_email', false, $type, $plugin_update, $result );

// Remove Files
		function admin_init_1() {
		if ( !function_exists("remove_action") ) return;
			
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
		
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		wp_clear_scheduled_hook( 'wp_update_plugins' );
}
					
break;
				
				case 'theme' :
					
// Disable theme updates
// Disable Updates
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

// Update E-mails
		apply_filters( 'auto_theme_update_send_email', false, $type, $theme_update, $result );

// Remove Files
		function admin_init_2() {
		if ( !function_exists("remove_action") ) return;

		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );
		
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		wp_clear_scheduled_hook( 'wp_update_themes' );

}

					break;
				
				case 'core' :
					
// Disable WordPress core update
// Disable Updates
		remove_action( 'load-update-core.php', 'wp_update_core' );
		add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

// Update E-mails
		apply_filters( 'auto_core_update_send_email', false, $type, $core_update, $result );

// Remove Files
		function admin_init_3() {
		if ( !function_exists("remove_action") ) return;

		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		wp_clear_scheduled_hook( 'wp_version_check' );
		
		wp_clear_scheduled_hook( 'wp_version_check' );
		}
					
					break;

				case 'page' :
					
					// Remove Updates Page

		add_action('admin_menu', 'remove_menus', 102);
		function remove_menus() {
		global $submenu;
		remove_submenu_page ( 'index.php', 'update-core.php' );
		}
					
					break;

				case 'all' :
					
					// Remove Notifications

		add_action('admin_menu','hide_admin_notices');
		function hide_admin_notices() {
		remove_action( 'admin_notices', 'update_nag', 3 );
		}

					
					break;




}

}

}

	function display_page() 
	{ 
		
		// Check if user can access to the plugin
		if (!current_user_can('update_core'))
			wp_die( __('You do not have sufficient permissions to access this page.') );
		
		?>
		
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2><?php _e('Disable All Updates Settings','update-notifications-manager'); ?></h2>
			
			<form method="post" action="options.php">
				
			    <?php settings_fields('_update_notifications'); ?>
			    			    
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e('Disable Updates and Other Settings:', 'update-notifications-manager') ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e('Disable Updates and Other Settings:', 'update-notifications-manager') ?></span></legend>
								<label for="plugins_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['plugin'], true); ?> value="1" id="plugins_notify" name="_update_notifications[plugin]"> <?php _e('Disable Plugin Updates', 'update-notifications-manager') ?>
								</label>
								<br>
								<label for="themes_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['theme'], true); ?> value="1" id="themes_notify" name="_update_notifications[theme]"> <?php _e('Disable Theme Updates', 'update-notifications-manager') ?>
								</label>
								<br>
								<label for="core_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['core'], true); ?> value="1" id="core_notify" name="_update_notifications[core]"> <?php _e('Disable WordPress Core Update', 'update-notifications-manager') ?>
								</label>
								<br>
								<label for="page_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['page'], true); ?> value="1" id="page_notify" name="_update_notifications[page]"> <?php _e('Remove Updates Page (Under Dashboard)', 'update-notifications-manager') ?>
								</label>
								<br>
								<label for="all_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['all'], true); ?> value="1" id="all_notify" name="_update_notifications[all]"> <?php _e('Remove Notices (For All)', 'update-notifications-manager') ?>
								</label>
							</fieldset>
						</td>
					</tr>
				</table>
				
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				
			</form>
		</div>
			
	<?php
	}	
}

// Start this plugin once all other plugins are fully loaded
global $Update_Notifications; $Update_Notifications = new Update_Notifications();

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