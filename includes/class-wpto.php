<?php

/**
* @link              https://www.designsbytouch.co.uk
* @since             1.0.0
* @package           Wp_Theme_Optimizer
*/
class wpto {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      wpto_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Unique identifier for your plugin options.
	 *
	 *
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $options_slug;


	/**
	 * Default Settings Values.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $options_data;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'wpto';
		$this->version = '1.0.0';
		$this->plugin_screen_hook_suffix = null;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - wpto_Loader. Orchestrates the hooks of the plugin.
	 * - wpto_i18n. Defines internationalization functionality.
	 * - wpto_Admin. Defines all hooks for the admin area.
	 * - wpto_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpto-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpto-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpto-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpto-public.php';

		$this->loader = new wpto_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the wpto_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new wpto_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

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

		$plugin_admin = new wpto_Admin( $this->get_plugin_name(), $this->get_version()) ;

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update');

		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
                //write_log($plugin_basename);
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );




	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new wpto_Public( $this->get_plugin_name(), $this->get_version());

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );


		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_cssjs_ver');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_wp_version_number');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_oembed');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_jquery_migrate');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_emoji_release');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_recent_comments_css');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_rsd_link');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_rss_feed');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_wlwmanifest');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_wp_json');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_wp_shortlink');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_wp_post_links');
		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_dns_prefetch');
// Yoast
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_remove_yoast_information');

// WooCommerce
 if ( class_exists( 'WooCommerce' ) ) {

$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_add_payment_method' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_lost_password' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_price_slider' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_single_product' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_add_to_cart' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_cart_fragments' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_credit_card_form' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_checkout' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_add_to_cart_variation' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_single_product' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_cart' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_wc_chosen' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_woocommerce' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_prettyPhoto' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_prettyPhoto_init' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_jquery_blockui' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_jquery_placeholder' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_jquery_payment' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_fancybox' );
$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_jqueryui' );
}




// HTML minify
	$this->loader->add_action( 'after_setup_theme', $plugin_public, 'wpto_html_minify');




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
	 * @return    wpto_Loader    Orchestrates the hooks of the plugin.
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
