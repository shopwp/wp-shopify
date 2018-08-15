<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Admin_Menus') ) {

	class Admin_Menus {

		private $Template_Loader;
		private $DB_Collections;
		private $DB_Products;
		private $DB_Tags;
		private $DB_Collects;


	  /*

	  Initialize the class and set its properties.

	  */
	  public function __construct($Template_Loader, $DB_Collections, $DB_Products, $DB_Tags, $DB_Collects) {

			$this->Template_Loader 		= $Template_Loader;
			$this->DB_Collections 		= $DB_Collections;
			$this->DB_Products 				= $DB_Products;
			$this->DB_Tags 						= $DB_Tags;
			$this->DB_Collects 				= $DB_Collects;

	  }


		/*

		Add posts meta boxes to Products screens

		*/
		public function add_posts_meta_boxes() {

			// Collections
	    add_meta_box(
	      'wps_products_meta_box_collections',
	      __('Collections', WPS_TEXT_DOMAIN),
	      [ $this, 'products_meta_box_collections' ],
	      WPS_PRODUCTS_POST_TYPE_SLUG,
	      'side',
	      'low'
	    );

			// Tags
			add_meta_box(
	      'wps_products_meta_box_tags',
	      __('Tags', WPS_TEXT_DOMAIN),
	      [ $this, 'wps_products_meta_box_tags' ],
	      WPS_PRODUCTS_POST_TYPE_SLUG,
	      'side',
	      'low'
	    );

	  }


		/*

		Products meta box: Collections

		TODO: Need to check for empty $product here. Perhaps show a notice message if true

		*/
		public function products_meta_box_collections($post) {

			$collections = $this->DB_Collections->get_collections();
			$name = '';

			$product = $this->DB_Products->get_product_from_post_id($post->ID);

			if (Utils::has($product, 'product_id')) {
				$collects = $this->DB_Collects->get_collects_by_product_id($product->product_id); ?>

				<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">

				<?php foreach ( $collections as $collection ) {

					$selectedCollection = in_array($collection->collection_id, array_column($collects, 'collection_id'));

					?>

					<li id="category-<?= $collection->collection_id; ?>" class="popular-category">
						<label class="selectit" for="collection-<?= $collection->collection_id; ?>">
							<input <?= $selectedCollection ? 'checked="checked"' : ''; ?> value="16" type="checkbox" name="collections[<?= $collection->collection_id; ?>]" id="collection-<?= $collection->collection_id; ?>"> <?php esc_html_e( $collection->title ); ?>
						</label>
					</li>

				<?php } ?>

				</ul>

			<?php } else { ?>
				<span>No product found. Try clearing the WP Shopify cache.</span>
			<?php } ?>

		<?php }


		/*

		Products meta box: Tags

		*/
		public function wps_products_meta_box_tags($post) {

			$allTags = $this->DB_Tags->get_unique_tags();
			$currentTags = $this->DB_Tags->get_tags_from_post_id($post->ID);

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
	  public function add_nav_menu_meta_boxes() {

	    add_meta_box(
	      'wps_nav_cart_icon',
	      __('Cart', WPS_TEXT_DOMAIN),
	      [ $this, 'nav_menu_link' ],
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
	  public function walker_nav_menu_start_el_callback($item_output, $item) {

	    if ($item->description === 'WP Shopify Cart Icon') {

				// If we add additional wps_cart attributes we need to add the defaults here as well
				$data = [
					'counter' => true
				];

				ob_start();
				$this->Template_Loader->set_template_data($data)->get_template_part( 'partials/cart/cart-icon', 'wrapper' );
				$item_output = ob_get_contents();
				ob_end_clean();

				return $item_output;

	    }



	    return $item_output;

	  }


		/*

		Replaces the custom menu icon with our cart icon

		*/
	  public function add_custom_nav_fields( $menu_item ) {

	    if ($menu_item->description === 'WP Shopify Cart Icon') {
	      $menu_item->wp_shopify_cart_icon = true;
	    }

	    return $menu_item;

	  }


		/*

		Hooks

		*/
		public function hooks() {

			add_filter('wp_setup_nav_menu_item', [$this, 'add_custom_nav_fields'] );
			add_action('admin_init', [$this, 'add_nav_menu_meta_boxes']);
			add_action('admin_init', [$this, 'add_posts_meta_boxes']);
			add_filter('walker_nav_menu_start_el', [$this, 'walker_nav_menu_start_el_callback'], 10, 2);

		}


		/*

	  Init

	  */
		public function init() {
	    $this->hooks();
		}


	}

}
