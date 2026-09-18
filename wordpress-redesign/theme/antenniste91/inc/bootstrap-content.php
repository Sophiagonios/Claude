<?php
/**
 * Seeds the real site structure once, right after the theme is activated:
 * the homepage, the four service pages (as children of a "Services" page
 * so /services/antenne-tv/ etc. resolve), Zone / Avis / FAQ / Contact /
 * Mentions légales, the reading settings, and the primary nav menu.
 *
 * Runs exactly once (guarded by the antenniste91_bootstrapped option) so
 * re-activating the theme later never overwrites content the owner has
 * since edited in the block editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_template_directory() . '/inc/content.php';

function antenniste91_upsert_page( $slug, $title, $content, $parent_id = 0 ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return $existing->ID;
	}
	return wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_parent'  => $parent_id,
		)
	);
}

function antenniste91_bootstrap() {
	if ( get_option( 'antenniste91_bootstrapped' ) ) {
		return;
	}

	$services_parent = antenniste91_upsert_page( 'services', 'Services', '' );

	$antenne     = antenniste91_upsert_page( 'antenne-tv', 'Antenne TV & TNT', antenniste91_content_service_antenne(), $services_parent );
	$parabole    = antenniste91_upsert_page( 'parabole-satellite', 'Parabole & satellite', antenniste91_content_service_parabole(), $services_parent );
	$starlink    = antenniste91_upsert_page( 'starlink', 'Internet Starlink', antenniste91_content_service_starlink(), $services_parent );
	$video       = antenniste91_upsert_page( 'videosurveillance', 'Vidéosurveillance', antenniste91_content_service_video(), $services_parent );

	$zone        = antenniste91_upsert_page( 'zone-intervention', "Zone d'intervention", antenniste91_content_zone() );
	$avis        = antenniste91_upsert_page( 'avis-clients', 'Avis clients', antenniste91_content_avis() );
	$faq         = antenniste91_upsert_page( 'faq', 'Questions fréquentes', antenniste91_content_faq() );
	$contact     = antenniste91_upsert_page( 'contact', 'Contact', antenniste91_content_contact() );
	$mentions    = antenniste91_upsert_page( 'mentions-legales', 'Mentions légales', antenniste91_content_mentions_legales() );
	$blog        = antenniste91_upsert_page( 'blog', 'Blog', '' );
	$accueil     = antenniste91_upsert_page( 'accueil', 'Accueil', antenniste91_content_home() );

	// Reading settings: static front page + a real page for the blog archive.
	if ( $accueil ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $accueil );
	}
	if ( $blog ) {
		update_option( 'page_for_posts', $blog );
	}

	antenniste91_create_primary_menu( array( $antenne, $parabole, $starlink, $video, $blog, $faq, $contact ) );

	update_option( 'antenniste91_bootstrapped', 1 );
}
add_action( 'after_switch_theme', 'antenniste91_bootstrap' );

function antenniste91_create_primary_menu( $ids ) {
	list( $antenne, $parabole, $starlink, $video, $blog, $faq, $contact ) = $ids;

	$menu_name = 'Menu principal';
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? $menu->term_id : wp_create_nav_menu( $menu_name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$services_parent_item = wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => 'Services',
			'menu-item-url'       => '#',
			'menu-item-status'    => 'publish',
		)
	);

	$children = array(
		$antenne  => 'Antenne TV & TNT',
		$parabole => 'Parabole & satellite',
		$starlink => 'Internet Starlink',
		$video    => 'Vidéosurveillance',
	);
	foreach ( $children as $page_id => $label ) {
		if ( ! $page_id ) {
			continue;
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-parent-id' => $services_parent_item,
				'menu-item-status'    => 'publish',
			)
		);
	}

	if ( $blog ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => 'Blog',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $blog,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}
	if ( $faq ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => 'FAQ',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $faq,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}
	if ( $contact ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => 'Contact',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $contact,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}

	$locations              = get_theme_mod( 'nav_menu_locations' );
	$locations              = is_array( $locations ) ? $locations : array();
	$locations['primary']   = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
