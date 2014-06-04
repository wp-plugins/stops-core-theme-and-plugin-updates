<?php
/**
 * @package Disable Updates Manager
 * @author Websiteguy
 * @email webguywp@gmail.com
 * @version 4.1.0
 */
/*
Plugin Name: Disable Updates Manager
Plugin URI: http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Version: 4.1.0
Description: A configurable plugin that disables updates for you. Easy, clean and helpful.
Author: Websiteguy
Author URI: http://www.webguywp.wordpress.com
Author Email: webguywp@gmail.com
License: GPL2
Text Domain: disable-updates-manager
Domain Path: lang
Tested up to WordPress: 3.9.1

@Copyright 2013 - 2014 Websiteguy (email: webguywp@gmail.com)

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

Go to the license.txt in the trunk for more information.
*/

class Disable_Updates {

	// Define version.
	const VERSION = '4.1.0';

	function __construct() {

		// Load our textdomain
		add_action( 'init', array( __CLASS__ , 'load_textdomain' ) );

		// Add menu page.
		add_action( 'admin_menu', array( __CLASS__, 'add_submenu' ) );

		// Settings API.
		add_action( 'admin_init', array( __CLASS__, 'register_setting' ) );

		// Add action links.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'action_links' ) );

		// Add meta links.
		add_filter( 'plugin_row_meta', array( __CLASS__, 'meta_links' ), 10, 2 );

		// load the values recorded.
		$this->load_disable_updates();
	}

	static function load_textdomain() {

		load_plugin_textdomain( 'disable-updates-manager', FALSE, basename( dirname( __FILE__ ) ) . '/lang' );
	}

	static function enqueue_css() {

			wp_enqueue_style( 'disable-updates-manager-css', plugins_url( 'style.css', __FILE__ ), array(), self::VERSION );
	}

	// Register settings.
	static function register_setting() {

		register_setting( '_disable_updates', '_disable_updates', array( __CLASS__, 'validate_settings' ) );
	}

	static function validate_settings( $value ) {

		return $value;
	}

	static function add_submenu() {

		$page_hook = add_submenu_page( 'options-general.php', 'Disable Updates Manager', __( 'Disable Updates Manager', 'disable-updates-manager' ), 'manage_options', 'disable-updates-manager', array( __CLASS__, 'display_page' ) );

		// Enqueue the admin CSS.
		add_action( "load-$page_hook", array( __CLASS__, 'enqueue_css' ) );
	}

