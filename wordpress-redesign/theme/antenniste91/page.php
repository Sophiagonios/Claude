<?php
/**
 * Generic inner-page template: used for every page that isn't the front
 * page (services, zone d'intervention, avis, FAQ, contact, mentions
 * légales…). A light banner up top, the page's own block content, and a
 * closing call-to-action band so every page keeps pushing toward the phone.
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-banner">
		<div class="wrap">
			<p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <?php the_title(); ?></p>
			<span class="eyebrow"><?php bloginfo( 'name' ); ?></span>
			<h1><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php $service_slugs = array( 'antenne-tv', 'parabole-satellite', 'starlink', 'videosurveillance', 'visiophone' ); ?>
	<?php if ( in_array( get_post_field( 'post_name' ), $service_slugs, true ) ) : ?>
		<div class="wrap section-pad">
			<div class="page-layout">
				<aside class="toc">
					<span class="toc-label">Sur cette page</span>
					<?php foreach ( antenniste91_service_toc() as $item ) : ?>
						<a href="<?php echo esc_url( $item[0] ); ?>"><?php echo esc_html( $item[1] ); ?></a>
					<?php endforeach; ?>
				</aside>
				<div class="entry-content is-wide">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="wrap section-pad">
			<div class="entry-content is-wide">
				<?php the_content(); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php
endwhile;
?>

<?php antenniste91_render_final_cta( 'Prêt à commencer', 'Un appel suffit pour lancer le diagnostic.', "Décrivez votre besoin, obtenez un créneau et un devis gratuit, sans engagement." ); ?>

<?php
get_footer();
