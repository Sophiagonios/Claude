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
				</div>
				<p style="margin:-6px 0 26px;"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="color:#CFDDE8; font-size:13.5px; text-decoration:underline;">ou décrivez votre besoin par écrit →</a></p>

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
		<div class="stat"><span class="label" style="font-size:14.5px; font-weight:700; color:var(--text);">Diagnostic avant toute intervention</span></div>
		<div class="stat"><span class="label" style="font-size:14.5px; font-weight:700; color:var(--text);">Un seul interlocuteur, du 1er appel au SAV</span></div>
		<div class="stat"><span class="label" style="font-size:14.5px; font-weight:700; color:var(--text);">Particuliers, professionnels &amp; collectivités</span></div>
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
