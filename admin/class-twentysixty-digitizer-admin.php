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
  	
  	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);      // Site Health
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
    
    // Dynamic styles
    $stored_url = get_option( 'twentysixty_digitizer_login_logo' );
  	if ( empty( $stored_url ) )
  	  $stored_url =  plugins_url( 'images/login-logo.png', __FILE__ );
    
  

    list($width, $height) = getimagesize( str_replace("https://", "http://", $stored_url) );
    $width = $width/2;
    $height = $height/2;
    
    $dynamic_styles = "
    .login h1 a {
      background: url({$stored_url}) no-repeat top center;
      background-size:{$width}px {$height}px;
      width:{$width}px;
      height:{$height}px;
    }
    "; 
    wp_add_inline_style( "twentysixty-digitizer-login-style", $dynamic_styles );
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
  	_e( '<span id="footer-thankyou">Developed by <a href="//2060digital.com" target="_blank">2060 Digital</a></span>.', 'twentysixtytheme' );
  }
  

  /**
   * Add a settings page.
   * 
   * @access public
   * @return void
   */
  public function add_admin_menu() {
    add_options_page( '2060 Digital', '2060 Digital', 'manage_options', 'twentysixty_digitizer', array( $this, 'options_page' ) );
  }

  /**
   * Register settings.
   * 
   * @access public
   * @return void
   */
  public function settings_init() { 
  
  	register_setting( 'settingsPage', 'twentysixty_digitizer_remote_access_token' );
  	register_setting( 'settingsPage', 'twentysixty_digitizer_login_logo' );
  	register_setting( 'settingsPage', 'twentysixty_digitizer_scripts' );
  
  	add_settings_section(
  		'twentysixty_settingsPage_section_key', 
  		__( 'Receive updates', $this->plugin_name ), 
  		array( $this, 'settings_section_key_callback' ), 
  		'settingsPage'
  	);
  
  	add_settings_field( 
  		'twentysixty_digitizer_remote_access_token', 
  		__( 'Access Key', $this->plugin_name ), 
  		array( $this, 'access_key_render' ), 
  		'settingsPage', 
  		'twentysixty_settingsPage_section_key' 
  	);
  	
    add_settings_section(
  		'twentysixty_settingsPage_section_scripts', 
  		__( '<hr><br>Custom Scripts', $this->plugin_name ), 
  		array( $this, 'settings_section_scripts_callback' ), 
  		'settingsPage'
  	);
  
  	add_settings_field( 
  		'twentysixty_digitizer_script_analytics', 
  		__( 'Google Analytics Tracking ID', $this->plugin_name ), 
  		array( $this, 'analytics_render' ), 
  		'settingsPage', 
  		'twentysixty_settingsPage_section_scripts' 
  	);

  	add_settings_field( 
  		'twentysixty_digitizer_script_gtm', 
  		__( 'Google Tag Manager Container ID', $this->plugin_name ), 
  		array( $this, 'gtm_render' ), 
  		'settingsPage', 
  		'twentysixty_settingsPage_section_scripts' 
  	);	
  	
    add_settings_field( 
  		'twentysixty_digitizer_script_fb', 
  		__( 'Facebook Base Pixel ID', $this->plugin_name ), 
  		array( $this, 'fb_render' ), 
  		'settingsPage', 
  		'twentysixty_settingsPage_section_scripts' 
  	);	
  	
  	
  	add_settings_section(
  		'twentysixty_settingsPage_section_login', 
  		__( '<hr><br>Customize Login Screen', $this->plugin_name ), 
  		array( $this, 'settings_section_login_callback' ), 
  		'settingsPage'
  	);
  	
  	add_settings_field( 
  		'twentysixty_digitizer_login_logo', 
  		__( 'Login Logo (2x)', $this->plugin_name ), 
  		array( $this, 'login_logo_render' ), 
  		'settingsPage', 
  		'twentysixty_settingsPage_section_login' 
  	);
  }
  

  /**
   * Render access key field.
   * 
   * @access public
   * @return void
   */
  public function access_key_render() {   
  	$stored_key = get_option( 'twentysixty_digitizer_remote_access_token' );
  	?>
  	<input type='text' name='twentysixty_digitizer_remote_access_token' size="46" value='<?php echo $stored_key; ?>'>
  	<?php  
  }
  
  /**
   * Render Analytics field.
   * 
   * @access public
   * @return void
   */
  public function analytics_render() {   
  	$stored_scripts = get_option( 'twentysixty_digitizer_scripts' );
  	?>
  	<input type='text' name='twentysixty_digitizer_scripts[analytics]' size="46" placeholder="UA-XXXXXXXXX-1" value='<?php echo $stored_scripts["analytics"]; ?>'>
  	<?php  
  }

  /**
   * Render GTM field.
   * 
   * @access public
   * @return void
   */
  public function gtm_render() {   
  	$stored_scripts = get_option( 'twentysixty_digitizer_scripts' );
  	?>
  	<input type='text' name='twentysixty_digitizer_scripts[gtm]' size="46" placeholder="GTM-XXXXXX" value='<?php echo $stored_scripts["gtm"]; ?>'>
  	<?php $theme = wp_get_theme(); ?>
  	<?php $theme_name = $theme->__toString(); ?>
  	<?php if ( $theme_name != '2060 Digital Child Theme' && $theme_name != 'Beaver Builder Theme' ): ?>
  	<br><br>
  	<small>Warning: Your current theme looks like it may not support the <code>fl_body_open</code> action.<br>This means that Google Tag Manager will not have a fallback for users with JavaScript disabled.<br>It is therefore recommended that you manually add your GTM container in your code instead.</small>
  	<?php endif; ?>
  	<?php  
  }
  
  /**
   * Render Facebook Pixel field.
   * 
   * @access public
   * @return void
   */
  public function fb_render() {   
  	$stored_scripts = get_option( 'twentysixty_digitizer_scripts' );
  	?>
  	<input type='text' name='twentysixty_digitizer_scripts[fb]' size="46" placeholder="XXXXXXXXXXXXXXX" value='<?php echo $stored_scripts["fb"]; ?>'>
  	<?php  
  }
  
  /**
   * Render login logo field.
   * 
   * @access public
   * @return void
   */
  public function login_logo_render() {   
  	$stored_url = get_option( 'twentysixty_digitizer_login_logo' );
  	if ( empty( $stored_url ) )
  	  $stored_url = plugins_url( 'images/login-logo.png', __FILE__ );
  	 
    list($width, $height) = getimagesize( $stored_url );
    $width = $width/2;
    $height = $height/2;

    $logo_url = $stored_url . '?v=2020';
    
  	?>
  	<div class="login-logo-upload">
  	<img src="<?php echo $logo_url; ?>" height="<?php echo $height; ?>px" width="<?php echo $width; ?>px" alt="login logo" class="twentysixty_digitizer_login_logo_img" /><br>
  	<input type='hidden' name='twentysixty_digitizer_login_logo' size="46" value='<?php echo $logo_url; ?>'>
    <p><a href="#" class="login_logo_upload button-secondary">Upload New</a>       
    <small>Upload a 2x (retina) version of the login logo here.</small><br><small>To keep things looking nice, we recommend this image be between 300px and 640px wide.</small></p>   
  	</div>
      <script>
        jQuery(document).ready(function($) {
            $('.login_logo_upload').click(function(e) {
                e.preventDefault();
    
                var custom_uploader = wp.media({
                    title: 'Custom Login Logo',
                    button: {
                        text: 'Upload Image'
                    },
                    multiple: false  // Set this to true to allow multiple files to be selected
                })
                .on('select', function() {
                    
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('.twentysixty_digitizer_login_logo_img')
                        .attr('src', attachment.url)
                        .attr('height', attachment.height/2)
                        .attr('width', attachment.width/2);
                    $('input[name=twentysixty_digitizer_login_logo]').val(attachment.url);
    
                })
                .open();
            });
        });
      </script>

  	<?php  
  }
  
  
  /**
   * Render settings section intro text.
   * 
   * @access public
   * @return void
   */
  public function settings_section_key_callback() {   
  	echo __( 'Enter your 2060 Digital access key to receive updates from 2060 Digital.', $this->plugin_name );  
  }
  
  /**
   * Render settings section intro text.
   * 
   * @access public
   * @return void
   */
  public function settings_section_scripts_callback() {   
  	echo __( 'If you\'d like, you can specify a Google Analytics ID or Google Tag Manager Container ID here to have the appropriate scripts added to the site automatically.' ); 
  	echo '<br>';
  	echo __( 'Note that this will only be a basic implementation; if you need to do any more advanced implementation (e.g. event tracking), you will need a developer to help place the code.', $this->plugin_name );  
  }
  
  
  /**
   * Render settings section intro text.
   * 
   * @access public
   * @return void
   */
  public function settings_section_login_callback() {   
  	echo __( 'Customize the Wordpress login screen here.', $this->plugin_name );  
  }
  
  
  /**
   * Render options page markup.
   * 
   * @access public
   * @return void
   */
  public function options_page() { 
    
    if( function_exists( 'wp_enqueue_media' ) ){
        wp_enqueue_media();
    } else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
    }
      
  	?>
  	<div class="wrap">
  	<form action='options.php' method='post'>
  
  		<h1>2060 Digital Settings</h1>
  
  		<?php
  		settings_fields( 'settingsPage' );
  		do_settings_sections( 'settingsPage' );
      submit_button(); 
      ?>
  
  	</form>
  	</div>
  	<?php
  }
  
  
  /**
   * Make custom image sizes available for selection.
   *
   * These sizes are defined in the main plugin class.
   * 
   * @access public
   * @return void
   */
  public function insert_custom_image_sizes( $sizes ) {
    
    $sizes["thumb_large"] = "Thumbnail (Large)";
    $sizes["600w"] = "600 Wide";
    $sizes["600x300"] = "600 x 300 Cropped";
    $sizes["1440w"] = "1440 Wide";
    $sizes["medium_large"] = "Medium Large";    
    return $sizes;    
  }
  
  
  /**
   * Add custom login message.
   * 
   * @access public
   * @param mixed $message
   * @return void
   */
  public function login_footer() {
    ?>
    <p style="text-align:center;color:#121B35;">
      <img src="<?php echo plugins_url( "/images/webteam-spider.png", __FILE__ ); ?>" srcset="<?php echo plugins_url( "/images/webteam-spider@2x.png", __FILE__ ); ?> 2x" alt="2060 Digital" />
      <br>Powered by <a style="color:#121B35;" href="https://2060digital.com" target="_blank">2060 Digital</a></p>
    <?php
  }
  
  
  /**
   * Keep database/user accounts in sync. 
   * Will often contain hardcoded data to roll out to all sites.
   * 
   * @access public
   * @return void
   */
  public function maybe_run_update() {
     
    $digitizer_version = get_option( "twentysixty-digitizer-version" );  
    
    if ( empty( $digitizer_version ) || version_compare( $this->version, $digitizer_version ) === 1 ) {

      $autoptimize_excluded_scripts = get_option( "autoptimize_js_exclude" );
      $min_jquery_is_not_excluded = strpos( $autoptimize_excluded_scripts, "/js/jquery/jquery.min.js" ) === false;

      if($min_jquery_is_not_excluded) {
        update_option( "autoptimize_js_exclude", $autoptimize_excluded_scripts . ", /js/jquery/jquery.min.js" );
      }

        // keep this empty
        update_option( "autoptimize_cdn_url", "" );
        update_option( "twentysixty-digitizer-version", $this->version );
    }      
  }
}
