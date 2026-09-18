<?php
/**
 * Single blog post.
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-banner">
		<div class="wrap">
			<p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></p>
			<span class="eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="wrap section-pad">
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</div>
	<?php
endwhile;
?>

<div class="inner-cta">
	<span class="eyebrow inv">Prêt à commencer</span>
	<h2>Un appel suffit pour lancer le diagnostic.</h2>
	<p>Décrivez votre besoin, obtenez un créneau et un devis gratuit — sans engagement.</p>
	<div class="hero-ctas">
		<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">Appeler — <?php echo antenniste91_phone_display(); ?></a>
		<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Devis gratuit</a>
	</div>
</div>

<?php
get_footer();
