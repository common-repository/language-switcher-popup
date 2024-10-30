<?php

defined('ABSPATH') || exit;

global $sitepress;


?>

<div class="lspw-wpml-popup" style="display:none">
	<div class="lspw-wpml-popup-background"></div>
	<div class="wpml-popup-inner">
		<form id="popup-form-language" name="myform" method="POST">
				<div class="popup-language-select-headline-container">
					<p class="popup-language-select-language-headline"><?php $options = get_option('lspw_options'); echo esc_html($options['text_string']); ?></p>
				</div>
					<div class="popup-language-select">
						<div class="popup-language-dropdown">
							<?php

							if (!empty($sitepress)) :
								$active_languages = $sitepress->get_active_languages();
								$shipping_country = empty($_COOKIE['wpml_lspw_current_language']) ? '' : sanitize_text_field(esc_html($_COOKIE['wpml_lspw_current_language']));
								if (!empty($shipping_country)) {
									$current_language = $sitepress->get_current_language();
								} else {
									$current_language = '';
								}

								foreach ($active_languages as $language) :

									$url  = isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ?  'https://' : 'http://';
									$url .= isset($_SERVER['HTTP_HOST']) ? sanitize_text_field($_SERVER['HTTP_HOST']) : '';
									$url .= isset($_SERVER['REQUEST_URI']) ? sanitize_text_field($_SERVER['REQUEST_URI']) : '';

									$paramters       = '';
									$paramters_array = array();

									if ( strpos( $url, '?' ) ) {
										$data      = explode('?', $url );
										$paramters = end( $data );

										$paramters_array = explode('&', $paramters );
										$paramters       = '';

									}

									$location = apply_filters('wpml_permalink', $url, $language['code']);

									if ( !empty( $paramters_array ) ) {

										$paramters = array();

										foreach ( array_unique( $paramters_array ) as $value ) {

											if ( false === strpos($value , 'lang' ) && false === strpos($location , $value ) ) {

												$paramters[] = urldecode( $value );
											}
										}

										$paramters = implode('&', $paramters );
									}

									if ( !empty( $paramters ) ) {
										if ( strpos( $location, '?') ) {
											$location .= '&' . $paramters;
										} else {
											$location .= '?' . $paramters;
										}
									}

									?>
									<input type="hidden" name="wpml_language_permalink[<?php echo esc_html($language['code']); ?>]" value="<?php echo esc_url($location); ?>">
								<?php endforeach; ?>

								<select name="wpml_language" class="wpml_language_dropdown wpml_language_selector" title="<?php esc_html_e('Select your language', 'wpml-lang-popup'); ?>">
									<?php
									foreach ($active_languages as $language) :
										?>
										<option value="<?php echo esc_html($language['code']); ?>" <?php echo selected($language['code'], $current_language); ?>><?php echo esc_html($language['native_name']); ?></option>
									<?php endforeach; ?>
								</select>
							<?php endif; ?>
						</div>
						<?php
						wp_nonce_field('wpml_lp_nonce', 'wpml_lp_nonce');
						?>
						<button class="wpml-popup-button" type="submit" name="wpml_lspw_current_language">
							<?php esc_html_e('Select', 'wpml-lang-popup'); ?>
						</button>
					</div>
				<div class="cross-1px" id="popup-form-language-close"></div>
	</form>
</div>

</div>
