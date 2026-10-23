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

<?php antenniste91_render_final_cta( 'Prêt à commencer', 'Un appel suffit pour lancer le diagnostic.', "Décrivez votre besoin, obtenez un créneau et un devis gratuit, sans engagement." ); ?>

<?php
get_footer();
