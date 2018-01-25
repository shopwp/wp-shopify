<?php

namespace WPS;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Class Admin_Menus

*/
class Admin_Menus {

  protected static $instantiated = null;
  private $Config;

  /*

  Initialize the class and set its properties.

  */
  public function __construct($Config) {
    $this->config = $Config;
  }


  /*

  Creates a new class if one hasn't already been created.
  Ensures only one instance is used.

  */
  public static function instance($Config) {

    if (is_null(self::$instantiated)) {
      self::$instantiated = new self($Config);
    }

    return self::$instantiated;

  }


  /*

  Add nav menu meta box

  */
  public function add_nav_menu_meta_boxes() {

    add_meta_box(
      'wl_login_nav_link',
      __('Cart', 'wp-shopify'),
      array( $this, 'nav_menu_link'),
      'nav-menus',
      'side',
      'low',
      [
        'test' => 'wee'
      ]
    );

  }


  /*

  Add nav menu link

  */
  public function nav_menu_link($wee, $test) {

    error_log('---- $test -----');
    error_log(print_r($test, true));
    error_log('---- /$test -----');

    ?>

    <div id="posttype-wl-login" class="posttypediv">

      <div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
        <ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
          <li>
            <label class="menu-item-title">
              <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Cart Icon
            </label>

            <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
            <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Cart Icon">
            <input type="hidden" class="menu-item-description menu-item-description-okokok" name="menu-item[-1][menu-item-description]" value="WP Shopify Cart Icon">
          </li>
        </ul>
      </div>

      <p class="button-controls">
        <span class="list-controls">
          <a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#posttype-page" class="select-all">Select All</a>
        </span>
        <span class="add-to-menu">
          <input type="submit" class="button-secondary submit-add-to-menu right wps-submit-menu-cart-icon" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-wl-login">
          <span class="spinner"></span>
        </span>
      </p>

    </div>

  <?php }













  public function walker_nav_menu_start_el_callback($item_output, $item) {

  	error_log('---- $item -----');
  	error_log(print_r($item, true));
  	error_log('---- /$item -----');

    // //   var_dump($item_output, $item);
    if ($item->description === 'WP Shopify Cart Icon') {

  		error_log('---- FOUND IT -----');

      ob_start();
			include($this->config->plugin_path . "public/partials/cart/button.php");
			$item_output = ob_get_contents();
			ob_end_clean();

  		return $item_output;

    }

    return $item_output;

  }







  public function rc_scm_add_custom_nav_fields( $menu_item ) {

    if ($menu_item->description === 'WP Shopify Cart Icon') {
      $menu_item->wp_shopify_cart_icon = true;
    }

    return $menu_item;

  }




  public function filter_function_name( $atts, $item, $args ) {

    error_log('---- $atts -----');
    error_log(print_r($atts, true));
    error_log('---- /$atts -----');

    // Manipulate attributes
    return $atts;

  }


	/*

  Init

  */
	public function init() {

    add_filter('wp_setup_nav_menu_item', [$this, 'rc_scm_add_custom_nav_fields'] );
    add_action('admin_init', [$this, 'add_nav_menu_meta_boxes']);
    add_filter('walker_nav_menu_start_el', [$this, 'walker_nav_menu_start_el_callback'], 10, 2);

    add_filter( 'nav_menu_link_attributes', [$this, 'filter_function_name'], 10, 3 );


	}








}
