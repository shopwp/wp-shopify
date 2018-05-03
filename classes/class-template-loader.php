<?php

namespace WPS;


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists( 'WPS_Gamajo_Template_Loader' ) ) {
  require plugin_dir_path( __FILE__ ) . 'class-template-loader-gamajo.php';
}


/*

Template loader for WP Shopify

*/
if ( !class_exists('Template_Loader') ) {

  class Template_Loader extends \WPS_Gamajo_Template_Loader {


    /*

    Prefix for filter names.

    @since 1.0.0
    @var string

    */
    protected $filter_prefix = 'wps';


    /*

    Directory name where custom templates for this plugin should be found in the theme.

    @since 1.0.0
    @var string

    */
    protected $theme_template_directory = 'wps-templates';


    /*

    Reference to the root directory path of this plugin.

    Can either be a defined constant, or a relative reference from where the subclass lives.

    In this case, `MEAL_PLANNER_PLUGIN_DIR` would be defined in the root plugin file as:

    ~~~
    define( 'MEAL_PLANNER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    ~~~

    @since 1.0.0
    @var string

    */
    protected $plugin_directory = WPS_PLUGIN_DIR;


    /*

    Directory name where templates are found in this plugin.

    Can either be a defined constant, or a relative reference from where the subclass lives.

    e.g. 'templates' or 'includes/templates', etc.

    @since 1.1.0
    @var string

    */
    protected $plugin_template_directory = WPS_RELATIVE_TEMPLATE_DIR;



		/* @if NODE_ENV='free' */
		public function locate_template( $template_names, $load = false, $require_once = true ) {

			// Use $template_names as a cache key - either first element of array or the variable itself if it's a string
			$cache_key = is_array( $template_names ) ? $template_names[0] : $template_names;

			// If the key is in the cache array, we've already located this file.
			if ( isset( $this->template_path_cache[$cache_key] ) ) {

				$located = $this->template_path_cache[$cache_key];

			} else {

				// No file found yet.
				$located = false;

				// Remove empty entries.
				$template_names = array_filter( (array) $template_names );
				$template_paths = $this->get_template_paths();

				// Try to find a template file.
				foreach ( $template_names as $template_name ) {

					// Trim off any slashes from the template name.
					$template_name = ltrim( $template_name, '/' );

					/*

					Only looks inside the plugin folder

					*/
					$newTemplatePaths = [end($template_paths)];


					foreach ( $newTemplatePaths as $template_path ) {

						if ( file_exists( $template_path . $template_name ) ) {

							$located = $template_path . $template_name;
							// Store the template path in the cache
							$this->template_path_cache[$cache_key] = $located;
							break 2;
						}
					}
				}
			}

			if ( $load && $located ) {
				load_template( $located, $require_once );
			}

			return $located;

		}
		/* @endif */


  }

}
