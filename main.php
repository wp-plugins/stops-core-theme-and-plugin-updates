<?php
/*
Plugin Name: Easy Updates Manager
Plugin URI: https://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Description: Manage and disable WordPress updates, including core, plugin, theme, and automatic updates - Works with Multisite
Author: MPS Plugins, kidsguide, ronalfy
Version: 5.0.0
Requires at least: 4.0
Author URI: https://wordpress.org/plugins/stops-core-theme-and-plugin-updates/
Contributors: MPS Plugins, kidsguide, ronalfy
Text Domain: stops-core-theme-and-plugin-updates
Domain Path: /languages
Updates: true
Network: true
*/ 
/**
 * Main plugin class
 *
 * Initializes auto-loader, internationalization, and plugin dependencies.
 *
 * @since 5.0.0
 *
 * @package WordPress
 */
class MPSUM_Updates_Manager {
	
	/**
	* Holds the class instance.
	*
	* @since 5.0.0
	* @access static
	* @var MPSUM_Updates_Manager $instance
	*/
	private static $instance = null;
	
	/**
	* Stores the plugin's options
	*
	* @since 5.0.0
	* @access static
	* @var array $options
	*/
	private static $options = false;
	
	/**
	* Retrieve a class instance.
	*
	* Retrieve a class instance.
	*
	* @since 5.0.0 
	* @access static
	*
	* @return MPSUM_Updates_Manager Instance of the class.
	*/
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	} //end get_instance
	
	/**
	* Retrieve the plugin basename.
	*
	* Retrieve the plugin basename.
	*
	* @since 5.0.0 
	* @access static
	*
	* @return string plugin basename
	*/
	public static function get_plugin_basename() {
		return plugin_basename( __FILE__ );	
	}
	
	/**
	* Class constructor.
	*
	* Set up internationalization, auto-loader, and plugin initialization.
	*
	* @since 5.0.0
	* @access private
	*
	*/
	private function __construct() {
		/* Localization Code */
		load_plugin_textdomain( 'stops-core-theme-and-plugin-updates', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		spl_autoload_register( array( $this, 'loader' ) );
		
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	} //end constructor

	/**
	* Return the absolute path to an asset.
	*
	* Return the absolute path to an asset based on a relative argument.
	*
	* @since 5.0.0 
	* @access static
	*
	* @param string  $path Relative path to the asset.
	* @return string Absolute path to the relative asset.
	*/
	public static function get_plugin_dir( $path = '' ) {
		$dir = rtrim( plugin_dir_path(__FILE__), '/' );
		if ( !empty( $path ) && is_string( $path) )
			$dir .= '/' . ltrim( $path, '/' );
		return $dir;		
	}
	
	/**
	* Return the web path to an asset.
	*
	* Return the web path to an asset based on a relative argument.
	*
	* @since 5.0.0 
	* @access static
	*
	* @param string  $path Relative path to the asset.
	* @return string Web path to the relative asset.
	*/
	public static function get_plugin_url( $path = '' ) {
		$dir = rtrim( plugin_dir_url(__FILE__), '/' );
		if ( !empty( $path ) && is_string( $path) )
			$dir .= '/' . ltrim( $path, '/' );
		return $dir;	
	}
	
	/**
	* Retrieve the plugin's options
	*
	* Retrieve the plugin's options based on context
	*
	* @since 5.0.0 
	* @access static
	*
	* @param string  $context Context to retrieve options for.  This is used as an array key.
	* @param bool  $force_reload Whether to retrieve cached options or forcefully retrieve from the database.
	* @return array All options if no context, or associative array if context is set.  Empty array if no options.
	*/
	public static function get_options( $context = '', $force_reload = false ) {
		//Try to get cached options
		$options = self::$options;
		if ( false === $options || true === $force_reload ) {
			$options = get_site_option( 'MPSUM', false, false );
		}
		
		if ( false === $options ) {
			$options = self::maybe_migrate_options();		
		}
		
		//Store options
		if ( !is_array( $options ) ) {
			$options = array();	
		}
		self::$options = $options;
		
		//Attempt to get context
		if ( !empty( $context ) && is_string( $context ) ) {
			if ( array_key_exists( $context, $options ) ) {
				return (array)$options[ $context ];	
			} else {
				return array();	
			}
		}
		
		
		return $options;
	} //get_options
	
	/**
	* Auto-loads classes.
	*
	* Auto-load classes that belong to this plugin.
	*
	* @since 5.0.0 
	* @access private
	*
	* @param string  $class_name The name of the class.
	*/
	private function loader( $class_name ) {
		if ( class_exists( $class_name, false ) || false === strpos( $class_name, 'MPSUM' ) ) {
			return;
		}
		$file = MPSUM_Updates_Manager::get_plugin_dir( "includes/{$class_name}.php" );
		if ( file_exists( $file ) ) {
			include_once( $file );
		}	
	}
	
	/**
	* Determine whether to migrate options from an older version of the plugin.
	*
	* Migrate old options to new plugin format.
	*
	* @since 5.0.0 
	* @access private
	*
	* @return bool|array  false if no migration, associative array of options if migration successful
	*/
	public static  function maybe_migrate_options() {
		$options = false;
		$original_options = get_option( '_disable_updates', false );
		
		if ( false !== $original_options && is_array( $original_options ) ) {
			$options = array(
				'core' => array(),
				'plugins' => array(),
				'themes' => array()
			);
			//Global WP Updates
			if ( isset( $original_options[ 'all' ] ) && "1" === $original_options[ 'all' ] ) {
				$options[ 'core' ][ 'all_updates' ] = 'off';
			}
			//Global Plugin Updates
			if ( isset( $original_options[ 'plugin' ] ) && "1" === $original_options[ 'plugin' ] ) {
				$options[ 'core' ][ 'plugin_updates' ] = 'off';
			}
			//Global Theme Updates
			if ( isset( $original_options[ 'theme' ] ) && "1" === $original_options[ 'theme' ] ) {
				$options[ 'core' ][ 'theme_updates' ] = 'off';
			}
			//Global Core Updates
			if ( isset( $original_options[ 'core' ] ) && "1" === $original_options[ 'core' ] ) {
				$options[ 'core' ][ 'core_updates' ] = 'off';
			}
			//Global Individual Theme Updates
			if ( isset( $original_options[ 'it' ] ) && "1" === $original_options[ 'it' ] ) {
				if ( isset( $original_options[ 'themes' ] ) && is_array( $original_options[ 'themes' ] ) ) {
					$options[ 'themes' ] = 	$original_options[ 'themes' ];
				}
			}
			//Global Individual Plugin Updates
			if ( isset( $original_options[ 'ip' ] ) && "1" === $original_options[ 'ip' ] ) {
				if ( isset( $original_options[ 'plugins' ] ) && is_array( $original_options[ 'plugins' ] ) ) {
					$options[ 'plugins' ] = 	$original_options[ 'plugins' ];
				}
			}
			//Browser Nag
			if ( isset( $original_options[ 'bnag' ] ) && "1" === $original_options[ 'bnag' ] ) {
				$options[ 'core' ][ 'misc_browser_nag' ] = 'off';
			}
			//WordPress Version
			if ( isset( $original_options[ 'wpv' ] ) && "1" === $original_options[ 'wpv' ] ) {
				$options[ 'core' ][ 'misc_wp_footer' ] = 'off';
			}
			//Translation Updates
			if ( isset( $original_options[ 'auto-translation-updates' ] ) && "1" === $original_options[ 'auto-translation-updates' ] ) {
				$options[ 'core' ][ 'automatic_translation_updates' ] = 'off';
			}
			//Translation Updates
			if ( isset( $original_options[ 'auto-core-emails' ] ) && "1" === $original_options[ 'auto-core-emails' ] ) {
				$options[ 'core' ][ 'notification_core_update_emails' ] = 'off';
			}
			//Automatic Updates
			if ( isset( $original_options[ 'abup' ] ) && "1" === $original_options[ 'abup' ] ) {
				$options[ 'core' ][ 'automatic_major_updates' ] = 'off';
				$options[ 'core' ][ 'automatic_minor_updates' ] = 'off';
				$options[ 'core' ][ 'automatic_plugin_updates' ] = 'off';
				$options[ 'core' ][ 'automatic_theme_updates' ] = 'off';
			}
			
			delete_option( '_disable_updates' );
			delete_site_option( '_disable_updates' );
			update_site_option( 'MPSUM', $options );
			
		}
		return $options;		
	}
	
	/**
	* Initialize the plugin and its dependencies.
	*
	* Initialize the plugin and its dependencies.
	*
	* @since 5.0.0 
	* @access public
	* @see __construct
	* @internal Uses plugins_loaded action
	*
	*/
	public function plugins_loaded() {
		//Skip disable updates if a user is excluded
		$disable_updates_skip = false;
		if ( current_user_can( 'update_core' ) ) {
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$excluded_users = MPSUM_Updates_Manager::get_options( 'excluded_users' );
			if ( in_array( $current_user_id, $excluded_users ) ) {
				$disable_updates_skip = true;
			}
		}
		if ( false === $disable_updates_skip ) {
			MPSUM_Disable_Updates::run();
		}
		
		
		$not_doing_ajax = ( !defined( 'DOING_AJAX' ) || !DOING_AJAX );
		$not_admin_disabled = ( !defined( 'MPSUM_DISABLE_ADMIN' ) || !MPSUM_DISABLE_ADMIN );
		if ( is_admin() && $not_doing_ajax && $not_admin_disabled ) {
			MPSUM_Admin::run();	
		}	
	}
	
	/**
	* Save plugin options.
	*
	* Saves the plugin options based on context.  If no context is provided, updates all options.
	*
	* @since 5.0.0
	* @access static
	*
	* @param array  $options Associative array of plugin options.
	* @param string $context Array key of which options to update
	*/
	public static function update_options( $options = array(), $context = '' ) {
		$options_to_save = self::get_options();
		
		if ( !empty( $context ) && is_string( $context ) ) {
			$options_to_save[ $context ] = $options;
		} else {
			$options_to_save = $options;	
		}
		
		self::$options = $options_to_save;
		update_site_option( 'MPSUM', $options_to_save );
	}
		
} //end class MPSUM_Updates_Manager

MPSUM_Updates_Manager::get_instance();