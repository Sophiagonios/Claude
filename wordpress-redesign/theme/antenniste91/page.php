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

	<?php $service_slugs = array( 'antenne-tv', 'parabole-satellite', 'starlink', 'videosurveillance' ); ?>
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

<div class="inner-cta">
	<span class="eyebrow inv">Prêt à commencer</span>
	<h2>Un appel suffit pour lancer le diagnostic.</h2>
	<p>Décrivez votre besoin, obtenez un créneau et un devis gratuit — sans engagement.</p>
	<div class="hero-ctas">
		<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
			Appeler — <?php echo antenniste91_phone_display(); ?>
		</a>
	</div>
	<p style="margin-top:16px;"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="color:#CFDDE8; font-size:13.5px; text-decoration:underline;">ou décrivez votre besoin par écrit →</a></p>
</div>

<?php
get_footer();