	static function action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . add_query_arg( array( 'page' => 'disable-updates-manager' ), admin_url( 'options-general.php' ) ) . '">' . __( 'Configure', 'disable-updates-manager' ) . '</a>',
				),
			$links
		);

	}

	static function meta_links( $links, $file ) {

		$plugin = plugin_basename( __FILE__ );

		if ( $file == $plugin ) {

			return array_merge(
				$links,
				array( '<a target="_BLANK" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LC5UR6667DLXU">Donate</a>' ),
				array( '<a href="http://www.wordpress.org/support/plugin/stops-core-theme-and-plugin-updates">Support</a>' ),
				array( '<a href="http://www.wordpress.org/plugins/stops-core-theme-and-plugin-updates/faq/">FAQ</a>' ),
				array( '<a href="https://www.youtube.com/watch?v=ppCxjREhF9g">Tutorial</a>' ),
				array( '<a href="https://github.com/Websiteguy/disable-updates-manager">GitHub</a>' )
			);
		}

		return $links;
	}

	// Functions for plugin (Change in settings)
	static function load_disable_updates() {

		$status = get_option( '_disable_updates' );

		if ( ! $status ) {
			return;
		}

		foreach ( $status as $id => $value ) {

			switch ( $id ) {

				// Disable Plugin Updates
				case 'plugin' :

					self::disable_plugin_updates();
					break;

				// Disable Theme Updates
				case 'theme' :

					self::disable_theme_updates();

					break;

				// Disable WordPress Core Updates
				case 'core' :

					self::disable_core_updates();

					break;

				// Remove the Dashboard Updates Menu
				case 'page' :

					// Remove the Dashboard Updates Menu Code
					add_action( 'admin_init', create_function( '', 'remove_submenu_page( \'index.php\', \'update-core.php\' );' ) );

					break;

				// Disable All Updates
				case 'all' :

					// Disable Plugin Updates Only
					self::disable_plugin_updates();

					// Disable Theme Updates Only
					self::disable_theme_updates();

					// Disable Core Updates Only
					self::disable_core_updates();

					// Disable Debug E-mails
					add_filter( 'automatic_updates_send_debug_email ', '__return_false', 1 );

					// Disable WordPress Automatic Updates
					define( 'AUTOMATIC_UPDATER_DISABLED', TRUE );
					define( 'WP_AUTO_UPDATE_CORE', FALSE );

					break;

				// Remove WordPress Version Number
				case 'wpv' :

					add_filter( 'update_footer', '__return_empty_string', 11 );

					break;

				case 'ip' :

					if ( defined( 'disable_updates_loaded' ) ) {
						return;
					}

					define( 'disable_updates_loaded', 1 );

					add_action( 'init', array( __CLASS__, 'update_plugin_block_status' ) );
					add_action( 'init', array( __CLASS__, 'plugin_action_links' ) );
					add_filter( 'site_transient_update_plugins', array( __CLASS__, 'remove_plugin_update_notification' ) );
					add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_block_action_link' ), 10, 4 );

					// remove blocked plugins from being checked for updates at wordpress.org
					add_filter( 'http_request_args', array( __CLASS__, 'http_request_args_plugins_filter' ), 5, 2 );

					break;

				case 'it':

					add_filter( 'site_transient_update_themes', array( __CLASS__, 'remove_theme_update_notification' ) );

					// remove blocked themes from being checked for updates at wordpress.org
					add_filter( 'http_request_args', array( __CLASS__, 'http_request_args_themes_filter' ), 5, 2 );

					break;

				// Disable automatic background updates.
				case 'abup' :

					wp_clear_scheduled_hook( 'wp_maybe_auto_update' );
					break;

			}
		}
	}

	static function update_plugin_block_status() {

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		// see if there are actions to process
		if ( ! isset( $_GET[ 'disable_updates' ] ) || ! isset( $_GET[ '_wpnonce' ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_GET[ '_wpnonce' ], 'disable_updates' ) ) {
			return;
		}

		$blocked = get_option( 'disable_updates_blocked' );
		// $plugins = get_site_transient( 'update_plugins' );

		// block action
		// if ( isset( $_GET[ 'block' ] ) && isset( $plugins->response ) && isset( $plugins->response[ $_GET[ 'block' ] ] ) ) {
		// 	$p                           = $plugins->response[ $_GET[ 'block' ] ];
		// 	$blocked[ $_GET[ 'block' ] ] = array( 'slug' => $p->slug, 'new_version' => $p->new_version );
		// }
		if ( isset( $_GET[ 'block' ] ) ) {

			$blocked[ $_GET[ 'block' ] ] = TRUE;
		}

		if ( isset( $_GET[ 'unblock' ] ) ) {

			unset( $blocked[ $_GET[ 'unblock' ] ] );
		}

		update_option( 'disable_updates_blocked', $blocked );
	}

	static function remove_plugin_update_notification( $plugins ) {

		if ( ! isset( $plugins->response ) || count( $plugins->response ) == 0 ) {

			return $plugins;
		}

		$blocked = (array) get_option( 'disable_updates_blocked' );

		// foreach ( $blocked as $filename => $plugin ) {

		// 	if ( isset( $plugins->response[ $filename ] )
		// 		 && $plugins->response[ $filename ]->new_version == $plugin[ 'new_version' ]
		// 	) {

		// 		$plugins->disable_updates[ $filename ] = $plugins->response[ $filename ];
		// 		unset( $plugins->response[ $filename ] );
		// 	}
		// }
		foreach ( $blocked as $filename => $plugin ) {

			if ( isset( $plugins->response[ $filename ] ) && $plugin == TRUE ) {

				$plugins->disable_updates[ $filename ] = $plugins->response[ $filename ];
				unset( $plugins->response[ $filename ] );
			}
		}

		return $plugins;
	}

	static function remove_theme_update_notification( $themes ) {

		$options = get_option( '_disable_updates' );

		if ( FALSE === $options ) {

			return $themes;
		}

		if ( isset( $options['themes'] ) ) {

			$blocked = $options['themes'];

		} else {

			return $themes;
		}

		if ( 0 === (int) count( $blocked ) ) {

			return $themes;
		}

		if ( ! isset( $themes->response ) || count( $themes->response ) == 0 ) {

			return $themes;
		}

		foreach ( $blocked as $theme ) {

			if ( isset( $themes->response[ $theme ] ) ) {

				$themes->disable_updates[ $theme ] = $themes->response[ $theme ];
				unset( $themes->response[ $theme ] );
			}
		}

		return $themes;
	}

	static function last_checked() {
		global $wp_version;

		return (object) array(
			'last_checked'    => time(),
			'updates'         => array(),
			'version_checked' => $wp_version,
		);
	}

	// Disable Core Updates
	static function disable_core_updates() {

		# 2.3 to 2.7:
		add_action( 'init', create_function( '', 'remove_action( \'init\', \'wp_version_check\' );' ), 2 );
		add_filter( 'pre_option_update_core', '__return_null' );

		# 2.8 to 3.0:
		remove_action( 'wp_version_check', 'wp_version_check' );
		remove_action( 'admin_init', '_maybe_update_core' );
		add_filter( 'pre_transient_update_core', array( __CLASS__,'last_checked' ) );

		# >3.0:
		remove_action( 'load-update-core.php', 'wp_update_core' );
		add_filter( 'pre_site_transient_update_core', array( __CLASS__,'last_checked' ) );

		// Hide Update Notices in Admin Dashboard
		add_action( 'admin_menu', create_function( '', 'remove_action( \'admin_notices\', \'update_nag\', 3 );' ) );

		wp_clear_scheduled_hook( 'wp_version_check' );

		// Disable email.
		add_filter( 'auto_core_update_send_email', '__return_false' );

		// This doesn't make sense. Purpose?
		// apply_filters( 'automatic_core_updates_send_debug_email', TRUE, $type, $core_update, $result );
	}

	// Disable Plugin Updates
	static function disable_plugin_updates() {

		# 2.3 to 2.7:
		// add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );") );
		// add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );"), 2 );
		// add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );"), 2 );
		add_filter( 'pre_option_update_plugins', '__return_null' );

		# 2.8 to 3.0:
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'admin_init', '_maybe_update_plugins' );
		remove_action( 'wp_update_plugins', 'wp_update_plugins' );
		add_filter( 'pre_transient_update_plugins', array( __CLASS__,'last_checked' ) );

		# >3.0:
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		add_filter( 'pre_site_transient_update_plugins', array( __CLASS__,'last_checked' ) );

		wp_clear_scheduled_hook( 'wp_update_plugins' );

		// Disable Plugin Update E-mails (only works for some plugins)
		// This doesn't make sense. Purpose?
		// apply_filters( 'auto_plugin_update_send_email', FALSE, $type, $plugin_update, $result );

		// This doesn't make sense. Purpose?
		// apply_filters( 'automatic_plugin_updates_send_debug_email', TRUE, $type, $plugin_update, $result );
	}

	// Disable Theme Updates
	static function disable_theme_updates() {

		# 2.8 to 3.0:
		remove_action( 'load-themes.php', 'wp_update_themes' );
		remove_action( 'load-update.php', 'wp_update_themes' );
		remove_action( 'admin_init', '_maybe_update_themes' );
		remove_action( 'wp_update_themes', 'wp_update_themes' );
		add_filter( 'pre_transient_update_themes', array( __CLASS__,'last_checked' ) );

		# >3.0:
		remove_action( 'load-update-core.php', 'wp_update_themes' );
		add_filter( 'pre_site_transient_update_themes', array( __CLASS__,'last_checked' ) );

		wp_clear_scheduled_hook( 'wp_update_themes' );

		// Disable Theme Update E-mails (only works for some plugins)
		// This doesn't make sense. Purpose?
		// apply_filters( 'auto_theme_update_send_email', FALSE, $type, $theme_update, $result );

		// This doesn't make sense. Purpose?
		// apply_filters( 'automatic_theme_updates_send_debug_email', TRUE, $type, $theme_update, $result );
	}

	static function plugin_action_links() {

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$plugins  = get_site_transient( 'update_plugins' );
		// $to_block = get_option( 'disable_updates_blocked' );

		if ( isset( $plugins->response ) ) {

			// loop through all of the plugins with updates available and attach the appropriate filter
			foreach ( $plugins->response as $filename => $plugin ) {

				// check that the version is the version we want to block updates to
				add_action( "in_plugin_update_message-$filename", array( __CLASS__, 'plugin_block_link' ), 99, 2 );
			}
		}

		if ( isset( $plugins->disable_updates ) ) {

			foreach ( $plugins->disable_updates as $filename => $plugin ) {

				// check that the version is the version we want to block updates to
				add_action( "after_plugin_row_$filename", array( __CLASS__, 'plugin_unblock_link'), -1, 1 );
			}
		}
	}

	static function plugin_block_action_link( $actions, $plugin_file, $plugin_data, $context ) {

		$blocked = get_option( 'disable_updates_blocked' );

		if ( array_key_exists( $plugin_file, $blocked ) ) {

			$actions[] = '<a class="delete" href="plugins.php?_wpnonce=' . wp_create_nonce( 'disable_updates' ) . '&disable_updates&unblock=' . $plugin_file . '">Unblock Updates</a>';

		} else {

			$actions[] = '<a class="delete" href="plugins.php?_wpnonce=' . wp_create_nonce( 'disable_updates' ) . '&disable_updates&block=' . $plugin_file . '">Block Updates</a>';
		}

		return $actions;
	}

	static function http_request_args_plugins_filter( $r, $url ) {

		if ( 0 !== strpos( $url, 'https://api.wordpress.org/plugins/update-check/1.1/' ) ) {

			return $r;
		}

		$blocked = get_option( 'disable_updates_blocked' );
		$blocked = (array) array_keys( $blocked );

		if ( 0 === (int) count( $blocked ) ) {

			return $r;
		}

		if ( ! isset( $r['body']['plugins'] ) ) {

			return $r;
		}

		$plugins = json_decode( $r['body']['plugins'], TRUE );

		foreach ( $blocked as $p ) {

			if ( isset( $plugins['plugins'][ $p ] ) ) unset( $plugins['plugins'][ $p ] );
			if ( FALSE !== $key = array_search( $p, $plugins['active'] ) ) unset( $plugins['active'][ $key ] );
		}

		$r['body']['plugins'] = json_encode( $plugins );

		return $r;
	}

	static function http_request_args_themes_filter( $r, $url ) {

		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {

			return $r;
		}

		$options = get_option( '_disable_updates' );

		if ( FALSE === $options ) {

			return $r;
		}

		if ( isset( $options['themes'] ) ) {

			$blocked = $options['themes'];

		} else {

			return $r;
		}

		if ( 0 === (int) count( $blocked ) ) {

			return $r;
		}

		if ( ! isset( $r['body']['themes'] ) ) {

			return $r;
		}

		$themes = json_decode( $r['body']['themes'], TRUE );

		foreach ( $blocked as $t ) {

			if ( isset( $themes['themes'][ $t ] ) ) unset( $themes['themes'][ $t ] );
		}

		$r['body']['themes'] = json_encode( $themes );

		return $r;
	}

	// Settings page (under dashboard).
	static function display_page() {

		$status = get_option( '_disable_updates' );

		// Don't Allow Users to View Settings
		if ( ! current_user_can( 'update_core' ) ) {
			wp_die( __( 'You do not have permissions to access this page.' ) );
		}
		?>

		<div class="wrap">

		<h2><?php _e( 'Disable Updates Manager Settings', 'disable-updates-manager' ); ?></h2>

			<div class="error" style="width: 780px;">
				<p>
					<strong>Please Note! - </strong>If either your WordPress core, theme, or plugins get too out
					of date, you may run into compatibility problems.
				</p>
			</div>

			<form method="post" action="options.php">

				<?php settings_fields( '_disable_updates' ); ?>

				<table class="wp-list-table widefat fixed bookmarks" style="width: 590px; border-radius: 4px;">
					<thead>
						<tr>
							<th>Disable Updates</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>

								<div class="showonhover">
									<label for="all_notify">
										<input
											type="checkbox" <?php checked( 1, ( isset( $status['all'] ) ? (int) $status['all'] : 0 ), TRUE ); ?>
											value="1" id="all_notify"
											name="_disable_updates[all]"> <?php _e( 'Disable All Updates', 'disable-updates-manager' ) ?>
									</label>
									<span>
										<a href="#" class="viewdescription">?</a>
										<span class="hovertext">Just disables core, theme, and plugin updates.</span>
									</span>
								</div>

								<span style="padding-left: 12px; display:block">
									<label for="plugins_notify">
										<input type="checkbox" <?php checked( 1, ( isset( $status['plugin'] ) ? (int) $status['plugin'] : 0 ), TRUE ); ?>
											   value="1" id="plugins_notify"
											   name="_disable_updates[plugin]"> <?php _e( 'Disable Plugin Updates', 'disable-updates-manager' ) ?>
									</label>
									<br>
									<label for="themes_notify">
										<input type="checkbox" <?php checked( 1, ( isset( $status['theme'] ) ? (int) $status['theme'] : 0 ), TRUE ); ?>
											   value="1" id="themes_notify"
											   name="_disable_updates[theme]"> <?php _e( 'Disable Theme Updates', 'disable-updates-manager' ) ?>
									</label>
									<br>
									<label for="core_notify">
										<input type="checkbox" <?php checked( 1, ( isset( $status['core'] ) ? (int) $status['core'] : 0 ), TRUE ); ?>
											   value="1" id="core_notify"
											   name="_disable_updates[core]"> <?php _e( 'Disable WordPress Core Update', 'disable-updates-manager' ) ?>
									</label>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
				<br>

				<table class="wp-list-table widefat fixed bookmarks" style="width: 590px; border-radius: 4px;">
					<thead>
						<tr>
							<th>Disable Plugins Individually</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="showonhover">
									<label for="ip_notify">
										<input type="checkbox" <?php checked( 1, ( isset( $status['ip'] ) ? (int) $status['ip'] : 0 ), TRUE ); ?>
											   value="1" id="ip_notify"
											   name="_disable_updates[ip]"> <?php _e( 'Disable Plugins Individually', 'disable-updates-manager' ) ?>
									</label>

									<span>
										<a href="#" class="viewdescription">?</a>
										<span class="hovertext">Go to the "Plugins" section in your dashboard to disable.</span>
									</span>
								</div>

							</td>
						</tr>
					</tbody>
				</table>
				<br>

				<table class="wp-list-table widefat fixed bookmarks" style="width: 590px; border-radius: 4px;">
					<thead>
						<tr>
							<th>Disable Themes Individually</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="showonhover">
									<label for="it_notify">
										<input type="checkbox" <?php checked( 1, ( isset( $status['it'] ) ? (int) $status['it'] : 0 ), TRUE ); ?>
											   value="1" id="it_notify"
											   name="_disable_updates[it]"> <?php _e( 'Disable Themes Individually', 'disable-updates-manager' ) ?>
									</label>

									<span>
										<a href="#" class="viewdescription">?</a>
										<span class="hovertext">Enabling this option will show the list of themes to block updates.</span>
									</span>
								</div>
							</td>
						</tr>

						<?php

						if ( isset( $status['it'] ) ) {

							?>

							<tr>
								<th>Select themes to disable:</th>
							</tr>

							<?php

							$themes = wp_get_themes( array( 'allowed' => TRUE ) );

							foreach ( $themes as $slug => $theme ) {

								$key = sanitize_key( $slug );

								?>

								<tr>
									<td>

										<?php

										printf( '<label for="%1$s"><input type="checkbox" value="%3$s" id="%1$s" name="_disable_updates[themes][%1$s]"%2$s> %4$s</label>',
											$key,
											( isset( $status['themes'][ $key ] ) && $status['themes'][ $key ] === $slug ) ? ' checked="checked"' : '',
											$slug,
											esc_html( $theme->name )
										 );

										?>

									</td>
								</tr>

							<?php
							}

						}
						?>

					</tbody>
				</table>
				<br>

				<table class="wp-list-table widefat fixed bookmarks" style="width: 590px; border-radius: 4px;">
					<thead>
						<tr>
							<th>Other Settings</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="showonhover">
									<label for="page_notify">
										<input
											type="checkbox" <?php checked( 1, ( isset( $status['page'] ) ? (int) $status['page'] : 0 ), TRUE ); ?>
											value="1" id="page_notify"
											name="_disable_updates[page]"> <?php _e( 'Remove Updates Page', 'disable-updates-manager' ) ?>
									</label>
									<span>
										<a href="#" class="viewdescription">?</a>
										<span class="hovertext">The one in the dashboard tab.</span>
									</span>
								</div>

								<div class="showonhover">
									<label for="wpv_notify">
										<input
											type="checkbox" <?php checked( 1, ( isset( $status['wpv'] ) ? (int) $status['wpv'] : 0 ), TRUE ); ?>
											value="1" id="wpv_notify"
											name="_disable_updates[wpv]"> <?php _e( 'Remove WordPress Core Version from Footer', 'disable-updates-manager' ) ?>
									</label>
									<span>
										<a href="#" class="viewdescription">?</a>
										<span class="hovertext">Removes it for all users.</span>
									</span>
								</div>

								<label for="abup_notify">
									<input type="checkbox" <?php checked( 1, ( isset( $status['abup'] ) ? (int) $status['abup'] : 0 ), TRUE ); ?>
										   value="1" id="abup_notify"
										   name="_disable_updates[abup]"> <?php _e( 'Disable Automatic Background Updates', 'disable-updates-manager' ) ?>
								</label>
								<br>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Update Settings' ) ?>"/>
				</p>

				<table class="wp-list-table widefat fixed bookmarks"
					   style="width: auto; padding: 5px; border-radius: 4px;">
					<tbody>
						<tr>
							<td>
								<p align="center">
									<a href="http://wordpress.org/support/plugin/stops-core-theme-and-plugin-updates">Support</a> |
									<a href="https://www.youtube.com/watch?v=ppCxjREhF9g">Tutorial</a> |
									<a href="http://wordpress.org/plugins/stops-core-theme-and-plugin-updates/faq/">FAQ</a> |
									<a href="https://github.com/Websiteguy/disable-updates-manager">GitHub</a>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

			</form>
		</div>

	<?php
	}
}

global $Disable_Updates;
$Disable_Updates = new Disable_Updates();

if ( ! function_exists( 'printr' ) ) {
	function printr( $txt ) {
		echo '<pre>';
		print_r( $txt );
		echo '</pre>';
	}
}