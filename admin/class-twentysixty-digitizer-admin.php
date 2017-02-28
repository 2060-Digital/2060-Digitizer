<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://2060digital.com
 * @since      1.0.0
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/admin
 * @author     Ryan Benhase <rbenhase@2060digital.com>
 */
class Twentysixty_Digitizer_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


  /**
   * Disable some built-in dashboard widgets.
   * 
   * @access public
   * @return void
   */
  public function disable_default_dashboard_widgets() {
  	global $wp_meta_boxes;
  	
  	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);       // Right Now Widget
  	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
  	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
  	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
  	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget
  
  	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);        // Quick Press Widget
  	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);      // Recent Drafts Widget
  	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);            // Primary
  	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);          // Secondary
  }

  /*
   * Change Wordpress logo Link on login page
   * @access public
   * @return void
   */
  public function login_url() { return home_url(); }
  
  /*
   * Change Wordpress alt attribute to blog name on login page.
   * @access public
   * @return void
   */ 
  public function login_title() { return get_option( 'blogname' ); }
  
  
  /*
   * Enqueue login styles.
   * @access public
   * @return void
   */ 
  public function login_styles() {
    wp_enqueue_style( 'twentysixty-digitizer-login-style', plugins_url( 'css/login.css', __FILE__ ) );
  }
  
  
  /*
   * Enqueue admin scripts and styles.
   * @access public
   * @return void
   */ 
  public function enqueue_scripts_styles() {
    wp_enqueue_style( 'twentysixty-digitizer-admin-style', plugins_url( 'css/twentysixty-digitizer-admin.css', __FILE__ ) );
  }
  
  
  /**
   * Customize admin footer.
   * 
   * @access public
   * @return void
   */
  public function custom_admin_footer() {
  	_e( '<span id="footer-thankyou">Developed by <a href="http://2060digital.com" target="_blank">2060 Digital</a></span>.', 'twentysixtytheme' );
  }
  
  
  /**
   * If plugin has been recently updated, handle any upgrades or scripts that need to run once.
   * 
   * @access public
   * @return void
   */
  public function maybe_run_update() {
  	
  	$digitizer_version = get_option( "twentysixty-digitizer-version" );
  	
  	if ( empty( $digitizer_version ) || version_compare( $this->version, $digitizer_version ) === 1 ) {    	
    	// Anything that needs to be run once when the plugin is updated
      
      // Add/remove user accounts
    	$user = get_user_by( "email", "acurtis@2060digital.com" );    	
    	    	
    	if ( $user != false ) {
      	$user->user_nicename = 'ctjohnson';
      	$user->display_name = 'Chad Johnson';
      	$user->user_email = 'ctjohnson@2060digital.com';  
      	
      	$user_id = wp_update_user( $user );
      	
      	if ( !is_wp_error( $user_id ) ) {
  	      update_user_meta( $user->ID, 'nickname', 'ctjohnson' );
          update_user_meta( $user->ID, 'first_name', 'Chad' );
          update_user_meta( $user->ID, 'last_name', 'Johnson' );
          update_user_meta( $user->ID, 'wp_capabilities', array ( 'administrator' => true ) );
          
          global $wpdb;
          
          $wpdb->update( $wpdb->users, array( 'user_login' => 'ctjohnson' ), array( 'ID' => $user_id ) );
      	}    	
    	}  
    	
    	update_option( "twentysixty-digitizer-version", $this->version );  	
  	}
	}
}
