<?php
/**
 * @package Disable Updates Manager
 * @author Websiteguy
 * @version 2.4.0
*/
/*
Plugin Name: Disable Updates Manager
Plugin URI: http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Version: 2.4.0
Description: Now you can chose which type of update you won't to disable! Just go to the settings page under dashboard. 
Author: Websiteguy
Author URI: http://profiles.wordpress.org/kidsguide/
Compatible with WordPress 2.3+.
*/
/*
Copyright 2014 Websiteguy (email : mpsparrow@cogeco.ca)

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

    class Disable_Updates {
	    // Set status in array
	    private $status = array(); 
		
		// Set checkboxes in array
	    private $checkboxes = array(); 
	
	function Disable_Updates() {
		
    // Coming Soon: Add translations
	if (function_exists('load_plugin_textdomain'))
		load_plugin_textdomain( 'disable-updates-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
		
	// Add menu page
	        add_action('admin_menu', array(&$this, 'add_submenu'));
		
	// Settings API
		    add_action('admin_init', array(&$this, 'register_setting'));
		
		
	// load the values recorded
		    $this->load_disable_updates();
		}

	// Register Settings
	function register_setting()	{
	    register_setting('_disable_updates', '_disable_updates', array(&$this, 'validate_settings'));		
	    }

	function validate_settings( $input ) {
		$options = get_option( '_disable_updates' );
		
		foreach ( $this->checkboxes as $id ) {
			if ( isset( $options[$id] ) && !isset( $input[$id] ) )
				unset( $options[$id] );
		}
		
		return $input;
	    }

	function add_submenu() {
	// Add submenu in menu "Dashboard"
		add_submenu_page( 'index.php', 'Disable Updates', __('Disable Updates','disable-updates-manager'), 'administrator', __FILE__, array(&$this, 'display_page') );
		}

	function load_disable_updates() {
		$this->status = get_option('_disable_updates');
		
		if( !$this->status ) return;

		foreach( $this->status as $id => $value ) {

		switch( $id ) {

	// Disable Plugin Updates	
			case 'plugin' :
					
    // Disable Plugin Updates Code
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

    // Disable Plugin Update E-mails (only works for some plugins)
		apply_filters( 'auto_plugin_update_send_email', false, $type, $plugin_update, $result );
			
			break;
	
	// Disable Theme Updates
			case 'theme' :

    // Disable Theme Updates Code
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

    // Disable Theme Update E-mails (only works for some plugins)
		apply_filters( 'auto_theme_update_send_email', false, $type, $theme_update, $result );

			break;
				
	// Disable WordPress Core Updates			
			case 'core' :
	
    // Disable WordPress Core Updates Code
		remove_action( 'load-update-core.php', 'wp_update_core' );
		add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

    // Disable WordPress Core Update E-mails (only works for some plugins)
		apply_filters( 'auto_core_update_send_email', false, $type, $core_update, $result );
					
			break;

	// Remove the Dashboard Updates Menu		
			case 'page' :
			
	// Remove the Dashboard Updates Menu Code		
		add_action('admin_menu', 'remove_menus', 102);
		
		function remove_menus() {
			global $submenu;
		remove_submenu_page ( 'index.php', 'update-core.php' );
		}
					
			break;

    // Remove Update Files
			case 'files' :

	// Remove Plugin Files
		function admin_init_plugin() {
			if ( !function_exists("remove_action") ) return;
			
			remove_action( 'load-plugins.php', 'wp_update_plugins' );
			remove_action( 'load-update.php', 'wp_update_plugins' );
			remove_action( 'admin_init', '_maybe_update_plugins' );
			remove_action( 'wp_update_plugins', 'wp_update_plugins' );
			wp_clear_scheduled_hook( 'wp_update_plugins' );
		
			remove_action( 'load-update-core.php', 'wp_update_plugins' );
			wp_clear_scheduled_hook( 'wp_update_plugins' );
		}			
		
	// Remove Theme Files
		function admin_init_theme() {
			if ( !function_exists("remove_action") ) return;

			remove_action( 'load-themes.php', 'wp_update_themes' );
			remove_action( 'load-update.php', 'wp_update_themes' );
			remove_action( 'admin_init', '_maybe_update_themes' );
			remove_action( 'wp_update_themes', 'wp_update_themes' );
			wp_clear_scheduled_hook( 'wp_update_themes' );
		
			remove_action( 'load-update-core.php', 'wp_update_themes' );
			wp_clear_scheduled_hook( 'wp_update_themes' );
		}
		
    // Remove WordPress Core Files
		function admin_init_core() {
			if ( !function_exists("remove_action") ) return;

			remove_action( 'wp_version_check', 'wp_version_check' );
			remove_action( 'admin_init', '_maybe_update_core' );
			wp_clear_scheduled_hook( 'wp_version_check' );
		
			wp_clear_scheduled_hook( 'wp_version_check' );
		}
		
			break;

		}
	}
}

    // Settings Page (Under Dashboard)
	    function display_page() { 
		
	// Check if user can access to the plugin
		if (!current_user_can('update_core'))
			wp_die( __('You do not have sufficient permissions to access this page.') );
		
		?>
		
		<div class="wrap">
			<h2><?php _e('Disable All Updates Settings','disable-updates-manager'); ?></h2>
			
			<form method="post" action="options.php">
				
			    <?php settings_fields('_disable_updates'); ?>
			    			    
				<table class="form-table">

					<tr>
					<th scope="row"><?php _e('Disable Updates:', 'disable-updates-manager') ?></th>
					<td>
						<fieldset>
								<legend class="screen-reader-text"><span><?php _e('Disable Updates:', 'disable-updates-manager') ?></span></legend>
							<label for="plugins_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['plugin'], true); ?> value="1" id="plugins_notify" name="_disable_updates[plugin]"> <?php _e('Disable Plugin Updates', 'disable-updates-manager') ?>
							</label>
								<br>
							<label for="themes_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['theme'], true); ?> value="1" id="themes_notify" name="_disable_updates[theme]"> <?php _e('Disable Theme Updates', 'disable-updates-manager') ?>
							</label>
								<br>
							<label for="core_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['core'], true); ?> value="1" id="core_notify" name="_disable_updates[core]"> <?php _e('Disable WordPress Core Update', 'disable-updates-manager') ?>
							</label>
						</fieldset>
					</td>
				    </tr>
					
				    <tr>
					<th scope="row"><?php _e('Other Settings:', 'disable-updates-manager') ?></th>
					<td>
						<fieldset>
								<legend class="screen-reader-text"><span><?php _e('Other Settings:', 'disable-updates-manager') ?></span></legend>
								<br>
							<label for="page_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['page'], true); ?> value="1" id="page_notify" name="_disable_updates[page]"> <?php _e('Remove Updates Page (Under Dashboard)', 'disable-updates-manager') ?>
							</label>
								<br>
							<label for="files_notify">
									<input type="checkbox" <?php checked(1, (int)$this->status['files'], true); ?> value="1" id="files_notify" name="_disable_updates[files]"> <?php _e('Removes Updates Files (Note: Only use this setting if you are disabling all the updates)', 'disable-updates-manager') ?>
							</label>
						</fieldset>
					</td>
					</tr>
				    
					<tr>
				    <td>
						<fieldset>
								<p class="submit">
									<input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
								</p>
						</fieldset>
				    </td>
				    </tr>				

                </table>
				
			</form>

		</div>
			
<?php
		}	
	}

    // Start this plugin once all other plugins are fully loaded
		global $Disable_Updates; $Disable_Updates = new Disable_Updates();

    // Plugin Page Links Function
		add_filter( 'plugin_row_meta', 'thsp_plugin_meta_links', 10, 2 );

		function thsp_plugin_meta_links( $links, $file ) {	
		    $plugin = plugin_basename(__FILE__);	

    // Create links
		if ( $file == $plugin ) {		
		    return array_merge(			
		    $links,			
		        array( '<a href="http://www.wordpress.org/support/plugin/stops-core-theme-and-plugin-updates">Support</a>' ),
		        array( '<a href="http://www.wordpress.org/plugins/stops-core-theme-and-plugin-updates/faq/">FAQ</a>' ),
		        array( '<a href="http://www.youtube.com/watch?v=ESOSt_ebiwM">Tutorial</a>' )
		);	
		}	
		return $links;
	}


    // Add Settings Link
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'thsp_plugin_action_links' );

		function thsp_plugin_action_links( $links ) {

		return array_merge(
			array('settings' => '<a href="' . admin_url( 'index.php?page=stops-core-theme-and-plugin-updates/Function.php' ) . '">' . __( 'Settings', 'ts-fab' ) . '</a>'),
				$links);
		}