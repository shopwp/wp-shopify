<?php

namespace WPS;

use WPS\Template_Loader;
use WPS\DB;
use WPS\DB\Products;
use WPS\DB\Tags;
use WPS\Utils;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Class Admin_Menus

*/
if ( !class_exists('Admin_Menus') ) {

	class Admin_Menus {

	  protected static $instantiated = null;
	  private $Config;
		public $DB;

	  /*

	  Initialize the class and set its properties.

	  */
	  public function __construct($Config) {
	    $this->config = $Config;
			$this->template_loader = new Template_Loader;
			$this->DB = new DB();
			$this->Products = new Products();
			$this->Tags = new Tags();
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

		Add posts meta boxes

		*/
		public function wps_add_posts_meta_boxes() {

			// Collections
	    add_meta_box(
	      'wps_products_meta_box_collections',
	      __('Collections', 'wp-shopify'),
	      [ $this, 'products_meta_box_collections' ],
	      'wps_products',
	      'side',
	      'low'
	    );

			// Tags
			add_meta_box(
	      'wps_products_meta_box_tags',
	      __('Tags', 'wp-shopify'),
	      [ $this, 'wps_products_meta_box_tags' ],
	      'wps_products',
	      'side',
	      'low'
	    );

	  }


		/*

		Products meta box: Collections

		*/
		public function products_meta_box_collections($post) {

			$collections = $this->DB->get_collections();
			$name = '';

			$product = $this->Products->get_product($post->ID);
			$collectionsCurrent = $this->DB->get_collections_by_product_id($product->product_id);

			?>

			<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">

			<?php foreach ( $collections as $collection ) {

				$selectedCollection = in_array($collection->collection_id, array_column($collectionsCurrent, 'collection_id'));

				?>

				<li id="category-<?= $collection->collection_id; ?>" class="popular-category">
					<label class="selectit" for="collection-<?= $collection->collection_id; ?>">
						<input <?= $selectedCollection ? 'checked="checked"' : ''; ?> value="16" type="checkbox" name="collections[<?= $collection->collection_id; ?>]" id="collection-<?= $collection->collection_id; ?>"> <?php esc_html_e( $collection->title ); ?>
					</label>
				</li>

			<?php } ?>

			</ul>

		<?php }


		/*

		Products meta box: Tags

		*/
		public function wps_products_meta_box_tags($post) {

			$allTags = $this->Tags->get_unique_tags();
			$currentTags = $this->Tags->get_product_tags($post->ID);

			?>

			<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">

			<?php foreach ($allTags as $tag) {

				$selectedTag = in_array($tag, array_column($currentTags, 'tag'));

				?>

				<li id="tag-wrapper-<?= $tag; ?>" class="popular-tag">
					<label class="selectit" for="tag-<?= $tag; ?>">
						<input <?= $selectedTag ? 'checked="checked"' : ''; ?> value="16" type="checkbox" name="tags[<?= $tag; ?>]" id="tag-<?= $tag; ?>"> <?php esc_html_e( $tag ); ?>
					</label>
				</li>

			<?php } ?>

			</ul>

		<?php }


	  /*

	  Add nav menu meta box

	  */
	  public function wps_add_nav_menu_meta_boxes() {

	    add_meta_box(
	      'wps_nav_cart_icon',
	      __('Cart', 'wp-shopify'),
	      array( $this, 'nav_menu_link'),
	      'nav-menus',
	      'side',
	      'low'
	    );

	  }


	  /*

	  Add nav menu link

	  */
	  public function nav_menu_link($wee, $test) { ?>

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


		/*

		Replaces the custom menu icon with our cart icon

		*/
	  public function wps_walker_nav_menu_start_el_callback($item_output, $item) {

	    if ($item->description === 'WP Shopify Cart Icon') {

				// If we add additional wps_cart attributes we need to add the defaults here as well
				$data = [
					'counter' => true
				];

				ob_start();
				$this->template_loader->set_template_data($data)->get_template_part( 'partials/cart/cart-icon', 'wrapper' );
				$item_output = ob_get_contents();
				ob_end_clean();

				return $item_output;

	    }



	    return $item_output;

	  }


		/*

		Replaces the custom menu icon with our cart icon

		*/
	  public function wps_add_custom_nav_fields( $menu_item ) {

	    if ($menu_item->description === 'WP Shopify Cart Icon') {
	      $menu_item->wp_shopify_cart_icon = true;
	    }

	    return $menu_item;

	  }


		/*

	  Init

	  */
		public function init() {

	    add_filter('wp_setup_nav_menu_item', [$this, 'wps_add_custom_nav_fields'] );
	    add_action('admin_init', [$this, 'wps_add_nav_menu_meta_boxes']);

			add_action('admin_init', [$this, 'wps_add_posts_meta_boxes']);

	    add_filter('walker_nav_menu_start_el', [$this, 'wps_walker_nav_menu_start_el_callback'], 10, 2);


		}


	}

}
