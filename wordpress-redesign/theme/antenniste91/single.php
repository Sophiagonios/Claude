<?php
/**
 * Single blog post.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$banner_photo_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	if ( ! $banner_photo_url ) {
		$fallback         = antenniste91_blog_banner_photo( get_post_field( 'post_name' ) );
		$banner_photo_url = $fallback ? get_template_directory_uri() . '/assets/photos/' . $fallback : '';
	}
	?>
	<?php if ( $banner_photo_url ) : ?>
		<div class="page-banner-photo" style="background-image:url('<?php echo esc_url( $banner_photo_url ); ?>');">
			<div class="wrap">
				<p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></p>
				<span class="eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
				<h1><?php the_title(); ?></h1>
			</div>
		</div>
	<?php else : ?>
		<div class="page-banner">
			<div class="wrap">
				<p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></p>
				<span class="eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
				<h1><?php the_title(); ?></h1>
			</div>
		</div>
	<?php endif; ?>

	<div class="wrap section-pad">
		<div class="entry-content is-wide">
			<?php the_content(); ?>
			<?php antenniste91_render_final_cta( 'Prêt à commencer', 'Un appel suffit pour lancer le diagnostic.', "Décrivez votre besoin, obtenez un créneau et un devis gratuit, sans engagement." ); ?>
		</div>
	</div>
	<?php
endwhile;
?>

<?php
get_footer();
