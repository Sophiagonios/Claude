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
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Roboto:wght@400;500;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'antenniste91-style', get_stylesheet_uri(), array(), ANTENNISTE91_VERSION );
}
add_action( 'wp_enqueue_scripts', 'antenniste91_assets' );

/**
 * Site-wide inline scripts: mobile nav toggle (every page) and the
 * front-page hero audience toggle (Particulier / Professionnel).
 * Small enough to inline; not worth a separate enqueued file.
 */
function antenniste91_inline_scripts() {
	?>
	<script>
	(function(){
		var mnav = document.getElementById('mnav');
		var openBtn = document.getElementById('mnav-open');
		var closeBtn = document.getElementById('mnav-close');
		function openNav(){ mnav.classList.add('open'); openBtn.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; }
		function closeNav(){ mnav.classList.remove('open'); openBtn.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }
		if ( openBtn ) { openBtn.addEventListener('click', openNav); }
		if ( closeBtn ) { closeBtn.addEventListener('click', closeNav); }
		if ( mnav ) { mnav.addEventListener('click', function(e){ if ( e.target === mnav ) { closeNav(); } }); }
		document.querySelectorAll('.mobile-nav a').forEach(function(a){ a.addEventListener('click', closeNav); });

		/* Bascule Particulier/Professionnel réutilisée pour tout couple
		   d'onglets tab-X/tab-Y + panel-X/panel-Y présent sur la page
		   (hero d'accueil, puis section "Pour qui ?"). */
		function wireAudienceToggle(tabPartId, tabProId, panelPartId, panelProId){
			var tabPart = document.getElementById(tabPartId);
			var tabPro = document.getElementById(tabProId);
			var panelPart = document.getElementById(panelPartId);
			var panelPro = document.getElementById(panelProId);
			if ( tabPart && tabPro && panelPart && panelPro ) {
				var show = function(which){
					var isPart = which === 'part';
					panelPart.hidden = !isPart;
					panelPro.hidden = isPart;
					tabPart.setAttribute('aria-pressed', String(isPart));
					tabPro.setAttribute('aria-pressed', String(!isPart));
				};
				tabPart.addEventListener('click', function(){ show('part'); });
				tabPro.addEventListener('click', function(){ show('pro'); });
			}
		}
		wireAudienceToggle('tab-part', 'tab-pro', 'panel-part', 'panel-pro');

		/* Sombre mat = thème par défaut (pas d'attribut data-theme).
		   Clair = choix explicite de la personne (data-theme="light"). */
		var toggle = document.getElementById('theme-toggle');
		if ( toggle ) {
			var sun = toggle.querySelector('.icon-sun');
			var moon = toggle.querySelector('.icon-moon');
			function currentTheme(){
				return document.documentElement.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
			}
			function paint(theme){
				var isDark = theme === 'dark';
				sun.hidden = isDark;
				moon.hidden = !isDark;
				toggle.setAttribute('aria-label', isDark ? 'Passer en mode clair' : 'Passer en mode sombre');
			}
			paint( currentTheme() );
			toggle.addEventListener('click', function(){
				var next = currentTheme() === 'dark' ? 'light' : 'dark';
				if ( next === 'light' ) { document.documentElement.setAttribute('data-theme', 'light'); }
				else { document.documentElement.removeAttribute('data-theme'); }
				try { localStorage.setItem('antenniste91_theme', next); } catch(e) {}
				paint(next);
			});
		}

		/* Comparateur avant/après à curseur : la photo "avant" est révélée
		   par un clip-path piloté par un <input type="range"> natif, donc
		   déplaçable au doigt, à la souris, ou au clavier (flèches). */
		document.querySelectorAll('.ba-slider').forEach(function(slider){
			var range = slider.querySelector('.ba-range');
			var before = slider.querySelector('.ba-before');
			var handle = slider.querySelector('.ba-handle');
			if ( ! range || ! before || ! handle ) { return; }
			function update(){
				var v = range.value;
				before.style.clipPath = 'inset(0 ' + (100 - v) + '% 0 0)';
				handle.style.left = v + '%';
			}
			range.addEventListener('input', update);
			update();
		});

		/* Carrousel avant/après : flèches précédent/suivant qui font défiler
		   d'une carte à la fois ; le défilement tactile (swipe) fonctionne
		   nativement grâce au scroll-snap, sans JS. */
		document.querySelectorAll('.ba-carousel').forEach(function(carousel){
			var track = carousel.querySelector('.ba-track');
			var prev = carousel.querySelector('.ba-prev');
			var next = carousel.querySelector('.ba-next');
			if ( ! track ) { return; }
			function scrollByCard(dir){
				var card = track.querySelector('.ba-wrap');
				var amount = card ? card.getBoundingClientRect().width + 20 : track.clientWidth;
				track.scrollBy({ left: dir * amount, behavior: 'smooth' });
			}
			if ( prev ) { prev.addEventListener('click', function(){ scrollByCard(-1); }); }
			if ( next ) { next.addEventListener('click', function(){ scrollByCard(1); }); }
		});
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'antenniste91_inline_scripts' );

require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/nav.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/contact-form.php';
require get_template_directory() . '/inc/security.php';
require get_template_directory() . '/inc/cookie-consent.php';
require get_template_directory() . '/inc/bootstrap-content.php';
