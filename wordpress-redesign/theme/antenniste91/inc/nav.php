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
		'antenne-tv' => array(
			'icon' => '<svg viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="5" width="18" height="12" rx="2"/><rect x="8" y="19" width="8" height="2" rx="1"/><rect x="6.3" y="1.8" width="2" height="4.4" rx="1" transform="rotate(-25 7.3 4)"/><rect x="15.7" y="1.8" width="2" height="4.4" rx="1" transform="rotate(25 16.7 4)"/></svg>',
			'desc' => "Pose, orientation, dépannage de réception",
		),
		'parabole-satellite' => array(
			'icon' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 16c0-7.2 5.8-13 13-13v3c-5.5 0-10 4.5-10 10H3Z"/><circle cx="19" cy="5" r="1.7"/><rect x="10.5" y="16" width="3" height="5" rx="1"/><rect x="8" y="20" width="8" height="2" rx="1"/></svg>',
			'desc' => 'Installation et réglage de parabole',
		),
		'starlink' => array(
			'icon' => '<svg viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="2" width="14" height="9" rx="2" transform="rotate(-15 12 6.5)"/><rect x="11" y="12" width="2" height="7" rx="1"/><rect x="8" y="19" width="8" height="2" rx="1"/></svg>',
			'desc' => 'Étude de dégagement et pose du kit',
		),
		'videosurveillance' => array(
			'icon' => '<svg viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="7" width="14" height="10" rx="3"/><circle cx="9" cy="12" r="2.8" fill="var(--navy)"/><path d="M16 10.5 22 7v10l-6-3.5Z"/></svg>',
			'desc' => 'Caméras intérieures et extérieures',
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
