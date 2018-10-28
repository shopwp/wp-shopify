<?php

namespace WPS\WS;

use WPS\Transients;
use WPS\Messages;
use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Settings_General extends \WPS\WS {

	protected $DB_Settings_General;
	protected $DB_Shop;
	protected $DB_Collections;


	public function __construct($DB_Settings_General, $DB_Shop, $DB_Collections) {

		$this->DB_Settings_General		= $DB_Settings_General;
		$this->DB_Shop								= $DB_Shop;
		$this->DB_Collections					= $DB_Collections;

	}


	/*

	Update Settings General

	*/
	public function update_settings_general() {


		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (update_settings_general)' );
		}

		if (!isset($_POST['data'])) {
			$this->send_success();
		}


		$form_data = $_POST['data'];

		$newGeneralSettings = [];

		if (isset($form_data['wps_settings_general_products_url']) && $form_data['wps_settings_general_products_url']) {
			$newGeneralSettings['url_products'] = $form_data['wps_settings_general_products_url'];
		}

		if (isset($form_data['wps_settings_general_collections_url']) && $form_data['wps_settings_general_collections_url']) {
			$newGeneralSettings['url_collections'] = $form_data['wps_settings_general_collections_url'];
		}


		if (isset($form_data['wps_settings_general_num_posts'])) {

			if ($form_data['wps_settings_general_num_posts']) {
				$newGeneralSettings['num_posts'] = $form_data['wps_settings_general_num_posts'];

			} else {
				$newGeneralSettings['num_posts'] = null;

			}

		}

		if (isset($form_data['wps_settings_general_products_link_to_shopify'])) {
			$newGeneralSettings['products_link_to_shopify'] = (int)$form_data['wps_settings_general_products_link_to_shopify'];
		}

		if (isset($form_data['wps_settings_general_show_breadcrumbs'])) {
			$newGeneralSettings['show_breadcrumbs'] = (int)$form_data['wps_settings_general_show_breadcrumbs'];
		}

		if (isset($form_data['wps_settings_general_hide_pagination'])) {
			$newGeneralSettings['hide_pagination'] = (int)$form_data['wps_settings_general_hide_pagination'];
		}


		if (isset($form_data['wps_settings_general_styles_all'])) {
			$newGeneralSettings['styles_all'] = (int)$form_data['wps_settings_general_styles_all'];
		}

		if (isset($form_data['wps_settings_general_styles_core'])) {
			$newGeneralSettings['styles_core'] = (int)$form_data['wps_settings_general_styles_core'];
		}

		if (isset($form_data['wps_settings_general_styles_grid'])) {
			$newGeneralSettings['styles_grid'] = (int)$form_data['wps_settings_general_styles_grid'];
		}

		if (isset($form_data['wps_settings_general_price_with_currency'])) {
			$newGeneralSettings['price_with_currency'] = (int)$form_data['wps_settings_general_price_with_currency'];
		}

		if (isset($form_data['wps_settings_general_cart_loaded'])) {
			$newGeneralSettings['cart_loaded'] = (int)$form_data['wps_settings_general_cart_loaded'];
		}

		if (isset($form_data['wps_settings_general_enable_beta'])) {
			$newGeneralSettings['enable_beta'] = (int)$form_data['wps_settings_general_enable_beta'];
		}

		if (isset($form_data['wps_settings_general_enable_cart_terms'])) {
			$newGeneralSettings['enable_cart_terms'] = (int)$form_data['wps_settings_general_enable_cart_terms'];
		}

		if (isset($form_data['wps_settings_general_cart_terms_content'])) {

			$terms_string = (string) $form_data['wps_settings_general_cart_terms_content'];

			$newGeneralSettings['cart_terms_content'] = wp_kses($terms_string, [
				'strong' => [],
				'b' => [],
				'i' => [],
				'em' => [],
				'a' => [
					'href' => [],
					'title' => [],
					'target' => []
				]
			]);

		}

		if (isset($form_data['wps_settings_general_sync_by_collections'])) {
			$newGeneralSettings['sync_by_collections'] = maybe_serialize($form_data['wps_settings_general_sync_by_collections']);
		}

		if (isset($form_data['wps_settings_general_save_connection_only'])) {
			$newGeneralSettings['save_connection_only'] = (int)$form_data['wps_settings_general_save_connection_only'];
		}

		// Always 1 if free version
		if (isset($form_data['wps_settings_general_selective_sync_all'])) {
			$newGeneralSettings['selective_sync_all'] = (int)$form_data['wps_settings_general_selective_sync_all'];

		} else {
			$newGeneralSettings['selective_sync_all'] = 1;
		}


		if (isset($form_data['wps_settings_general_selective_sync_products'])) {
			$newGeneralSettings['selective_sync_products'] = (int)$form_data['wps_settings_general_selective_sync_products'];
		}

		if (isset($form_data['wps_settings_general_selective_sync_collections'])) {
			$newGeneralSettings['selective_sync_collections'] = (int)$form_data['wps_settings_general_selective_sync_collections'];
		}


		if (isset($form_data['wps_settings_general_selective_sync_tags'])) {
			$newGeneralSettings['selective_sync_tags'] = (int)$form_data['wps_settings_general_selective_sync_tags'];
		}

		if (isset($form_data['wps_settings_general_selective_sync_images'])) {
			$newGeneralSettings['selective_sync_images'] = (int)$form_data['wps_settings_general_selective_sync_images'];
		}

		if (isset($form_data['wps_settings_general_selective_sync_shop'])) {
			$newGeneralSettings['selective_sync_shop'] = 1;
		}

		if (isset($form_data['wps_settings_general_related_products_show'])) {
			$newGeneralSettings['related_products_show'] = (int)$form_data['wps_settings_general_related_products_show'];
		}

		if (isset($form_data['wps_settings_general_related_products_sort'])) {
			$newGeneralSettings['related_products_sort'] = $form_data['wps_settings_general_related_products_sort'];
		}

		if (isset($form_data['wps_settings_general_related_products_amount'])) {
			$newGeneralSettings['related_products_amount'] = (int)$form_data['wps_settings_general_related_products_amount'];
		}

		if (isset($form_data['wps_settings_general_items_per_request'])) {
			$newGeneralSettings['items_per_request'] = (int)$form_data['wps_settings_general_items_per_request'];
		}

		if (isset($form_data['wps_settings_general_add_to_cart_color'])) {
			$newGeneralSettings['add_to_cart_color'] = (string) $form_data['wps_settings_general_add_to_cart_color'];
		}

		if (isset($form_data['wps_settings_general_variant_color'])) {
			$newGeneralSettings['variant_color'] = (string) $form_data['wps_settings_general_variant_color'];
		}

		if (isset($form_data['wps_settings_general_checkout_button_color'])) {
			$newGeneralSettings['checkout_color'] = (string) $form_data['wps_settings_general_checkout_button_color'];
		}

		if (isset($form_data['wps_settings_general_cart_icon_color'])) {
			$newGeneralSettings['cart_icon_color'] = (string) $form_data['wps_settings_general_cart_icon_color'];
		}

		if (isset($form_data['wps_settings_general_cart_counter_color'])) {
			$newGeneralSettings['cart_counter_color'] = (string) $form_data['wps_settings_general_cart_counter_color'];
		}


		if (isset($form_data['wps_settings_general_products_heading_toggle'])) {

			if ($form_data['wps_settings_general_products_heading_toggle'] === 'false') {
				$newGeneralSettings['products_heading_toggle'] = 0;

			} else {
				$newGeneralSettings['products_heading_toggle'] = 1;
			}

		}

		if (isset($form_data['wps_settings_general_collections_heading_toggle'])) {

			if ($form_data['wps_settings_general_collections_heading_toggle'] === 'false') {
				$newGeneralSettings['collections_heading_toggle'] = 0;

			} else {
				$newGeneralSettings['collections_heading_toggle'] = 1;
			}

		}

		if (isset($form_data['wps_settings_general_related_products_heading_toggle'])) {

			if ($form_data['wps_settings_general_related_products_heading_toggle'] === 'false') {
				$newGeneralSettings['related_products_heading_toggle'] = 0;

			} else {
				$newGeneralSettings['related_products_heading_toggle'] = 1;
			}

		}




		if (isset($form_data['wps_settings_products_images_sizing_toggle'])) {

			if ($form_data['wps_settings_products_images_sizing_toggle'] === 'false') {
				$newGeneralSettings['products_images_sizing_toggle'] = 0;

			} else {
				$newGeneralSettings['products_images_sizing_toggle'] = 1;
			}

		}

		if (isset($form_data['wps_settings_products_images_sizing_width'])) {
			$newGeneralSettings['products_images_sizing_width'] = (int) $form_data['wps_settings_products_images_sizing_width'];
		}

		if (isset($form_data['wps_settings_products_images_sizing_height'])) {
			$newGeneralSettings['products_images_sizing_height'] = (int) $form_data['wps_settings_products_images_sizing_height'];
		}


		if (isset($form_data['wps_settings_products_images_sizing_crop'])) {
			$newGeneralSettings['products_images_sizing_crop'] = (string) $form_data['wps_settings_products_images_sizing_crop'];
		}

		if (isset($form_data['wps_settings_products_images_sizing_scale'])) {
			$newGeneralSettings['products_images_sizing_scale'] = (string) $form_data['wps_settings_products_images_sizing_scale'];
		}




		if (isset($form_data['wps_settings_collections_images_sizing_toggle'])) {

			if ($form_data['wps_settings_collections_images_sizing_toggle'] === 'false') {
				$newGeneralSettings['collections_images_sizing_toggle'] = 0;

			} else {
				$newGeneralSettings['collections_images_sizing_toggle'] = 1;
			}

		}

		if (isset($form_data['wps_settings_collections_images_sizing_width'])) {
			$newGeneralSettings['collections_images_sizing_width'] = (int) $form_data['wps_settings_collections_images_sizing_width'];
		}

		if (isset($form_data['wps_settings_collections_images_sizing_height'])) {
			$newGeneralSettings['collections_images_sizing_height'] = (int) $form_data['wps_settings_collections_images_sizing_height'];
		}


		if (isset($form_data['wps_settings_collections_images_sizing_crop'])) {
			$newGeneralSettings['collections_images_sizing_crop'] = (string) $form_data['wps_settings_collections_images_sizing_crop'];
		}

		if (isset($form_data['wps_settings_collections_images_sizing_scale'])) {
			$newGeneralSettings['collections_images_sizing_scale'] = (string) $form_data['wps_settings_collections_images_sizing_scale'];
		}




		if (isset($form_data['wps_settings_related_products_images_sizing_toggle'])) {

			if ($form_data['wps_settings_related_products_images_sizing_toggle'] === 'false') {
				$newGeneralSettings['related_products_images_sizing_toggle'] = 0;

			} else {
				$newGeneralSettings['related_products_images_sizing_toggle'] = 1;
			}

		}

		if (isset($form_data['wps_settings_related_products_images_sizing_width'])) {
			$newGeneralSettings['related_products_images_sizing_width'] = (int) $form_data['wps_settings_related_products_images_sizing_width'];
		}

		if (isset($form_data['wps_settings_related_products_images_sizing_height'])) {
			$newGeneralSettings['related_products_images_sizing_height'] = (int) $form_data['wps_settings_related_products_images_sizing_height'];
		}


		if (isset($form_data['wps_settings_related_products_images_sizing_crop'])) {
			$newGeneralSettings['related_products_images_sizing_crop'] = (string) $form_data['wps_settings_related_products_images_sizing_crop'];
		}

		if (isset($form_data['wps_settings_related_products_images_sizing_scale'])) {
			$newGeneralSettings['related_products_images_sizing_scale'] = (string) $form_data['wps_settings_related_products_images_sizing_scale'];
		}










		/*

		If user keeps all selective sync fields empty, default to sync all.
		TODO: Handle on front-end instead

		*/
		if ($newGeneralSettings['selective_sync_all'] === 0 && $newGeneralSettings['selective_sync_products'] === 0 && $newGeneralSettings['selective_sync_collections'] === 0 && $newGeneralSettings['selective_sync_customers'] === 0 && $newGeneralSettings['selective_sync_orders'] === 0) {
			$newGeneralSettings['selective_sync_all'] = 1;
		}


		/*

		If user keeps all stylesheet fields empty, default to all styles.
		TODO: Handle on front-end instead

		*/

		if ($newGeneralSettings['styles_all'] === 0 && $newGeneralSettings['styles_core'] === 0 && $newGeneralSettings['styles_grid'] === 0) {
			$newGeneralSettings['styles_all'] = 1;
		}


		$results = $this->DB_Settings_General->update_general($newGeneralSettings);

		Transients::delete_cached_settings();
		Transients::delete_cached_prices();

		if (is_wp_error($results)) {
			$this->send_error( $results->get_error_message() );

		} else {
			update_site_option('wps_settings_updated', $newGeneralSettings);
			$this->send_success($results);
		}


	}


	/*

	Resets rewrite rules when settings form is saved

	*/
	public function reset_rewrite_rules($old_value, $new_value) {
		update_option('rewrite_rules', '');
	}


	/*

	Get plugin setting for currency symbol toggle

	*/
	public function get_currency_format() {

		if (!Utils::valid_frontend_nonce($_GET['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_currency_format)' );
		}


		$price_with_currency = $this->get_column_single('price_with_currency');

		if ( Utils::array_not_empty($price_with_currency) && isset($price_with_currency[0]->price_with_currency) ) {
			$this->send_success($price_with_currency[0]->price_with_currency);

		} else {
			$this->send_error( Messages::get('products_curency_format_not_found') . ' (get_currency_format)' );
		}

	}


	/*

	Get plugin setting for currency formats

	*/
	public function get_currency_formats() {

		if (!Utils::valid_frontend_nonce($_GET['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_currency_formats)' );
		}

		$price_with_currency = $this->DB_Settings_General->get_column_single('price_with_currency');
		$moneyFormat = $this->DB_Shop->get_shop('money_format');
		$moneyFormatWithCurrency = $this->DB_Shop->get_shop('money_with_currency_format');


		if ( Utils::array_not_empty($price_with_currency) && isset($price_with_currency[0]->price_with_currency) ) {
			$price_with_currency = $price_with_currency[0]->price_with_currency;

		} else {
			$price_with_currency = false;
		}


		if (isset($moneyFormat[0]) && $moneyFormat[0]->money_format) {
			$moneyFormat = (string)$moneyFormat[0]->money_format;

		} else {
			$moneyFormat = false;
		}


		if (isset($moneyFormatWithCurrency[0]) && $moneyFormatWithCurrency[0]->money_with_currency_format) {
			$moneyFormatWithCurrency = (string)$moneyFormatWithCurrency[0]->money_with_currency_format;

		} else {
			$moneyFormatWithCurrency = false;
		}


		$this->send_success([
			'priceWithCurrency'	=>	$price_with_currency,
			'moneyFormat'	=>	$moneyFormat,
			'moneyFormatWithCurrency'	=>	$moneyFormatWithCurrency
		]);


	}


	/*

	Get plugin setting for currency symbol toggle

	*/
	public function has_money_format_changed() {

		if (!Utils::valid_frontend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (has_money_format_changed)' );
		}


		$current_money_format = $this->DB_Shop->get_shop('money_format');

		if (isset($current_money_format[0]) && $current_money_format[0]) {
			$current_money_format = $current_money_format[0]->money_format;
		} else {
			$current_money_format = false;
		}

		$money_with_currency_format = $this->DB_Shop->get_shop('money_with_currency_format');

		if (isset($money_with_currency_format[0]) && $money_with_currency_format[0]) {
			$money_with_currency_format = $money_with_currency_format[0]->money_with_currency_format;

		} else {
			$money_with_currency_format = false;

		}

		if ($_POST['format'] === $current_money_format || $_POST['format'] === $money_with_currency_format) {
			$this->send_success(false);

		} else {
			$this->send_success(true);

		}

	}


	/*

	Get plugin setting money_format

	*/
	public function get_money_format() {

		if (!Utils::valid_frontend_nonce($_GET['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_money_format)' );
		}

		$moneyFormat = $this->DB_Shop->get_shop('money_format');

		if (isset($moneyFormat[0]) && $moneyFormat[0]->money_format) {

			$moneyFormat = (string)$moneyFormat[0]->money_format;
			$this->send_success($moneyFormat);

		} else {
			$this->send_success(false);

		}

	}


	/*

	Get plugin setting money_format

	*/
	public function get_money_format_with_currency() {

		if (!Utils::valid_frontend_nonce($_GET['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_money_format_with_currency)' );
		}

		$moneyFormat = $this->DB_Shop->get_shop('money_with_currency_format');

		if (isset($moneyFormat[0]) && $moneyFormat[0]->money_with_currency_format) {

			$moneyFormat = (string)$moneyFormat[0]->money_with_currency_format;
			$this->send_success($moneyFormat);

		} else {
			$this->send_success(false);

		}

	}


	public function get_selected_collections() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_selected_collections)' );
		}

		$collections = $this->DB_Settings_General->sync_by_collections();

		if (is_wp_error($collections)) {
			$this->send_error( $collections->get_error_message() );
		}

		if (empty($collections)) {
			wp_send_json_success();

		} else {
			wp_send_json_success( maybe_unserialize($collections) );
		}

	}


	public function reset_notice_flags() {

		$results = [];
		$results['app_uninstalled'] = $this->DB_Settings_General->set_app_uninstalled(0);

		$this->send_success($results);

	}



	/*

	Hooks

	*/
	public function hooks() {

		add_action('update_option_wps_settings_general', [$this, 'reset_rewrite_rules'], 10, 2);

		add_action('wp_ajax_update_settings_general', [$this, 'update_settings_general']);
		add_action('wp_ajax_nopriv_update_settings_general', [$this, 'update_settings_general']);

		add_action('wp_ajax_get_currency_format', [$this, 'get_currency_format']);
		add_action('wp_ajax_nopriv_get_currency_format', [$this, 'get_currency_format']);

		add_action('wp_ajax_get_currency_formats', [$this, 'get_currency_formats']);
		add_action('wp_ajax_nopriv_get_currency_formats', [$this, 'get_currency_formats']);

		add_action('wp_ajax_has_money_format_changed', [$this, 'has_money_format_changed']);
		add_action('wp_ajax_nopriv_has_money_format_changed', [$this, 'has_money_format_changed']);

		add_action('wp_ajax_get_money_format', [$this, 'get_money_format']);
		add_action('wp_ajax_nopriv_get_money_format', [$this, 'get_money_format']);

		add_action('wp_ajax_get_money_format_with_currency', [$this, 'get_money_format_with_currency']);
		add_action('wp_ajax_nopriv_get_money_format_with_currency', [$this, 'get_money_format_with_currency']);

		add_action('wp_ajax_get_selected_collections', [$this, 'get_selected_collections']);
		add_action('wp_ajax_nopriv_get_selected_collections', [$this, 'get_selected_collections']);

		add_action('wp_ajax_reset_notice_flags', [$this, 'reset_notice_flags']);
		add_action('wp_ajax_nopriv_reset_notice_flags', [$this, 'reset_notice_flags']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
