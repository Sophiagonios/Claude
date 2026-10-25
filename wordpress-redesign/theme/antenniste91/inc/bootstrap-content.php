<?php
/**
 * Seeds the real site structure once, right after the theme is activated:
 * the homepage, the four service pages (as children of a "Services" page
 * so /services/antenne-parabole/ etc. resolve), Zone / Avis / FAQ / Contact /
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

function antenniste91_seed_blog_posts() {
	foreach ( antenniste91_blog_posts() as $post ) {
		$existing = get_page_by_path( $post['slug'], OBJECT, 'post' );
		if ( $existing ) {
			continue;
		}
		$post_id = wp_insert_post(
			array(
				'post_title'   => $post['title'],
				'post_name'    => $post['slug'],
				'post_excerpt' => $post['excerpt'],
				'post_content' => $post['content'],
				'post_status'  => 'publish',
				'post_type'    => 'post',
			)
		);
		$photo = antenniste91_blog_banner_photo( $post['slug'] );
		if ( $post_id && $photo ) {
			antenniste91_set_default_thumbnail( $post_id, $photo, $post['title'] );
		}
	}
}

/**
 * Importe une photo du thème dans la médiathèque, une seule fois (retrouvée
 * ensuite par son titre), afin qu'elle devienne une vraie image de la
 * bibliothèque — remplaçable depuis l'admin sans toucher au code.
 */
function antenniste91_seed_photo_attachment( $filename, $alt ) {
	$title    = 'antenniste91-' . sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) );
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'title'          => $title,
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$source = get_template_directory() . '/assets/photos/' . $filename;
	if ( ! file_exists( $source ) ) {
		return 0;
	}

	$upload = wp_upload_bits( $filename, null, file_get_contents( $source ) );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$filetype      = wp_check_filetype( $upload['file'], null );
	$attachment_id = wp_insert_attachment(
		array(
			'post_title'     => $title,
			'post_mime_type' => $filetype['type'],
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	wp_update_attachment_metadata( $attachment_id, $metadata );

	return $attachment_id;
}

/** Donne à une page/un article une image à la une par défaut — la propriétaire peut la changer ensuite depuis l'admin. */
function antenniste91_set_default_thumbnail( $post_id, $filename, $alt ) {
	if ( ! $post_id || has_post_thumbnail( $post_id ) ) {
		return;
	}
	$attachment_id = antenniste91_seed_photo_attachment( $filename, $alt );
	if ( $attachment_id ) {
		set_post_thumbnail( $post_id, $attachment_id );
	}
}

function antenniste91_bootstrap() {
	if ( get_option( 'antenniste91_bootstrapped' ) ) {
		return;
	}

	$services_parent = antenniste91_upsert_page( 'services', 'Services', '' );

	$antenne     = antenniste91_upsert_page( 'antenne-parabole', "Installation d'antenne et parabole", antenniste91_content_service_antenne(), $services_parent );
	$antenne_arpajon = antenniste91_upsert_page( 'antenne-tv-arpajon', 'Antenne TV à Arpajon', antenniste91_content_geo_antenne_arpajon(), $services_parent );
	$starlink    = antenniste91_upsert_page( 'starlink', "Installation d'antenne Starlink", antenniste91_content_service_starlink(), $services_parent );
	$video       = antenniste91_upsert_page( 'videosurveillance', 'Installation vidéosurveillance', antenniste91_content_service_video(), $services_parent );
	$visiophone  = antenniste91_upsert_page( 'visiophone', 'Installation alarme et visiophone', antenniste91_content_service_visiophone(), $services_parent );

	$zone        = antenniste91_upsert_page( 'zone-intervention', "Zone d'intervention", antenniste91_content_zone() );
	$avis        = antenniste91_upsert_page( 'avis-clients', 'Avis clients', antenniste91_content_avis() );
	$contact     = antenniste91_upsert_page( 'contact', 'Contact', antenniste91_content_contact() );
	$mentions    = antenniste91_upsert_page( 'mentions-legales', 'Mentions légales', antenniste91_content_mentions_legales() );
	$confidentialite = antenniste91_upsert_page( 'politique-de-confidentialite', 'Politique de confidentialité', antenniste91_content_confidentialite() );
	$cgu         = antenniste91_upsert_page( 'cgu', "Conditions générales d'utilisation", antenniste91_content_cgu() );
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

	antenniste91_seed_blog_posts();

	$service_pages = array(
		'antenne-parabole'   => $antenne,
		'antenne-tv-arpajon' => $antenne_arpajon,
		'starlink'           => $starlink,
		'videosurveillance'  => $video,
		'visiophone'         => $visiophone,
	);
	foreach ( $service_pages as $slug => $page_id ) {
		$photo = antenniste91_service_banner_photo( $slug );
		if ( $page_id && $photo ) {
			antenniste91_set_default_thumbnail( $page_id, $photo, get_the_title( $page_id ) );
		}
	}
	antenniste91_set_default_thumbnail( $accueil, 'antenne-toit.jpg', 'Antenne et parabole installées sur un toit en Essonne' );

	antenniste91_create_primary_menu( array( $antenne, $starlink, $video, $visiophone, $blog, $contact ) );

	update_option( 'antenniste91_bootstrapped', 1 );
}
add_action( 'after_switch_theme', 'antenniste91_bootstrap' );

function antenniste91_create_primary_menu( $ids ) {
	list( $antenne, $starlink, $video, $visiophone, $blog, $contact ) = $ids;

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
		$antenne    => "Installation d'antenne et parabole",
		$video      => 'Installation vidéosurveillance',
		$visiophone => 'Installation alarme et visiophone',
		$starlink   => "Installation d'antenne Starlink",
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
