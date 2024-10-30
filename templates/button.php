<?php

defined( 'ABSPATH' ) || exit;

?>

<style>
.language-globe-icon {
    stroke: <?php $options = get_option('lspw_options'); echo esc_html($options['menu_icon_color']); ?>!important;
}

#lsp-button-lang-attribute {
  color: <?php $options = get_option('lspw_options'); echo esc_html($options['menu_icon_color']); ?>!important;
}

</style>


<div class="popup-menu-button-container">
	<button id="popup-settings-button" class="button">
		<div id="lsp-button-lang-icon">
			<?php
            include LSPW_WPML_LP_PLUGIN_DIR . 'assets/images/icon-globe.php';
				?>
		</div>
 	</button>
</div>
