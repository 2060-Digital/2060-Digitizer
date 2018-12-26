<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://2060digital.com
 * @since      1.0.0
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 * @author     Ryan Benhase <rbenhase@2060digital.com>
 */
class Twentysixty_Digitizer {

	/**
	 * The main plugin file path, used for plugin updates.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      String    $main_file    Main plugin file path
	 */
	public $main_file;
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Twentysixty_Digitizer_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $main_file ) {

  	$this->main_file = $main_file;
		$this->plugin_name = 'twentysixty-digitizer';
		$this->version = '1.1.9';

		$this->load_dependencies();
		$this->set_locale();
		$this->updater_init();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Twentysixty_Digitizer_Loader. Orchestrates the hooks of the plugin.
	 * - Twentysixty_Digitizer_i18n. Defines internationalization functionality.
	 * - Twentysixty_Digitizer_Admin. Defines all hooks for the admin area.
	 * - Twentysixty_Digitizer_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-twentysixty-digitizer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-twentysixty-digitizer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-twentysixty-digitizer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-twentysixty-digitizer-public.php';
		
		/**
		 * The class responsible for checking for plugin updates.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/plugin-update-checker/plugin-update-checker.php';


		$this->loader = new Twentysixty_Digitizer_Loader();

	}
	
	/**
	 * Initialize the plugin updater class
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function updater_init() {

		$className = PucFactory::getLatestClassVersion( 'PucGitHubChecker' );
		$myUpdateChecker = new $className(
			'https://github.com/2060digital/2060-digitizer/',
			$this->main_file,
			'master'
		);
		
		$access_key = get_option( 'twentysixty_digitizer_remote_access_token' );
		
		if ( !empty( $access_key ) ) {
  		$myUpdateChecker->setAccessToken( $access_key );
		}	else {
  		$this->loader->add_action( "admin_notices", $this, "display_access_key_notice" );
		}
	}
	
	
	
	/**
	 * Render an access key notice for administrators.
	 * 
	 * @access public
	 * @return void
	 */
	public function display_access_key_notice() {
  	
  	if ( current_user_can( "manage_options" ) ):	?>
  	<div id="setting-error-settings_updated" class="error settings-error notice is-dismissible"> 
      <p>
        <strong><?php _e( "Enter your 2060 Digital access key to receive important updates.", $this->plugin_name ); ?>
        <a href="<?php echo admin_url( '/options-general.php?page=twentysixty_digitizer' ); ?>"><?php _e( "Take me there!", $this->plugin_name ); ?></a>
        </strong>
      </p>
      <button type="button" class="notice-dismiss">
        <span class="screen-reader-text"><?php _e( "Dismiss this notice.", $this->plugin_name ); ?></span>
      </button>
    </div>
    <?php endif; 
	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Twentysixty_Digitizer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Twentysixty_Digitizer_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Twentysixty_Digitizer_Admin( $this->get_plugin_name(), $this->get_version() );
		
		// If plugin has been updated, run any upgrades or one-time scripts
		$this->loader->add_action( 'init', $plugin_admin, 'maybe_run_update' );
	
		// Add settings page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );
	
		// Login page
		$this->loader->add_action( 'login_footer', $plugin_admin, 'login_footer' );

		// Enqueue login styles
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_admin, 'login_styles' );
	
		// Enqueue admin scripts and styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts_styles' );
	
		// Disable default dashboard widgets
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'disable_default_dashboard_widgets' );   
	
		// Customize login page
		$this->loader->add_filter( 'login_headerurl', $plugin_admin, 'login_url' );
		$this->loader->add_filter( 'login_headertitle', $plugin_admin, 'login_title' );
	
		// Customize admin footer text
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'custom_admin_footer' );
		
		// Add additional image sizes and make them available
		add_image_size( 'thumb_large', 300, 300, true );
		add_image_size( '600w', 600, 99999 );
		add_image_size( '600x300', 600, 300, true );
		add_image_size( '1440w', 1440, 99999 );
		$this->loader->add_filter( 'image_size_names_choose', $plugin_admin, 'insert_custom_image_sizes' );
		
		// Automatic updates for plugins & themes
		add_filter( 'auto_update_plugin', '__return_true' );
		add_filter( 'auto_update_theme', '__return_true' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Twentysixty_Digitizer_Public( $this->get_plugin_name(), $this->get_version() );
    
		// Clean up comment styles in the head
		$this->loader->add_action( 'wp_head', $plugin_public, 'remove_recent_comments_style', 1 );
	
		// Clean up head
		$this->loader->add_action( 'init', $plugin_public, 'head_cleanup' );
	
		$stored_scripts = get_option( 'twentysixty_digitizer_scripts' );
	
		if ( !empty( $stored_scripts ) ) {
		  if ( !empty( $stored_scripts["gtm"] ) ) {

			$gtm_id = $stored_scripts["gtm"];
		
			// Use a closure (PHP 5.3+) to pass our variable to the wp_head action
			add_action( 'wp_head', function() use ( $gtm_id, $plugin_public ) {
			  $plugin_public->dynamic_gtm_container_head( $gtm_id );
			} );    
		
			// Try to place the noscript fallback as high in the body element as possible.
			// This will unfortunately not work on non-BB-theme sites.
			add_action( 'fl_body_open', function() use ( $gtm_id, $plugin_public ) {
			  $plugin_public->dynamic_gtm_container_body( $gtm_id );
			} );  
		  }  
	  
		  // Add Analytics if ID exists
		  if ( !empty( $stored_scripts["analytics"] ) ) {
		
			$analytics_id = $stored_scripts["analytics"];
		
			// Use a closure (PHP 5.3+) to pass our variable to the wp_head action
			add_action( 'wp_head', function() use ( $analytics_id, $plugin_public ) {
			  $plugin_public->dynamic_analytics_head( $analytics_id );
			} );        
		  }    
	  
				// Add Analytics if ID exists
		  if ( !empty( $stored_scripts["fb"] ) ) {
		
			$fb_id = $stored_scripts["fb"];
		
			// Use a closure (PHP 5.3+) to pass our variable to the wp_head action
			add_action( 'wp_head', function() use ( $fb_id, $plugin_public ) {
			  $plugin_public->dynamic_fb_head( $fb_id );
			} );        
		  }   
		}
	
	
		// Remove WP version from RSS
		$this->loader->add_filter( 'the_generator', $plugin_public, 'rss_version' );
	
		// Remove injected css for recent comments widget
		$this->loader->add_filter( 'wp_head', $plugin_public, 'remove_wp_widget_recent_comments_style', 1 );    
		
		// Filter robots.txt
		$this->loader->add_filter( 'robots_txt', $plugin_public, 'filter_robots_txt', 10, 2 ); 


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Twentysixty_Digitizer_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
