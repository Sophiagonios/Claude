<?php
/**
 * Front page: fixed hero (audience toggle + trust checklist), then the
 * "Accueil" page's own block content (services teaser, process, audience
 * split, zone/avis/faq teasers, final CTA) — fully editable in the block
 * editor, created once by inc/bootstrap-content.php.
 */

get_header();
?>

<div class="wrap hero-band">
	<div class="hero">
		<div class="grid">
			<div>
				<div class="toggle" role="group" aria-label="Je suis...">
					<button type="button" id="tab-part" aria-pressed="true">Particulier</button>
					<button type="button" id="tab-pro" aria-pressed="false">Professionnel</button>
				</div>

				<div class="audience-panel" id="panel-part">
					<h1><?php echo wp_kses_post( get_theme_mod( 'antenniste91_hero_title_part' ) ); ?></h1>
					<p class="lead"><?php echo wp_kses_post( get_theme_mod( 'antenniste91_hero_lead_part' ) ); ?></p>
				</div>
				<div class="audience-panel" id="panel-pro" hidden>
					<h1><?php echo wp_kses_post( get_theme_mod( 'antenniste91_hero_title_pro' ) ); ?></h1>
					<p class="lead"><?php echo wp_kses_post( get_theme_mod( 'antenniste91_hero_lead_pro' ) ); ?></p>
				</div>

				<div class="hero-ctas">
					<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
						Appeler maintenant
					</a>
					<div class="google-badge">
						<svg viewBox="0 0 24 24" width="22" height="22"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.63h6.47a5.53 5.53 0 0 1-2.4 3.63v3h3.89c2.27-2.09 3.58-5.17 3.58-8.81Z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.07 7.93-2.92l-3.89-3c-1.08.73-2.46 1.15-4.04 1.15-3.1 0-5.73-2.09-6.67-4.9H1.3v3.09A12 12 0 0 0 12 24Z"/><path fill="#FBBC05" d="M5.33 14.33a7.2 7.2 0 0 1 0-4.66V6.58H1.3a12 12 0 0 0 0 10.84l4.03-3.09Z"/><path fill="#EA4335" d="M12 4.77c1.76 0 3.34.6 4.58 1.79l3.44-3.44C17.94 1.19 15.23 0 12 0A12 12 0 0 0 1.3 6.58l4.03 3.09C6.27 6.86 8.9 4.77 12 4.77Z"/></svg>
						<span><strong><?php echo antenniste91_google_rating(); ?>/5</strong> sur Google</span>
					</div>
				</div>
				<p style="margin:-6px 0 26px; color:#CFDDE8; font-size:13.5px;">Devis gratuit, sans engagement.</p>

				<ul class="checklist">
					<li>Un conseil clair, même sans engagement</li>
					<li>Un expert qui répond à toutes vos questions</li>
					<li>Rien ne repart tant que ce n'est pas réglé</li>
				</ul>
			</div>

			<div class="hero-photo">
				<div class="cap">Photo à ajouter — une intervention réelle (antenne, Starlink ou caméra posée)</div>
			</div>
		</div>
	</div>
</div>

<div class="stats">
	<div class="wrap">
		<div class="stat"><span class="label">Diagnostic avant toute intervention</span></div>
		<div class="stat"><span class="label">Particuliers, professionnels &amp; collectivités</span></div>
	</div>
</div>

<?php
while ( have_posts() ) :
	the_post();
	echo '<div class="wrap section-pad">';
	the_content();
	echo '</div>';
endwhile;

get_footer();
