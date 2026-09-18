<?php
/**
 * Antenniste 91 — theme setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ANTENNISTE91_VERSION', '1.0.0' );

/**
 * Theme support + menus.
 */
function antenniste91_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'antenniste91' ),
			'footer'  => __( 'Menu pied de page', 'antenniste91' ),
		)
	);
}
add_action( 'after_setup_theme', 'antenniste91_setup' );

/**
 * Styles & fonts.
 */
function antenniste91_assets() {
	wp_enqueue_style(
		'antenniste91-fonts',
		'https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800;900&family=Manrope:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'antenniste91-style', get_stylesheet_uri(), array(), ANTENNISTE91_VERSION );
}
add_action( 'wp_enqueue_scripts', 'antenniste91_assets' );

/**
 * Front page hero toggle script (Particulier / Professionnel).
 * Inline: two lines of JS, not worth a separate enqueued file.
 */
function antenniste91_hero_script() {
	if ( ! is_front_page() ) {
		return;
	}
	?>
	<script>
	(function(){
		var tabPart = document.getElementById('tab-part');
		var tabPro = document.getElementById('tab-pro');
		var panelPart = document.getElementById('panel-part');
		var panelPro = document.getElementById('panel-pro');
		if ( ! tabPart || ! tabPro || ! panelPart || ! panelPro ) { return; }
		function show(which){
			var isPart = which === 'part';
			panelPart.hidden = !isPart;
			panelPro.hidden = isPart;
			tabPart.setAttribute('aria-pressed', String(isPart));
			tabPro.setAttribute('aria-pressed', String(!isPart));
		}
		tabPart.addEventListener('click', function(){ show('part'); });
		tabPro.addEventListener('click', function(){ show('pro'); });
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'antenniste91_hero_script' );

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/bootstrap-content.php';
