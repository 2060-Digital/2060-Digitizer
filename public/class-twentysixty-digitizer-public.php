<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://2060digital.com
 * @since      1.0.0
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/public
 * @author     Ryan Benhase <rbenhase@2060digital.com>
 */
class Twentysixty_Digitizer_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	  
  /**
   * Cleanup <head>.
   * 
   * @access public
   * @return void
   */
  public function head_cleanup() {
  	// category feeds
  	remove_action( 'wp_head', 'feed_links_extra', 3 );
  	// post and comment feeds
  	remove_action( 'wp_head', 'feed_links', 2 );
  	// EditURI link
  	remove_action( 'wp_head', 'rsd_link' );
  	// windows live writer
  	remove_action( 'wp_head', 'wlwmanifest_link' );
  	// previous link
  	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  	// start link
  	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  	// links for adjacent posts
  	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  	// WP version
  	remove_action( 'wp_head', 'wp_generator' );
  	// remove WP version from css
  	add_filter( 'style_loader_src', array( $this, 'remove_wp_ver_css_js' ), 9999 );
  	// remove Wp version from scripts
  	add_filter( 'script_loader_src', array( $this, 'remove_wp_ver_css_js' ), 9999 );
  
  } /* end twentysixty head cleanup */
  

  /**
   * Remove WP Version from RSS.
   * 
   * @access public
   * @return String An empty string
   */
  public function rss_version() { return ''; }
  
 
  /**
   * remove WP version from scripts.
   * 
   * @access public
   * @param mixed $src
   * @return String $src The filtered src
   */
  public function remove_wp_ver_css_js( $src ) {
  	if ( strpos( $src, 'ver=' ) )
  		$src = remove_query_arg( 'ver', $src );
  	return $src;
  }
  
  /**
   * Remove injected CSS for recent comments widget
   * 
   * @access public
   * @return void
   */
  public function remove_wp_widget_recent_comments_style() {
  	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
  		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  	}
  }
  

  /**
   * Remove injected CSS from recent comments widget.
   * 
   * @access public
   * @return void
   */
  public function remove_recent_comments_style() {
  	global $wp_widget_factory;
  	if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
  		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
  	}
  }

  /**
   * Add dynamically-specified Google Tag Manager container to the head.
   * 
   * @access public
   * @param Int $id The GTM Container ID
   * @return void
   */
  public function dynamic_gtm_container_head( $id ) {
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo $id; ?>');</script>
    <!-- End Google Tag Manager -->
    <?php
  }
  
  /**
   * Add dynamically-specified Google Tag Manager container (noscript fallback) to the body element.
   * 
   * @access public
   * @param Int $id The GTM Container ID
   * @return void
   */
  public function dynamic_gtm_container_body( $id ) {
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $id; ?>"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
  }
  
  /**
   * Add dynamically-specified Google Analytics tracking script to the head.
   * 
   * @access public
   * @param Int $id The Analytics Tracking ID
   * @return void
   */
  public function dynamic_analytics_head( $id ) {
    ?>
    <!-- Google Analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    
      ga('create', '<?php echo $id; ?>', 'auto');
      ga('send', 'pageview');
        
    </script>
    <!-- End Google Analytics -->
    <?php
  }

}
