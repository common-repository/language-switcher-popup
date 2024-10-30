<?php

/**
 * Plugin Name:  Language switcher popup for WPML
 * Description: Add-on for WPML. Detects the users browser language. Shows a popup for changing the language. Stores the language in a cookie. Redirects the user.
 * Version: 1.0.0
 * Author: Michael Neuhauser
 * Author URI: https://www.michael-neuhauser.de
 * Text Domain: language-switcher-popup
 * Domain Path: /languages/
 * Requires at least: 5.3
 * Requires PHP: 7.0
 *
 */



register_activation_hook( __FILE__, 'lspw_admin_notice_default_activation_hook' );

function lspw_admin_notice_default_activation_hook() {
  set_transient( 'lspw-admin-notice', true, 5 );

	$arr = array(
		"chkbox1" => "",
		"text_string" => "Please select your language",
		"menu_icon_color" => "#000000",
		"add_button_navigation" => "",
		"lspw_icon_position" => ""
	);
	update_option('lspw_options', $arr);
}

add_action( 'admin_notices', 'lspw_admin_notice_fn' );

function lspw_admin_notice_fn(){
  if( get_transient( 'lspw-admin-notice' ) ){
		 /* Possible notifications for later */
  }
}



function lspw_plugin_init() {
	if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {


defined('ABSPATH') || exit;

if (!defined('LSPW_WPML_LP_URL')) {
	 define('LSPW_WPML_LP_URL', plugin_dir_url(__FILE__));
}

if (!defined('LSPW_WPML_LP_PLUGIN_DIR')) {
	 define('LSPW_WPML_LP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

$plugin_version = 'basic';


// Specify Hooks/Filters


/* === Create Menu === */


function lspw_create_menu_page() {

    add_menu_page(
        'Language switcher popup for WPML',          // The title to be displayed on this menu's corresponding page
        'Language popup for WPML',                  // The text to be displayed for this actual menu item
        'manage_options',            // Which type of users can see this menu
        'lspw_options_page',                  // The unique ID - that is, the slug - for this menu item
        'lspw_options_page_fn',// The name of the function to call when rendering this menu's page
        plugins_url('language-switcher-popup/assets/images/lsp-plugin-icon-white.svg'),
				''
    );
}
add_action('admin_menu', 'lspw_create_menu_page');



// Link to settings page from plugins screen
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'lspw_add_action_links' );
function lspw_add_action_links ( $links ) {
    $mylinks = array(
        '<a href="' . admin_url( 'admin.php?page=lspw_options_page' ) . '">Settings</a>',
    );
    return array_merge( $links, $mylinks );
}




/* === Register settings. Add the settings section, and settings fields === */


function lspw_plugin_init_fn(){

	$options = get_option('lspw_options');

	register_setting('lspw_options', 'lspw_options', 'validate_options' );

	add_settings_section('standard_section_1', esc_html__('Language switcher ', 'lsp-lang-popup'), 'lspw_section1_text_fn', 'lspw_section_1');
	add_settings_field('plugin_chk1', esc_html__('Activate language switcher', 'lsp-lang-popup'), 'lspw_setting_chk1_fn', 'lspw_section_1', 'standard_section_1');
	add_settings_field('plugin_text_string', esc_html__('Choose headline', 'lsp-lang-popup'), 'lspw_setting_string_fn', 'lspw_section_1', 'standard_section_1');
	// add_settings_field('plugin_chk2', 'Restore default', 'setting_chk2_fn', 'lspw_section_1', 'standard_section_1');

	add_settings_section('standard_section_2', esc_html__('Button which opens the language switcher', 'lsp-lang-popup'), 'lspw_section2_text_fn', 'lspw_section_2');
	add_settings_field('add_button_navigation_chk', esc_html__('Add button to navigation', 'lsp-lang-popup'), 'lspw_add_button_navigation_fn', 'lspw_section_2', 'standard_section_2' ) ;
	add_settings_field('menu_icon_position', esc_html__('Choose navigation', 'lsp-lang-popup'), 'lspw_setting_menu_icon_position_fn', 'lspw_section_2', 'standard_section_2' ) ;

	add_settings_field('menu_icon_color_string', esc_html__('Choose menu icon color', 'lsp-lang-popup'), 'lspw_setting_menu_icon_color_fn', 'lspw_section_2', 'standard_section_2' ) ;


}
add_action('admin_init', 'lspw_plugin_init_fn' );


$options = get_option('lspw_options');




/* === Callback Functions === */


function lspw_section1_text_fn() {
	?><p><?php echo esc_html__('After activating the language switcher, it will automatically be displayed to your users.', 'lsp-lang-popup');?> </p><?php
}


function lspw_section2_text_fn() {
	?><p><?php echo esc_html__('You can integrate an icon which opens the language switcher by adding the following shortcode to your websites / templates:', 'lsp-lang-popup');?>
	<strong class="lsp-blue"><?php echo esc_html__('[wpml_popup_show_button]', 'lsp-lang-popup');?></strong><br><?php echo esc_html__('Or use it in Wordpress template files: ', 'lsp-lang-popup');?><strong class="lsp-blue"><?php echo esc_html__('<?php echo [wpml_popup_show_button] ?>', 'lsp-lang-popup');?></strong></p>

	<?php
	?><p><?php echo esc_html__('Or use the following settings to add the icon to the end of your navigation.', 'lsp-lang-popup');?> </p><?php

}


// Activate Plugin - Name: lspw_options[chkbox1]
function lspw_setting_chk1_fn() {
	$options = get_option('lspw_options');
	if($options['chkbox1']) { $checked = ' checked="checked" ';
}
	echo "<input ". esc_html($checked) ." id='plugin_chk2' name='lspw_options[chkbox1]' type='checkbox' />";

}


// Text of popup - Name: lspw_options[text_string]
function lspw_setting_string_fn() {

	// global $options_default;
	$options = get_option('lspw_options');
	echo "<input id='plugin_text_string' name='lspw_options[text_string]' size='40' type='text' value='". esc_html($options['text_string']) ."' />";

}




// Restore default - Name: lspw_options[chkbox2]

// function setting_chk2_fn() {
// 	$options = get_option('lspw_options');
// 	if($options['chkbox2']) { $checked = ' checked="checked" '; }
// 	echo "<input '.$checked.' id='plugin_chk2' name='lspw_options[chkbox2]' type='checkbox' />";
// 	echo ($options['chkbox2']);
//
// 	if ($options['chkbox2'] == 'on') {
// 		add_defaults_fn();
// 		add_defaults_pro_fn();
// 	}
// }



// Menu Icon Color - Name: lspw_options[menu_icon_color]

add_action( 'admin_enqueue_scripts', 'lspw_enqueue_color_picker' );
function lspw_enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', plugins_url('assets/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

function lspw_setting_menu_icon_color_fn() {
	$options = get_option('lspw_options');
	echo "<input id='menu_icon_color_string' name='lspw_options[menu_icon_color]' class='my-color-field' type='text' value='". esc_html($options['menu_icon_color']) ."' />";
}


// Add icon to navigation - Name: lspw_options[add_button_navigation]

function lspw_add_button_navigation_fn() {
	$options = get_option('lspw_options');
	if($options['add_button_navigation']) { $checked = ' checked="checked" '; }
	echo "<input ". esc_html($checked) ." id='add_button_navigation' name='lspw_options[add_button_navigation]' type='checkbox' />";
}


// Choose position of icon - Name: lspw_options[lspw_icon_position]


function lspw_setting_menu_icon_position_fn() {

	$options = get_option('lspw_options');
	$menus = get_registered_nav_menus();

	echo "<select id='lspw_icon_position' name='lspw_options[lspw_icon_position]'>";
	foreach($menus as $location => $description) {
		$selected = ($options['lspw_icon_position']==$location) ? 'selected="selected"' : '';
		echo "<option value='". esc_html($location) ."' ". esc_html($selected) .">". esc_html($description) ."</option>";
	}
	echo "</select>";
}



/* === Show admin page – Standard === */


function lspw_options_page_fn() {

	?>
		<div class="language-popup-dashboard">
			<?php settings_errors(); ?>

			<div class="lsp-row">
				<div class="col-6-12">
					<h1><?php echo esc_html__('Language switcher popup for WPML – Stores selected language in a cookie – Redirects user to his chosen language', 'lsp-lang-popup');?></h1>
					<p><?php echo esc_html__('This plugin detects the browser language of your users. If the browser language is different from the default language of your website, a pop-up appears. There your user can change the language. Once the user selects a language, the information is stored in a cookie. When he visits the startpage again, he automatically gets redirected to the language he selected.', 'lsp-lang-popup');?></p>
					<ul class="ul-checkmarks">
						<li><?php echo esc_html__('Seo friendly (no auto redirection without user interaction)', 'lsp-lang-popup');?></li>
						<li><?php echo esc_html__('Only shows a popup if user has a different browser language', 'lsp-lang-popup');?></li>
						<li><?php echo esc_html__('Stores language in a cookie', 'lsp-lang-popup');?></li>
						<li><?php 	echo esc_html__('Redirects user to his chosen language', 'lsp-lang-popup');?></li>
					</ul>
				</div>
				<div class="col-6-12">
					<img class="lsp-teaser-image" src="<?php echo esc_url(LSPW_WPML_LP_URL) . 'assets/images/lsp-popup-image-2.png'; ?>">
				</div>
			</div>

			<h1><?php echo esc_html__('Settings', 'lsp-lang-popup');?></h1>

			<form action="options.php" method="post">

						<?php
				if ( function_exists('wp_nonce_field') )
					wp_nonce_field('lspw_plugin_action_' . "yep");
				?>
			<?php settings_fields('lspw_options'); ?>

			<?php do_settings_sections('lspw_section_1'); ?>
			<?php do_settings_sections('lspw_section_2'); ?>

			<p class="submit">
				<input name="Submit" type="submit" class="button-primary lsp-button" value="<?php echo esc_attr_e('Save Changes', 'lsp-lang-popup'); ?>" />
			</p>

			</form>
		</div>


		<div class="language-popup-newsletter">
				<iframe scrolling="no" src="https://michael-neuhauser.de/lsp-plugin-newsletter" title="Newsletter"></iframe>
		</div>


		<div class="language-popup-dashboard">
			<h1><?php echo esc_html__('Support, bugs and feature requests?', 'lsp-lang-popup');?></h1>
			<p><?php echo esc_html__('As this is a new plugin, please be kind and give me feedback. I will do my best. :)', 'lsp-lang-popup');?></p>
			<a class="lsp-button positiv" href="mailto:info@michael-neuhauser.de"><?php echo esc_html__('Please send me a message', 'lsp-lang-popup');?></a>
		</div>




	<?php

} // end admin page standard




/* ============== Load elements in Frontend BASIC ================== */
/* =================================================================== */



$options = get_option('lspw_options');
if ($options['chkbox1'] == 'on') {



/* Integrate button into navigation */


	if ($options['add_button_navigation'] == 'on') {

		add_filter('wp_nav_menu_items','lspw_button_insert_navigation', 10, 2);
		function lspw_button_insert_navigation( $nav, $args ) {


				$options = get_option('lspw_options');
				$iconPosition = $options['lspw_icon_position'];

		    if( $args->theme_location == $iconPosition ) {

					ob_start();
					include LSPW_WPML_LP_PLUGIN_DIR . 'templates/button.php';
					$buffer = ob_get_clean();
					$new_items = $nav . $buffer ;
					return $new_items	;
			}
			else {
				return$nav;
			}
		}
	}


	wp_register_style('wpml-button-style', LSPW_WPML_LP_URL . 'assets/css/lsp-button.css', array(), '1.0.0');
	wp_enqueue_style('wpml-button-style');


	add_shortcode('wpml_popup_show_button', function ( $args = '') {
		ob_start();
		require LSPW_WPML_LP_PLUGIN_DIR . 'templates/button.php';
		return ob_get_clean();
	});


	if ($plugin_version == 'basic') {
		add_action('wp_footer', function () {
			require LSPW_WPML_LP_PLUGIN_DIR . 'templates/selector-popup.php';
			wp_register_style('wpml-popup-style', LSPW_WPML_LP_URL . 'assets/css/popup-style-standard-light.css', array(), '1.0.0');
			wp_enqueue_style('wpml-popup-style');
		});
	}

}



/* === Validate color code BASIC === */


function validate_options($data) {

    $old_options = get_option('lspw_options');
    $has_errors = false;


		if (!preg_match( '/^#[a-f0-9]{6}$/i', $data['menu_icon_color'])) {
				add_settings_error('prefix_messages', 'prefix_message', esc_html__('Color code is invalid', 'prefix'), 'error');

				$has_errors = true;
		}


    if ($has_errors) {
        $data = $old_options;
    }

    return $data;
}



/* =================== Plugin functions  =================== */
/* ========================================================= */



add_action('wp_loaded', function () {
	if (function_exists('load_plugin_textdomain')) {
		load_plugin_textdomain('lsp-lang-popup', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
});


add_action('wp_loaded', function () {

	if (isset($_POST['wpml_lspw_current_language'])) {

		if ( ! isset( $_POST['wpml_lp_nonce'] )
    || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['wpml_lp_nonce'])), 'wpml_lp_nonce')
		) {
		   print '<div style="width:100%; height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 24px; font-family: Arial;" ><span>Sorry, changing the language did not work. </span> <a style="color: #fff; background-color: #000; border-radius: 8px; padding:15px; font-size: 18px; text-decoration: none; margin-top: 20px;" href="' . get_site_url() . '">Return to the main page</a></div>';
		   exit;
		} else {

			$wpml_language = isset($_POST['wpml_language']) ? sanitize_text_field($_POST['wpml_language']) : '';

			global $sitepress;

			if (!empty($wpml_language)) {

				if (function_exists('wc_setcookie')) {
					wc_setcookie('wpml_lspw_current_language', $wpml_language, time() + ( 3600 * 24 * 7 ));
				} else {
					setcookie('wpml_lspw_current_language', $wpml_language, time() + ( 3600 * 24 * 7 ), COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN);
				}
			}

			if (isset($_POST['wpml_language_permalink'][$wpml_language])) {

				$location = esc_url_raw($_POST['wpml_language_permalink'][$wpml_language] );

				wp_redirect( $location);
				exit;
			}
		}
	}
}, 500);



add_action('template_redirect', function () {

	$wpml_language = empty($_COOKIE['wpml_lspw_current_language']) ? '' : sanitize_text_field($_COOKIE['wpml_lspw_current_language']);

	global $sitepress, $post;

	if ($wpml_language != $sitepress->get_current_language()) {

		if (!empty($wpml_language) && !empty($sitepress)) {

			$url  = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ?  'https://' : 'http://';
			$url .= isset($_SERVER['HTTP_HOST']) ? sanitize_text_field($_SERVER['HTTP_HOST']) : '';
			$url .= isset($_SERVER['REQUEST_URI']) ? sanitize_text_field($_SERVER['REQUEST_URI']) : '';

			define('LSPW_CURRENT_LANGUAGE_CODE', $wpml_language);

			global $plugin_version;

			$location = apply_filters('wpml_permalink', $url, $wpml_language);

			if ( is_front_page() ){
				wp_redirect( $location, 301);
				exit;
			}
		}
	}

}, 1000);



add_action('admin_enqueue_scripts', function () {

		wp_register_style('admin-style', LSPW_WPML_LP_URL . 'assets/css/admin-style.css', array(), '1.0.0');
		wp_enqueue_style('admin-style');

		wp_register_style('admin-style-icons', LSPW_WPML_LP_URL . 'assets/css/lsp-icons.css', array(), '1.0.0');
		wp_enqueue_style('admin-style-icons');



	}, 100);



add_action('wp_enqueue_scripts', function () {

	global $sitepress;
	$current_language = $sitepress->get_current_language();

	wp_register_script('wpml-popup-script', LSPW_WPML_LP_URL . 'assets/js/script.js', array('jquery'), '1.0.0', true);
	wp_register_script('wpml-select2-script', LSPW_WPML_LP_URL . 'assets/js/select2.min.js', array(), '1.0.0', true);



	wp_enqueue_script('wpml-select2-script');
	wp_enqueue_script('wpml-popup-script');


	wp_localize_script('wpml-popup-script', 'wpml_var', array(
		'active_lang' => $current_language,
	));
}, 100);



/* ==== Scripts ===== */

function lspw_enqueue_admin_script() {

	wp_enqueue_media();
	wp_enqueue_script("custom-js", plugin_dir_url(__FILE__) . "assets/js/admin-script.js");

    if($_GET["page"] == "language-switcher-popup-wpml")
    {
				wp_enqueue_media();
        wp_enqueue_script("custom-js", plugin_dir_url(__FILE__) . "assets/js/admin-script.js");
    }
}

add_action("admin_enqueue_scripts", "lspw_enqueue_admin_script");


}
else {
	function wpb_admin_notice_warn() {
	if( current_user_can('administrator') ) {
	echo '<div class="notice notice-error is-dismissible">
	      <p>WPML language switcher popup is enabled but not effective. It requires <a href="https://wpml.org" target="_blank">WPML</a>.</p>
	      </div>';
			}
	}
	add_action( 'admin_notices', 'wpb_admin_notice_warn' );
}

}
add_action( 'plugins_loaded', 'lspw_plugin_init' );
