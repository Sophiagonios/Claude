<?php
/**
 * Meta title/description, Open Graph + Twitter Card tags, favicon links,
 * and a robots.txt hint pointing to WordPress's own auto-generated sitemap
 * (wp-sitemap.xml, built into WordPress core since 5.5 — no plugin needed).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function antenniste91_meta_description() {
	if ( is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			return wp_strip_all_tags( $excerpt );
		}
	}
	return get_theme_mod(
		'antenniste91_site_description',
		"Antenne TV, parabole, Starlink et vidéosurveillance pour particuliers, professionnels et collectivités en Essonne (91). Devis gratuit, intervention rapide."
	);
}

function antenniste91_meta_title() {
	if ( is_singular() ) {
		return wp_get_document_title();
	}
	return get_bloginfo( 'name' ) . ' — ' . get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' );
}

function antenniste91_head_meta() {
	$description = esc_attr( antenniste91_meta_description() );
	$title       = esc_attr( antenniste91_meta_title() );
	$og_image    = esc_url( get_template_directory_uri() . '/assets/og-image.png' );
	$url         = esc_url( is_singular() ? get_permalink() : home_url( $GLOBALS['wp']->request ) );
	?>
	<meta name="description" content="<?php echo $description; ?>">
	<link rel="canonical" href="<?php echo $url; ?>">

	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php echo $title; ?>">
	<meta property="og:description" content="<?php echo $description; ?>">
	<meta property="og:image" content="<?php echo $og_image; ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:url" content="<?php echo $url; ?>">
	<meta property="og:locale" content="fr_FR">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo $title; ?>">
	<meta name="twitter:description" content="<?php echo $description; ?>">
	<meta name="twitter:image" content="<?php echo $og_image; ?>">

	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( get_template_directory_uri() . '/assets/favicon-32.png' ); ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( get_template_directory_uri() . '/assets/favicon-180.png' ); ?>">
	<link rel="icon" type="image/png" sizes="512x512" href="<?php echo esc_url( get_template_directory_uri() . '/assets/favicon-512.png' ); ?>">
	<?php
}
add_action( 'wp_head', 'antenniste91_head_meta', 1 );

/**
 * WordPress serves a virtual robots.txt automatically (no file needed) and
 * ships a native XML sitemap at /wp-sitemap.xml since 5.5 — this filter
 * only adds an explicit pointer to it for crawlers that check robots.txt first.
 */
function antenniste91_robots_txt( $output, $public ) {
	if ( '1' !== (string) $public ) {
		return $output; // Site marked "discourage search engines" in Settings > Reading — leave WordPress's own noindex-everything output alone.
	}
	$output .= "\nSitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
	return $output;
}
add_filter( 'robots_txt', 'antenniste91_robots_txt', 10, 2 );

function antenniste91_seo_customizer( $wp_customize ) {
	$wp_customize->add_setting(
		'antenniste91_site_description',
		array(
			'default'           => "Antenne TV, parabole, Starlink et vidéosurveillance pour particuliers, professionnels et collectivités en Essonne (91). Devis gratuit, intervention rapide.",
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'antenniste91_site_description',
		array(
			'label'   => __( 'Description du site (balise meta description par défaut)', 'antenniste91' ),
			'section' => 'antenniste91_contact',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'antenniste91_seo_customizer' );
