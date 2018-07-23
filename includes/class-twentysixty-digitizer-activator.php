<?php

/**
 * Fired during plugin activation
 *
 * @link       http://2060digital.com
 * @since      1.0.0
 *
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Twentysixty_Digitizer
 * @subpackage Twentysixty_Digitizer/includes
 * @author     Ryan Benhase <rbenhase@2060digital.com>
 */
class Twentysixty_Digitizer_Activator {

	/**
	 * Create new roles and assign capabilities on activation.
	 *
	 * This allows editors to edit theme options, e.g.
	 * to use the WP Theme Customizer.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
  	
		// The base for our new roles
		$editor_role = get_role( 'editor' );
	
		add_role(
			'designer',
			__( 'Designer' ),
			$editor_role->capabilities
		);
	
		add_role(
			'site_manager',
			__( 'Site Manager' ),
			$editor_role->capabilities
		);    
	
		$role_designer = get_role( 'designer' );    
		$role_designer->add_cap( 'edit_theme_options' );
		$role_designer->add_cap( 'gform_full_access' );
		$role_designer->add_cap( 'manage_options' );
		$role_designer->add_cap( 'tablepress_edit_tables' );
		$role_designer->add_cap( 'tablepress_delete_tables' );
		$role_designer->add_cap( 'tablepress_list_tables' );
		$role_designer->add_cap( 'tablepress_add_tables' );
		$role_designer->add_cap( 'tablepress_copy_tables' );
		$role_designer->add_cap( 'tablepress_import_tables' );
		$role_designer->add_cap( 'tablepress_export_tables' );
		$role_designer->add_cap( 'tablepress_access_options_screen' );
		$role_designer->add_cap( 'tablepress_access_about_screen' );
	
		$role_site_manager = get_role( 'site_manager' );    
		$role_site_manager->add_cap( 'edit_theme_options' );
		$role_site_manager->add_cap( 'gform_full_access' );
		$role_site_manager->add_cap( 'manage_options' );       
		$role_site_manager->add_cap( 'tablepress_edit_tables' );
		$role_site_manager->add_cap( 'tablepress_delete_tables' );
		$role_site_manager->add_cap( 'tablepress_list_tables' );
		$role_site_manager->add_cap( 'tablepress_add_tables' );
		$role_site_manager->add_cap( 'tablepress_copy_tables' );
		$role_site_manager->add_cap( 'tablepress_import_tables' );
		$role_site_manager->add_cap( 'tablepress_export_tables' );
		$role_site_manager->add_cap( 'tablepress_access_options_screen' );
		$role_site_manager->add_cap( 'tablepress_access_about_screen' );
	}
}
