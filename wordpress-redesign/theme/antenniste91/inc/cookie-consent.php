<?php
/**
 * Cookie consent banner + an analytics loader that only runs after the
 * visitor accepts — required for anything beyond strictly necessary
 * cookies under French/EU rules (CNIL). No tracking ID is set by default;
 * nothing loads until the owner fills in inc/customizer.php's field and
 * the visitor consents.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function antenniste91_analytics_customizer( $wp_customize ) {
	$wp_customize->add_setting(
		'antenniste91_ga_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'antenniste91_ga_id',
		array(
			'label'       => __( 'Identifiant Google Analytics 4 (ex. G-XXXXXXX — laisser vide pour ne rien charger)', 'antenniste91' ),
			'section'     => 'antenniste91_contact',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'antenniste91_analytics_customizer' );

function antenniste91_cookie_banner() {
	$ga_id = get_theme_mod( 'antenniste91_ga_id', '' );
	?>
	<div id="cookie-banner" class="cookie-banner" hidden>
		<div class="cookie-banner-box">
			<p>Ce site utilise des cookies techniques nécessaires à son fonctionnement et, avec votre accord, un outil de mesure d'audience. <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">En savoir plus</a>.</p>
			<div class="cookie-banner-actions">
				<button type="button" class="btn btn-ghost" id="cookie-refuse">Refuser</button>
				<button type="button" class="btn btn-call" id="cookie-accept">Accepter</button>
			</div>
		</div>
	</div>
	<script>
	(function(){
		var KEY = 'antenniste91_cookie_choice';
		var gaId = <?php echo wp_json_encode( $ga_id ); ?>;

		function loadAnalytics(){
			if ( ! gaId ) { return; }
			var s = document.createElement('script');
			s.async = true;
			s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(gaId);
			document.head.appendChild(s);
			window.dataLayer = window.dataLayer || [];
			function gtag(){ dataLayer.push(arguments); }
			gtag('js', new Date());
			gtag('config', gaId, { anonymize_ip: true });
		}

		var choice;
		try { choice = localStorage.getItem(KEY); } catch(e) { choice = null; }

		if ( choice === 'accepted' ) {
			loadAnalytics();
		} else if ( choice !== 'refused' ) {
			var banner = document.getElementById('cookie-banner');
			if ( banner ) { banner.hidden = false; }
		}

		var accept = document.getElementById('cookie-accept');
		var refuse = document.getElementById('cookie-refuse');
		if ( accept ) {
			accept.addEventListener('click', function(){
				try { localStorage.setItem(KEY, 'accepted'); } catch(e) {}
				document.getElementById('cookie-banner').hidden = true;
				loadAnalytics();
			});
		}
		if ( refuse ) {
			refuse.addEventListener('click', function(){
				try { localStorage.setItem(KEY, 'refused'); } catch(e) {}
				document.getElementById('cookie-banner').hidden = true;
			});
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'antenniste91_cookie_banner', 20 );
