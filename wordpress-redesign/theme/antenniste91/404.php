<?php
/**
 * Custom 404 — on-brand, with the primary CTA still front and centre
 * instead of a dead end.
 */

get_header();
?>

<div class="page-banner">
	<div class="wrap">
		<span class="eyebrow">Erreur 404</span>
		<h1>Cette page n'existe pas (ou plus).</h1>
		<p class="lead">Le lien est peut-être obsolète. Voici comment retrouver votre chemin.</p>
	</div>
</div>

<div class="wrap section-pad">
	<div class="entry-content is-wide">
		<div class="signal-grid">
			<a class="card" style="text-decoration:none; display:block;" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<h3>Accueil</h3>
				<p>Retour à la page principale.</p>
			</a>
			<a class="card" style="text-decoration:none; display:block;" href="<?php echo esc_url( home_url( '/services/antenne-parabole/' ) ); ?>">
				<h3>Installation d'antenne et parabole</h3>
				<p>Voir ce service.</p>
			</a>
			<a class="card" style="text-decoration:none; display:block;" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
				<h3>Blog</h3>
				<p>Conseils antenne, Starlink et vidéosurveillance.</p>
			</a>
			<a class="card" style="text-decoration:none; display:block;" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<h3>Contact</h3>
				<p>Nous décrire votre besoin.</p>
			</a>
		</div>

		<?php antenniste91_render_final_cta( "Besoin d'aide tout de suite ?", 'Un appel suffit pour lancer le diagnostic.' ); ?>
	</div>
</div>

<?php
get_footer();
