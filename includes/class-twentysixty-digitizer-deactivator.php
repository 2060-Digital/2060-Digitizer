<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://2060digital.com
 * @since      1.0.0
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 * @author     Ryan Benhase <rbenhase@2060digital.com>
 */
class Twentysixty_Digitizer_Deactivator {

	/**
	 * Reset editor capabilities on deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
    
    $users = get_users( array( "role__in" => array( "designer", "site_manager" ) ) );    
    foreach( $users as $user ) {
      $user->set_role( 'editor' );
      wp_update_user( $user );
    }
    
    remove_role( 'designer' );
    remove_role( 'site_manager' );
    
	}

}
