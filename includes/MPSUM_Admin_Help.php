<?php
/**
 * Help Screen for Easy Updates Manager
 *
 * Initializes and outputs the help screen for the plugin.
 *
 * @since 5.0.0
 *
 * @package WordPress
 */
class MPSUM_Admin_Help {
	
	/**
	* Class constructor.
	*
	* Initialize the class
	*
	* @since 5.0.0
	* @access public
	*
	*/
	public function __construct() {
		$screen = get_current_screen();

		$screen  = get_current_screen();
		$content1 = '<p>';
		$content1 .= esc_html__( 'This is the Easy Updates Manager settings help tab. In here you will find helpful information on what Easy Updates Manager does and how to use it.', 'stops-core-theme-and-plugin-updates' );
		$content1 .= '</p>';
		$content1 .= sprintf( '<div><p><strong>%s - </strong>%s</p></div>', esc_html__( 'Please Note!', 'stops-core-theme-and-plugin-updates' ), esc_html__( 'If either your WordPress core, theme, or plugins get too out of date, you may run into compatibility problems. Check the capability tab for more information.', 'stops-core-theme-and-plugin-updates' ) );

		$content2 = sprintf( '<div><p><a href="https://github.com/easy-updates-manager/easy-updates-manager/wiki">%s</a></p></div>', esc_html__( 'Please see our Wiki for documentation and videos.', 'stops-core-theme-and-plugin-updates' ) );
		
		$content3_strings = array(
			'website' => esc_html__( 'Our Website', 'stops-core-theme-and-plugin-updates' ),
			'support' => esc_html__( 'Support on WordPress', 'stops-core-theme-and-plugin-updates' ),
			'github' => esc_html__( 'GitHub Repository', 'stops-core-theme-and-plugin-updates' ),
			'official' => esc_html__( 'Official Documentation', 'stops-core-theme-and-plugin-updates' ),
		);
		$content3 = <<<CONTENT3
	<p>
        <a href="http://mpswp.wordpress.com" class="button">{$content3_strings['website']}</a>
	    <a href="http://wordpress.org/support/plugin/stops-core-theme-and-plugin-updates" class="button">{$content3_strings['support']}</a>
	    <a href="https://github.com/easy-updates-manager/easy-updates-manager" class="button">{$content3_strings['github']}</a>
	     <a href="https://github.com/easy-updates-manager/easy-updates-manager/wiki" class="button">{$content3_strings['official']}</a>
    </p>

CONTENT3;
		
		$content4_strings = array(
			'intro' => esc_html__( 'You will see four tabs where you can configure the update options.', 'stops-core-theme-and-plugin-updates' ),
			'general' => sprintf( '<strong>%s</strong> - %s', esc_html__( 'General', 'stops-core-theme-and-plugin-updates' ), esc_html__( 'Use this screen to finely tune which updates and automatic updates you would like to see.', 'stops-core-theme-and-plugin-updates' ) ),
			'plugins' => sprintf( '<strong>%s</strong> - %s', esc_html__( 'Plugins', 'stops-core-theme-and-plugin-updates' ), esc_html__( 'If plugin updates are enabled and/or automatic updates for plugins are enabled, you can configure which plugins will receive updates and/or automatic updates.', 'stops-core-theme-and-plugin-updates' ) ),
			'themes' => sprintf( '<strong>%s</strong> - %s', esc_html__( 'Themes', 'stops-core-theme-and-plugin-updates' ), esc_html__( 'If theme updates are enabled and/or automatic updates for themes are enabled, you can configure which themes will receive updates and/or automatic updates.', 'stops-core-theme-and-plugin-updates' ) ),
			'advanced' => sprintf( '<strong>%s</strong> - %s', esc_html__( 'Advanced', 'stops-core-theme-and-plugin-updates' ), esc_html__( 'Reset all options or allow certain users to see all updates regardless of what settings you have set.', 'stops-core-theme-and-plugin-updates' ) ),
			
		);
		$content4 = <<<CONTENT4
<p>
{$content4_strings['intro']}
<br>
<br>
{$content4_strings['general']}
<br>
<br>
{$content4_strings['plugins']}
<br>
<br>
{$content4_strings['themes']}
<br>
<br>
{$content4_strings['advanced']}
<br>
<br>
</p>

CONTENT4;
		
		$content5_strings = array(
			'contributors' => esc_html__( 'Contributors:', 'stops-core-theme-and-plugin-updates' )
		);
		$content5 = <<<CONTENT5
		
<p style="align: center;">
<h3>{$content5_strings[ 'contributors' ]}</h3>
<ul>
<li><a href="http://profiles.wordpress.org/kidsguide/">Matthew</a></li>
<li><a href="http://profiles.wordpress.org/ronalfy/">Ronalfy</a></li>
</ul>
</p>

CONTENT5;

		$content6 = '<p>';
		$content6 .= esc_html__( 'WordPress encourages you to update your plugins, themes, and core to make sure that there are no bugs. Even though you most likely want to disable all the updates and never think about updating again, you should still consider updating every once in a while to avoid major bugs and errors on your WordPress website.', 'stops-core-theme-and-plugin-updates' );
		$content6 .= sprintf( '<h3>%s</h3>', esc_html__( 'This plugin is tested so there are no known major issues.', 'stops-core-theme-and-plugin-updates' ) );
		$content6 .= '<ul>';
		$content6 .= sprintf( '<li>%s</li>', esc_html__( 'Tested with WordPress 4.1.1.',  'stops-core-theme-and-plugin-updates' ) );
		$content6 .= sprintf( '<li>%s</li>', esc_html__( 'Tested with popular plugins to ensure that there are no conflicts.',  'stops-core-theme-and-plugin-updates' ) );
		$content6 .= sprintf( '<li>%s</li>', esc_html__( 'Tested with popular themes to ensure that there are no conflicts.',  'stops-core-theme-and-plugin-updates' ) );
		$content6 .= '</ul>';
		$content6 .= '</p>';

		$screen->add_help_tab(array(
				'id'      => 'help_tab_content_1',
				'title'   => __( 'Overview',  'stops-core-theme-and-plugin-updates' ),
				'content' => $content1,
			));
			
	    $screen->add_help_tab(array(
                'id' => 'help_tab_content_4',
                'title' => __( 'Tabs',  'stops-core-theme-and-plugin-updates' ),
                'content' => $content4,
            ));
			
	    $screen->add_help_tab(array(
                'id' => 'help_tab_content_2',
                'title' => __( 'Documentation',  'stops-core-theme-and-plugin-updates' ),
                'content' => $content2,
            ));	
			
	    $screen->add_help_tab(array(
                'id' => 'help_tab_content_6',
                'title' => __( 'Capability',  'stops-core-theme-and-plugin-updates' ),
                'content' => $content6,
            ));
			
	    $screen->add_help_tab(array(
                'id' => 'help_tab_content_3',
                'title' => __( 'Troubleshooting',  'stops-core-theme-and-plugin-updates' ),
                'content' => $content3,
            ));
				
		$screen->set_help_sidebar($content5);
	
	} //end constructor
	
}
