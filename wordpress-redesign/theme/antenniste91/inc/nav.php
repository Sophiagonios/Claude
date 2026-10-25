<?php
/**
 * Menu helpers: build a simple tree from the "primary" nav menu and render
 * it both for the desktop mega-dropdown and the mobile panel, without a
 * custom Walker (fewer moving parts to get wrong without a live WordPress
 * to test against).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function antenniste91_get_menu_tree( $location = 'primary' ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return array();
	}
	$menu = wp_get_nav_menu_object( $locations[ $location ] );
	if ( ! $menu ) {
		return array();
	}
	$items = wp_get_nav_menu_items( $menu->term_id );
	if ( ! $items ) {
		return array();
	}

	$by_id = array();
	foreach ( $items as $item ) {
		$by_id[ $item->ID ] = array(
			'title'  => $item->title,
			'url'    => $item->url,
			'parent' => (int) $item->menu_item_parent,
		);
	}

	$tree = array();
	foreach ( $by_id as $id => $node ) {
		if ( ! $node['parent'] ) {
			$node['children'] = array();
			foreach ( $by_id as $maybe_child ) {
				if ( $maybe_child['parent'] === $id ) {
					$node['children'][] = $maybe_child;
				}
			}
			$tree[] = $node;
		}
	}
	return $tree;
}

/**
 * Small icon + one-line description for the "Services" mega-menu, keyed by
 * page slug. Anything not in this list still shows as a plain link — the
 * menu never breaks just because a page isn't one of the four services.
 */
function antenniste91_service_menu_meta( $slug ) {
	$map = array(
		'antenne-parabole' => array(
			'icon' => antenniste91_mini_icon( 'antenne' ),
			'desc' => "Pose, orientation, dépannage d'antenne et de parabole",
		),
		'starlink' => array(
			'icon' => antenniste91_mini_icon( 'starlink' ),
			'desc' => 'Étude de dégagement et pose du kit',
		),
		'videosurveillance' => array(
			'icon' => antenniste91_mini_icon( 'camera' ),
			'desc' => 'Caméras intérieures et extérieures',
		),
		'visiophone' => array(
			'icon' => antenniste91_mini_icon( 'visiophone' ),
			'desc' => 'Alarme et interphone vidéo connecté',
		),
	);
	return isset( $map[ $slug ] ) ? $map[ $slug ] : null;
}

function antenniste91_url_slug( $url ) {
	$path = wp_parse_url( $url, PHP_URL_PATH );
	if ( ! $path ) {
		return '';
	}
	$parts = array_filter( explode( '/', $path ) );
	return $parts ? end( $parts ) : '';
}

function antenniste91_render_desktop_nav( $tree ) {
	if ( empty( $tree ) ) {
		return;
	}
	echo '<ul class="main">';
	foreach ( $tree as $node ) {
		$has_children = ! empty( $node['children'] );
		echo '<li class="' . ( $has_children ? 'has-sub' : '' ) . '">';
		echo '<a href="' . esc_url( $node['url'] ) . '">' . esc_html( $node['title'] );
		if ( $has_children ) {
			echo ' <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>';
		}
		echo '</a>';
		if ( $has_children ) {
			echo '<div class="submenu">';
			foreach ( $node['children'] as $child ) {
				$meta = antenniste91_service_menu_meta( antenniste91_url_slug( $child['url'] ) );
				if ( $meta ) {
					echo '<a class="mega-item" href="' . esc_url( $child['url'] ) . '">';
					echo '<span class="ico">' . $meta['icon'] . '</span>';
					echo '<span><strong>' . esc_html( $child['title'] ) . '</strong><span>' . esc_html( $meta['desc'] ) . '</span></span>';
					echo '</a>';
				} else {
					echo '<a class="mega-item" href="' . esc_url( $child['url'] ) . '"><span><strong>' . esc_html( $child['title'] ) . '</strong></span></a>';
				}
			}
			echo '</div>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

function antenniste91_render_mobile_nav( $tree ) {
	if ( empty( $tree ) ) {
		return;
	}
	foreach ( $tree as $node ) {
		echo '<a class="mlink" href="' . esc_url( $node['url'] ) . '">' . esc_html( $node['title'] ) . '</a>';
		if ( ! empty( $node['children'] ) ) {
			echo '<div class="msub">';
			foreach ( $node['children'] as $child ) {
				echo '<a href="' . esc_url( $child['url'] ) . '">' . esc_html( $child['title'] ) . '</a>';
			}
			echo '</div>';
		}
	}
}
