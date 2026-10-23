<?php
/**
 * Real, conversion-focused French copy for every page, as Gutenberg block
 * markup so it's fully editable in the block editor after activation.
 *
 * Structural chunks (icon grids, cards, chips) use a `core/html` block —
 * that block stores raw HTML verbatim with zero attribute-schema risk, so
 * the layout can never show a "this block contains unexpected content"
 * warning. The actual prose — headings, paragraphs, lists, FAQ answers —
 * uses real core blocks (heading/paragraph/list/details) so those are the
 * parts edited inline, which is what changes most often in practice.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function antenniste91_html_block( $html ) {
	return "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->\n\n";
}

function antenniste91_heading_block( $text, $level = 2, $class = '', $id = '' ) {
	$class_attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';
	$id_attr    = $id ? ' id="' . esc_attr( $id ) . '"' : '';
	$json       = $class ? ',"className":"' . esc_attr( $class ) . '"' : '';
	return "<!-- wp:heading {\"level\":$level$json} -->\n<h$level$id_attr$class_attr>$text</h$level>\n<!-- /wp:heading -->\n\n";
}

/** Tinted, left-bordered intro box — a pull-quote style opener, not a plain paragraph. */
function antenniste91_callout_block( $text ) {
	return antenniste91_html_block( '<div class="callout"><p>' . $text . '</p></div>' );
}

/** 2-column grid of small bordered cards with a check dot, instead of a plain bullet list. */
function antenniste91_card_list_block( $items ) {
	$html = '<div class="signal-grid">';
	foreach ( $items as $item ) {
		$html .= '<div class="signal-card"><span class="dot"></span><p>' . $item . '</p></div>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
}

/** Placeholder photo blocks (1 or 2 across) so the page has visual breathing room before real photos arrive. */
/** Placeholder inline (icône + légende) pour un emplacement photo dont on n'a pas encore de vraie image. */
function antenniste91_photo_ph_inner( $caption ) {
	return '<div class="photo-ph"><svg viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="7" width="16" height="12" rx="3"/><circle cx="10" cy="13" r="3.2" fill="var(--paper-2)"/><path d="M18 11l4-2.5v9L18 15Z"/></svg><span>Photo à ajouter : ' . esc_html( $caption ) . '</span></div>';
}

function antenniste91_photo_placeholder_block( $captions ) {
	$cols = count( $captions ) >= 2 ? 2 : 1;
	$html = '<div class="photo-grid cols-' . $cols . '">';
	foreach ( $captions as $caption ) {
		$html .= '<div class="photo-ph"><svg viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="7" width="16" height="12" rx="3"/><circle cx="10" cy="13" r="3.2" fill="var(--paper-2)"/><path d="M18 11l4-2.5v9L18 15Z"/></svg><span>Photo à ajouter : ' . esc_html( $caption ) . '</span></div>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
}

/** Une vraie photo, à la place d'un emplacement en attente, dans le même format que les placeholders qu'elle remplace. */
function antenniste91_photo_block( $filename, $alt ) {
	$src  = get_template_directory_uri() . '/assets/photos/' . $filename;
	$html = '<div class="photo-grid cols-1"><div class="photo-real"><img src="' . esc_url( $src ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy"></div></div>';
	return antenniste91_html_block( $html );
}

/**
 * Comparateur avant/après à curseur : une seule photo glisse pour révéler
 * l'autre. Prend une LISTE de paires (avant/après/légende) et les affiche
 * dans un carrousel qu'on peut parcourir au doigt, à la souris ou avec les
 * flèches, pour pouvoir en ajouter d'autres au fur et à mesure.
 */
function antenniste91_avant_apres_block( $items ) {
	$uri  = get_template_directory_uri() . '/assets/photos/';
	$html = '<div class="ba-grid">';
	foreach ( $items as $item ) {
		$before = $item['before'];
		$after  = $item['after'];
		$html .= '<div class="ba-wrap">'
			. '<div class="ba-slider">'
			. '<img class="ba-img ba-after" src="' . esc_url( $uri . $after['file'] ) . '" alt="' . esc_attr( $after['alt'] ) . '" loading="lazy">'
			. '<img class="ba-img ba-before" src="' . esc_url( $uri . $before['file'] ) . '" alt="' . esc_attr( $before['alt'] ) . '" loading="lazy">'
			. '<span class="ba-tag ba-tag-before">Avant</span>'
			. '<span class="ba-tag ba-tag-after">Après</span>'
			. '<div class="ba-handle" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6-6 6 6 6M15 6l6 6-6 6"/></svg></div>'
			. '<input type="range" class="ba-range" min="0" max="100" value="50" aria-label="Faire glisser pour comparer avant et après">'
			. '</div>'
			. '<p class="ba-caption">'
			. ( ! empty( $item['title'] ) ? '<strong>' . esc_html( $item['title'] ) . '</strong>' : '' )
			. ( ! empty( $item['caption'] ) ? '<span>' . esc_html( $item['caption'] ) . '</span>' : '' )
			. '</p>'
			. '</div>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
}

/** Données partagées (accueil + page antenne-tv) pour le(s) comparateur(s) avant/après antenne. */
function antenniste91_antenne_avant_apres_items() {
	return array(
		array(
			'title'   => "Remplacement d'une antenne de toit",
			'before'  => array( 'file' => 'antenne-avant.jpg', 'alt' => 'Grande antenne râteau multi-éléments avant remplacement' ),
			'after'   => array( 'file' => 'antenne-apres.jpg', 'alt' => 'Petite antenne compacte installée en remplacement' ),
			'caption' => 'Remplacement par une antenne plus compacte, en toiture.',
		),
		array(
			'title'   => "Réglage d'une antenne de toit",
			'before'  => array( 'file' => 'antenne-reglage-avant.jpg', 'alt' => 'Antenne de toit avant réglage' ),
			'after'   => array( 'file' => 'antenne-reglage-apres.jpg', 'alt' => 'Antenne de toit réorientée après réglage' ),
			'caption' => "Réorientation d'une antenne existante pour retrouver un signal stable.",
		),
	);
}

/** Highlighted price callout, distinct from the rest of the prose. */
function antenniste91_price_box_block( $text ) {
	return antenniste91_html_block( '<div class="price-box"><div class="icon">€</div><p>' . $text . '</p></div>' );
}

/**
 * "Explore the [device]" style showcase: a large illustration centered in a
 * soft glow, flanked by feature call-outs — the product-page treatment the
 * plain bullet lists were missing.
 */
/** Grille de cartes à icône pour "Ce qui est inclus" sur les pages produits : même langage visuel que les cartes "Pourquoi nous" de l'accueil. */
function antenniste91_feature_cards_block( $items ) {
	$icons = array(
		'<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
		'<path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L4 17l3 3 5.3-5.3a4 4 0 0 1 5.4-5.4L21 6l-3-3-3.3 3.3Z"/>',
		'<polyline points="20 6 9 17 4 12"/>',
		'<path d="M12 3 4.5 6v6c0 5 3.3 8.4 7.5 9 4.2-.6 7.5-4 7.5-9V6L12 3Z"/><path d="m9 12 2 2 4-4"/>',
	);
	$html = '<div class="feat-grid">';
	foreach ( $items as $i => $item ) {
		$icon = $icons[ $i % count( $icons ) ];
		$html .= '<div class="feat-card"><div class="feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $icon . '</svg></div><h4>' . $item[0] . '</h4><p>' . $item[1] . '</p></div>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
}

function antenniste91_paragraph_block( $text, $class = '' ) {
	$class_attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';
	$json       = $class ? ' {"className":"' . esc_attr( $class ) . '"}' : '';
	return "<!-- wp:paragraph$json -->\n<p$class_attr>$text</p>\n<!-- /wp:paragraph -->\n\n";
}

function antenniste91_list_block( $items, $class = 'check-list' ) {
	$out = "<!-- wp:list {\"className\":\"$class\"} -->\n<ul class=\"wp-block-list $class\">\n";
	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>$item</li>\n<!-- /wp:list-item -->\n";
	}
	$out .= "</ul>\n<!-- /wp:list -->\n\n";
	return $out;
}

function antenniste91_details_block( $summary, $answer ) {
	$summary_json = str_replace( '"', '\\"', $summary );
	return "<!-- wp:details {\"summary\":\"$summary_json\"} -->\n<details class=\"wp-block-details\"><summary>$summary</summary>\n<!-- wp:paragraph -->\n<p>$answer</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->\n\n";
}

function antenniste91_button_block( $text, $url, $style = '' ) {
	$class = $style ? ' is-style-' . $style : '';
	return "<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button {\"className\":\"$style\"} -->\n<div class=\"wp-block-button$class\"><a class=\"wp-block-button__link wp-element-button\" href=\"" . esc_url( $url ) . "\">$text</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n\n";
}

/* =========================================================
   Reusable HTML chunks
   ========================================================= */

function antenniste91_chunk_services_grid() {
	$services = array(
		array(
			'title' => "Installation d'antenne",
			'text'  => "Antenne TV, parabole satellite : chaînes qui sautent, image pixellisée, panne après une tempête. On identifie la cause et on répare en une seule visite.",
			'url'   => home_url( '/services/antenne-tv/' ),
			'photo' => 'antenne-toit.jpg',
			'alt'   => "Antenne râteau installée sur un toit, ciel dégagé",
		),
		array(
			'title' => 'Internet Starlink',
			'text'  => "Pas de fibre chez vous ? Étude de dégagement du ciel, pose du kit et mise en réseau, pour un débit qui tient dans la durée.",
			'url'   => home_url( '/services/starlink/' ),
			'photo' => 'starlink-mur.jpg',
			'alt'   => "Antenne Starlink installée sur un mur, en hauteur",
		),
		array(
			'title' => 'Vidéosurveillance',
			'text'  => "Caméras intérieures et extérieures consultables depuis votre téléphone, pour surveiller votre bien à distance.",
			'url'   => home_url( '/services/videosurveillance/' ),
			'photo' => 'camera-interieure.png',
			'alt'   => "Caméra de vidéosurveillance intérieure",
		),
		array(
			'title' => 'Alarme &amp; visiophone',
			'text'  => "Voyez qui sonne avant d'ouvrir, même à distance : visiophone et alarme installés et expliqués pour votre maison ou votre établissement.",
			'url'   => home_url( '/services/visiophone/' ),
			'photo' => 'visiophone-entree.jpg',
			'alt'   => "Visiophone installé près d'une porte d'entrée",
		),
	);

	$assets_uri = get_template_directory_uri() . '/assets/photos/';
	$html = '<div class="services-grid">';
	foreach ( $services as $s ) {
		$photo_html = $s['photo']
			? '<img src="' . esc_url( $assets_uri . $s['photo'] ) . '" alt="' . esc_attr( $s['alt'] ) . '" loading="lazy" width="400" height="280">'
			: antenniste91_photo_ph_inner( $s['alt'] );
		$html .= '<a class="card" href="' . esc_url( $s['url'] ) . '">'
			. '<div class="card-photo">' . $photo_html . '</div>'
			. '<div class="card-body">'
			. '<h3>' . $s['title'] . '</h3>'
			. '<p>' . $s['text'] . '</p>'
			. '<span class="link-arrow">En savoir plus →</span>'
			. '</div></a>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
}

/** "Pourquoi nous choisir" : une seule section concrète (pas deux qui se répètent), avec les 4 points qui répondent vraiment à la question côté client. */
function antenniste91_chunk_expertise() {
	$icon_diag    = '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>';
	$icon_prix    = '<path d="M3 11V6a2 2 0 0 1 2-2h5l11 11-7 7L3 11Z"/><circle cx="8" cy="8" r="1.2" fill="currentColor" stroke="none"/>';
	$icon_install = '<path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L4 17l3 3 5.3-5.3a4 4 0 0 1 5.4-5.4L21 6l-3-3-3.3 3.3Z"/>';
	$icon_garantie = '<path d="M12 3 4.5 6v6c0 5 3.3 8.4 7.5 9 4.2-.6 7.5-4 7.5-9V6L12 3Z"/><path d="m9 12 2 2 4-4"/>';
	$items = array(
		array( $icon_diag,     'Diagnostic avant tout devis', "Vous décrivez la panne ou le projet, on cerne la cause probable, gratuitement et sans engagement." ),
		array( $icon_prix,     'Prix ferme, sans surprise', "Un devis détaillé transmis avant intervention : rien n'est engagé tant qu'il n'est pas validé." ),
		array( $icon_install,  'Installation testée devant vous', "Chaînes, débit ou image caméra vérifiés en direct avant que le technicien ne reparte." ),
		array( $icon_garantie, 'Garantie &amp; SAV inclus', "Une question après la pose ? Le même technicien répond, il connaît déjà votre installation." ),
	);
	$assets_uri = get_template_directory_uri() . '/assets/photos/';
	$html = '<div class="whyus-band"><div class="whyus-grid">'
		. '<div class="whyus-photo"><img src="' . esc_url( $assets_uri . 'starlink-mur.jpg' ) . '" alt="Antenne Starlink installée sur un mur, en hauteur" loading="lazy"></div>'
		. '<div class="whyus-cards">';
	foreach ( $items as $item ) {
		$html .= '<div class="whyus-card"><div class="whyus-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $item[0] . '</svg></div><h4>' . $item[1] . '</h4><p>' . $item[2] . '</p></div>';
	}
	$html .= '</div></div></div>';
	return antenniste91_html_block( $html );
}

function antenniste91_chunk_audience() {
	$icon_tv     = antenniste91_mini_icon_paths( 'antenne' );
	$icon_star   = antenniste91_mini_icon_paths( 'starlink' );
	$icon_cam    = antenniste91_mini_icon_paths( 'camera' );
	$icon_house  = antenniste91_mini_icon_paths( 'maison' );
	$icon_person = antenniste91_mini_icon_paths( 'personne' );

	$particulier_items = array(
		array( $icon_tv, "Chaînes qui sautent, image pixellisée ou neige à l'écran" ),
		array( $icon_star, "Zone mal desservie par la fibre : installation Starlink" ),
		array( $icon_cam, "Caméra pour surveiller l'entrée ou le jardin à distance" ),
		array( $icon_house, "Remise en service après tempête ou déménagement" ),
	);
	$pro_items = array(
		array( $icon_tv, "Antenne collective ou multiplex TV pour chambres et salles communes" ),
		array( $icon_star, "Starlink principal ou de secours sur un ou plusieurs sites" ),
		array( $icon_cam, "Vidéosurveillance des accès, parkings et zones sensibles" ),
		array( $icon_person, "Un interlocuteur unique pour vos devis-cadres et marchés" ),
	);

	$render_list = function( $items ) {
		$out = '<ul class="feature-list">';
		foreach ( $items as $item ) {
			$out .= '<li><span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $item[0] . '</svg></span><span>' . $item[1] . '</span></li>';
		}
		$out .= '</ul>';
		return $out;
	};

	$html = '<div class="audience-split">'
		. '<div class="aud-card"><span class="tag">Maison individuelle</span><h3>Particuliers</h3>'
		. $render_list( $particulier_items )
		. '<br/><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler pour mon projet</a></div>'
		. '<div class="aud-card pro"><span class="tag">Hôtels · Commerces · Collectivités</span><h3>Professionnels &amp; collectivités</h3>'
		. $render_list( $pro_items )
		. '<br/><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler pour un devis pro</a></div>'
		. '</div>';
	return antenniste91_html_block( $html );
}

function antenniste91_reviews_stars() {
	return '<div class="stars">' . str_repeat( '<svg viewBox="0 0 24 24" fill="currentColor"><path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.9L12 17.8 5.8 21.1 7 14.2l-5-4.9 6.9-1Z"/></svg>', 5 ) . '</div>';
}

/**
 * Affiche les vrais avis Google si un plugin est connecté (shortcode saisi
 * dans Personnaliser > Coordonnées & réglages), sinon un seul bloc d'attente
 * clairement identifié comme tel — jamais de fausses citations qui
 * imiteraient de vrais avis.
 */
function antenniste91_chunk_reviews_teaser() {
	$embed = get_theme_mod( 'antenniste91_reviews_embed', '' );
	$rating_line = '<div class="rating-strip">' . antenniste91_reviews_stars() . '<strong>' . antenniste91_google_rating() . '/5</strong> ' . antenniste91_review_count_label() . '</div>';

	if ( $embed ) {
		$html = $rating_line . '<div class="reviews-embed">' . do_shortcode( $embed ) . '</div>';
	} else {
		$html = $rating_line . '<div class="reviews-pending"><p>Les avis Google s\'afficheront ici une fois le plugin d\'avis connecté (ex. « Reviews Feed for Google »), via Personnaliser &rarr; Coordonnées &amp; réglages.</p></div>';
	}
	return antenniste91_html_block( $html );
}

/**
 * Vraie carte des communes de l'Essonne (91), fournie par la cliente,
 * utilisée telle quelle, avec quelques repères animés (pastille rouge
 * qui pulse) positionnés sur les villes principales.
 */
function antenniste91_essonne_map_image() {
	$image_url = esc_url( get_template_directory_uri() . '/assets/photos/carte-essonne.webp' );
	$markers   = array(
		array( 75, 29, 'Évry-Courcouronnes' ),
		array( 48, 10, 'Massy' ),
		array( 39, 70, 'Étampes' ),
	);
	$html = '<div class="essonne-map"><img src="' . $image_url . '" alt="Carte des communes du département de l\'Essonne (91)" loading="lazy">';
	foreach ( $markers as $m ) {
		list( $x, $y, $label ) = $m;
		$html .= '<span class="essonne-marker" style="left:' . $x . '%; top:' . $y . '%;" title="' . esc_attr( $label ) . '"><span class="ping"></span></span>';
	}
	$html .= '</div>';
	return $html;
}

function antenniste91_chunk_zone_chips() {
	$towns = array( 'Évry-Courcouronnes', 'Corbeil-Essonnes', 'Massy', 'Palaiseau', 'Étampes', 'Savigny-sur-Orge', 'Sainte-Geneviève-des-Bois', 'Draveil', 'Brétigny-sur-Orge', 'Longjumeau' );
	$html  = '<ul class="chips">';
	foreach ( $towns as $t ) {
		$html .= '<li>' . esc_html( $t ) . '</li>';
	}
	$html .= '</ul>';
	return antenniste91_html_block( $html );
}

/* =========================================================
   Page content
   ========================================================= */

function antenniste91_content_home() {
	$c  = antenniste91_html_block( '<span class="eyebrow">Ce que nous installons</span>' );
	$c .= antenniste91_heading_block( 'Quatre métiers, un seul technicien.' );
	$c .= antenniste91_paragraph_block( "Installateur antenne TV, parabole satellite, Starlink, vidéosurveillance et visiophone en Essonne (91) : de l'antenne râteau au visiophone connecté, chaque intervention est diagnostiquée avant devis, pour particuliers, professionnels et collectivités." );
	$c .= antenniste91_chunk_services_grid();

	$c .= antenniste91_html_block( '<div class="about-grid"><div>'
		. '<span class="eyebrow">Les experts en installation et dépannage</span>'
		. '<h2>À propos de ' . esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ) . '</h2>'
		. '<p>' . esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ) . ' installe et dépanne antennes, paraboles, caméras, alarmes et visiophones en Essonne. Notre mission : une connectivité et une sécurité fiables, expliquées simplement, pour les particuliers comme pour les professionnels.</p>'
		. '<p>Chaque intervention commence par un diagnostic clair et se termine par une explication en face à face, jamais un rapport technique incompréhensible. Notre équipe maintient une note de <strong>' . antenniste91_google_rating() . '/5 sur Google</strong> (' . antenniste91_review_count_label() . '), reflet de la confiance de nos clients.</p>'
		. '<div class="hero-ctas" style="margin-top:6px;"><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler maintenant</a></div>'
		. '</div><div class="about-photo-grid">'
		. '<div class="about-photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/photos/starlink-toit.jpg' ) . '" alt="Installation Starlink sur un toit-terrasse" loading="lazy"></div>'
		. '<div class="about-photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/photos/visiophone-entree.jpg' ) . '" alt="Visiophone installé près d\'une porte d\'entrée" loading="lazy"></div>'
		. '<div class="about-photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/photos/camera-app.png' ) . '" alt="Consultation des caméras depuis une application" loading="lazy"></div>'
		. '<div class="about-photo"><img src="' . esc_url( get_template_directory_uri() . '/assets/photos/parabole-toit.jpg' ) . '" alt="Parabole et antenne installées en toiture" loading="lazy"></div>'
		. '</div></div>'
	);

	$c .= antenniste91_html_block( '<span class="eyebrow">Pourquoi nous</span>' );
	$c .= antenniste91_heading_block( 'Pourquoi choisir ' . esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ) . ' ?' );
	$c .= antenniste91_chunk_expertise();

	$c .= antenniste91_html_block( '<span class="eyebrow">Nos interventions</span>' );
	$c .= antenniste91_heading_block( 'Avant / après.' );
	$c .= antenniste91_avant_apres_block( antenniste91_antenne_avant_apres_items() );

	$c .= antenniste91_html_block( '<span class="eyebrow">Pour qui</span>' );
	$c .= antenniste91_heading_block( 'Particuliers &amp; professionnels.' );
	$c .= antenniste91_chunk_audience();

	$c .= antenniste91_html_block( '<span class="eyebrow">Zone d\'intervention</span>' );
	$c .= antenniste91_heading_block( "Toute l'Essonne (91), sans exception." );
	$c .= antenniste91_html_block(
		'<div class="zone-grid"><div>'
		. '<p>Nous intervenons dans l\'ensemble du département de l\'Essonne et les communes limitrophes, déplacement inclus dans le devis, quelle que soit la commune.</p>'
		. antenniste91_chunk_zone_chips()
		. '<a class="link-arrow" href="' . esc_url( home_url( '/zone-intervention/' ) ) . '">Voir le détail des secteurs →</a>'
		. '</div><div class="zone-map-wrap">' . antenniste91_essonne_map_image() . '</div></div>'
	);

	$c .= antenniste91_html_block( '<span class="eyebrow">Avis clients</span>' );
	$c .= antenniste91_heading_block( "Ce qu'en disent les clients." );
	$c .= antenniste91_chunk_reviews_teaser();
	$c .= antenniste91_button_block( 'Voir tous les avis →', home_url( '/avis-clients/' ), 'outline-navy' );

	$c .= antenniste91_html_block(
		antenniste91_final_cta_html( 'Prêt à commencer', 'Un appel suffit pour lancer le diagnostic.', 'Devis gratuit, sans engagement.' )
	);

	return $c;
}

/** Fixed table-of-contents entries — identical structure across all 4 service pages. */
function antenniste91_service_toc() {
	return array(
		array( '#signes', 'Les signes à surveiller' ),
		array( '#inclus', "Ce qui est inclus" ),
		array( '#methode', 'Notre méthode' ),
		array( '#prix', 'Le prix' ),
		array( '#faq', 'Questions fréquentes' ),
	);
}

/**
 * Petits pictogrammes (24×24, trait) utilisés partout où une icône de
 * service apparaît en taille réduite — menu, cartes, listes. Même
 * vocabulaire visuel que les grandes illustrations ci-dessus (silhouettes
 * littérales), pour que l'antenne, la parabole, Starlink et la caméra se
 * reconnaissent au premier coup d'œil même en petit.
 */
function antenniste91_mini_icon_paths( $key ) {
	$icons = array(
		'antenne'  => '<path d="M3 12h15"/><path d="M6 7v10"/><path d="M8.5 8v8"/><path d="M11 9v6"/><path d="M13.5 9.8v4.4"/><path d="M11 12v8"/><path d="M8.5 20h5"/>',
		'parabole' => '<ellipse cx="10" cy="10" rx="7" ry="5" transform="rotate(-18 10 10)"/><path d="M10 10 18 6"/><circle cx="18" cy="6" r="1.3" fill="currentColor" stroke="none"/><path d="M10 15v6"/><path d="M7 21h6"/>',
		'starlink' => '<rect x="4" y="2" width="13" height="7.5" rx="1.3" transform="rotate(-14 10.5 5.5)"/><path d="M11 10v10"/><path d="M8 20h6"/>',
		'camera'   => '<rect x="2" y="8" width="12" height="8" rx="2.2"/><circle cx="8" cy="12" r="2.5"/><circle cx="8" cy="12" r=".9" fill="currentColor" stroke="none"/><path d="M14 10 20 7v10l-6-3.5Z"/><path d="M5 8V6a1.5 1.5 0 0 1 1.5-1.5H9"/>',
		'visiophone' => '<rect x="7" y="2" width="10" height="20" rx="2.5"/><circle cx="12" cy="7.5" r="1.5" fill="currentColor" stroke="none"/><circle cx="12" cy="16" r="2.2"/><path d="M4.5 9a7.5 7.5 0 0 1 0 6" opacity=".6"/>',
		'maison'   => '<path d="M3 11 12 4l9 7"/><path d="M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9"/>',
		'personne' => '<circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.6 3.1-6 7-6s7 2.4 7 6"/>',
	);
	return isset( $icons[ $key ] ) ? $icons[ $key ] : '';
}

/** Icône 24×24 complète (trait, currentColor), prête à insérer dans le HTML. */
function antenniste91_mini_icon( $key ) {
	$paths = antenniste91_mini_icon_paths( $key );
	if ( ! $paths ) {
		return '';
	}
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths . '</svg>';
}

function antenniste91_service_page( $args ) {
	$c  = antenniste91_callout_block( $args['hook'] );
	$c .= antenniste91_heading_block( 'Ce qui est inclus', 3, '', 'inclus' );
	$c .= antenniste91_feature_cards_block( $args['included'] );
	$c .= antenniste91_heading_block( 'Les signes qui indiquent qu\'il est temps d\'appeler', 3, '', 'signes' );
	$c .= antenniste91_card_list_block( $args['signs'] );
	$c .= empty( $args['photo1_real'] )
		? antenniste91_photo_placeholder_block( array( $args['photo1'] ) )
		: antenniste91_photo_block( $args['photo1_real'], $args['photo1'] );
	$c .= antenniste91_heading_block( 'Notre méthode', 3, '', 'methode' );
	$c .= antenniste91_paragraph_block( $args['process'] );
	$c .= antenniste91_heading_block( 'Le prix', 3, '', 'prix' );
	$c .= antenniste91_price_box_block( $args['pricing'] );
	$c .= empty( $args['avant_apres'] )
		? antenniste91_photo_placeholder_block( array( 'avant l\'intervention', 'après l\'intervention' ) )
		: antenniste91_avant_apres_block( $args['avant_apres'] );
	if ( ! empty( $args['faq'] ) ) {
		$c .= antenniste91_heading_block( 'Questions fréquentes', 3, '', 'faq' );
		foreach ( $args['faq'] as $q ) {
			$c .= antenniste91_details_block( $q['q'], $q['a'] );
		}
	}
	return $c;
}

function antenniste91_content_service_antenne() {
	return antenniste91_service_page(
		array(
			'hook'     => "Une chaîne qui saute, une image qui se pixellise, une neige persistante à l'écran : dans neuf cas sur dix, le problème vient de l'antenne, du câble ou de l'amplificateur, pas de la télévision. On identifie la cause exacte avant de réparer, pour ne pas payer deux fois.",
			'signs'    => array(
				"Certaines chaînes manquent ou disparaissent selon la météo",
				"L'image se pixellise ou l'écran affiche « signal faible »",
				"Aucune image sur une ou plusieurs prises TV du logement",
				"L'antenne a été endommagée par le vent ou une tempête",
			),
			'included' => array(
				array( 'Diagnostic complet', 'Antenne, câblage, amplificateur et répartiteur vérifiés.' ),
				array( 'Réparation ciblée', "Réorientation ou remplacement de l'antenne si nécessaire." ),
				array( 'Toutes les prises testées', 'Chaque prise TV concernée est vérifiée.' ),
				array( 'Test en direct', 'Les chaînes sont contrôlées avant la fin de la visite.' ),
			),
			'process'  => "Le technicien commence par un diagnostic sur place pour localiser la cause réelle de la panne. Le devis est annoncé avant toute réparation ; l'intervention se termine par un test des chaînes, prise par prise, en votre présence.",
			'pricing'  => "Le tarif dépend de l'accès au toit, du câblage existant et du nombre de prises à vérifier. Il vous est communiqué avant l'intervention, jamais après.",
			'photo1'      => 'Antenne installée sur un toit en Essonne',
			'photo1_real' => 'antenne-toit.jpg',
			'avant_apres' => antenniste91_antenne_avant_apres_items(),
			'faq'      => array(
				array(
					'q' => "Faut-il changer toute l'antenne ou peut-on la réparer ?",
					'a' => "La plupart des pannes viennent d'un câble, d'un connecteur ou d'un amplificateur défectueux : l'antenne elle-même est rarement à remplacer entièrement. Le diagnostic sur place permet de le savoir avant de proposer une solution.",
				),
				array(
					'q' => "Vous intervenez aussi en copropriété ?",
					'a' => "Oui, sur l'antenne collective comme sur les installations individuelles. Contactez-nous pour évoquer votre configuration.",
				),
			),
		)
	);
}

function antenniste91_content_service_parabole() {
	return antenniste91_service_page(
		array(
			'hook'     => "Parabole mal orientée après un coup de vent, envie de recevoir des bouquets internationaux, ou premier équipement : une parabole bien réglée se voit au millimètre : un mauvais alignement suffit à perdre le signal.",
			'signs'    => array(
				"Le signal satellite est faible ou instable selon le temps",
				"La parabole a bougé après une tempête ou des travaux",
				"Vous voulez ajouter un bouquet ou une chaîne étrangère",
				"Un déménagement nécessite une nouvelle installation",
			),
			'included' => array(
				array( 'Support adapté', 'Fixation choisie selon la façade ou le toit.' ),
				array( 'Réglage au millimètre', "Azimut, élévation et polarisation ajustés." ),
				array( 'Bouquets testés', 'Chaque chaîne reçue est vérifiée sur place.' ),
				array( 'Câblage protégé', 'Passage propre, à l\'abri des intempéries.' ),
			),
			'process'  => "Après un premier échange pour cerner le ou les bouquets souhaités, l'installation est réalisée avec un instrument de mesure du signal, pas au jugé. Chaque chaîne est vérifiée avant la fin de la visite.",
			'pricing'  => "Le tarif dépend du support à poser, de la hauteur d'intervention et du nombre de récepteurs à raccorder. Il est annoncé avant toute pose.",
			'photo1'      => 'Parabole installée en toiture',
			'photo1_real' => 'parabole-toit.jpg',
			'faq'      => array(
				array(
					'q' => "Faut-il une autorisation pour installer une parabole en copropriété ?",
					'a' => "Selon la visibilité de l'installation depuis la voie publique, une déclaration au syndic ou à la mairie peut être nécessaire. Nous vous indiquons la marche à suivre avant la pose.",
				),
			),
		)
	);
}

function antenniste91_content_service_starlink() {
	return antenniste91_service_page(
		array(
			'hook'     => "Pas de fibre chez vous, un débit ADSL qui ne suffit plus, ou besoin d'une connexion de secours : Starlink change la donne à condition d'être installé au bon endroit, avec un dégagement du ciel qui tient dans la durée.",
			'signs'    => array(
				"Aucune offre fibre ou ADSL satisfaisante n'est disponible",
				"Le débit actuel ne suit plus (télétravail, visioconférence, streaming)",
				"Vous avez besoin d'une connexion de secours pour un site professionnel",
				"Vous avez déjà le kit mais l'installation ne tient pas ou coupe",
			),
			'included' => array(
				array( 'Étude du ciel', 'Repérage du meilleur emplacement avant tout.' ),
				array( 'Fixation durable', 'Mât, façade ou toiture, selon la configuration.' ),
				array( 'Câblage propre', "Passage soigné jusqu'au routeur." ),
				array( 'Réseau mis en service', "Wi-Fi et câblage existant raccordés." ),
			),
			'process'  => "Une étude de dégagement du ciel précède toujours la pose : c'est elle qui détermine si l'installation tiendra dans le temps. Le kit est ensuite fixé durablement, câblé proprement, et le réseau testé en votre présence.",
			'pricing'  => "L'installation est facturée indépendamment du kit Starlink. Un devis est transmis après l'étude de dégagement, avant toute pose.",
			'photo1'      => 'Kit Starlink fixé en toiture',
			'photo1_real' => 'starlink-toit.jpg',
			'faq'      => array(
				array(
					'q' => "Fournissez-vous le kit Starlink ou seulement l'installation ?",
					'a' => "Précisez-nous votre situation au moment de la prise de contact, nous vous indiquerons la formule la plus adaptée.",
				),
				array(
					'q' => "Starlink fonctionne-t-il partout en Essonne ?",
					'a' => "La couverture satellite est large, mais le dégagement du ciel autour de votre bâtiment reste déterminant. C'est justement ce que l'étude préalable permet de vérifier avant tout engagement.",
				),
			),
		)
	);
}

function antenniste91_content_service_video() {
	return antenniste91_service_page(
		array(
			'hook'     => "Surveiller une entrée, un jardin, un commerce ou un parking à distance, sans complexité technique : des caméras bien positionnées et une application claire sur votre téléphone suffisent, à condition que l'installation soit pensée pour votre configuration.",
			'signs'    => array(
				"Vous voulez surveiller une entrée, un jardin ou un local à distance",
				"Un commerce, un hôtel ou un bâtiment public a besoin d'une couverture des accès",
				"Une caméra existante ne couvre plus la bonne zone ou ne fonctionne plus",
				"Vous voulez consulter les images depuis votre smartphone, où que vous soyez",
			),
			'included' => array(
				array( 'Zones étudiées', 'Nombre de caméras adapté aux besoins réels.' ),
				array( 'Pose intérieure/extérieure', 'Caméras positionnées selon les accès sensibles.' ),
				array( 'Application configurée', 'Consultation à distance prête sur votre smartphone.' ),
				array( 'Enregistrement réglé', 'Local ou cloud, selon votre choix.' ),
			),
			'process'  => "Après une visite ou un échange pour définir les zones sensibles, les caméras sont positionnées pour couvrir l'essentiel sans multiplier le matériel. L'application de consultation à distance est configurée et testée avec vous avant la fin de l'intervention.",
			'pricing'  => "Le tarif dépend du nombre de caméras, de la complexité du câblage et du mode d'enregistrement choisi. Il est annoncé avant la pose, jamais après.",
			'photo1'      => 'Caméra de vidéosurveillance intérieure',
			'photo1_real' => 'camera-interieure.png',
			'faq'      => array(
				array(
					'q' => "Puis-je consulter les images à distance depuis mon téléphone ?",
					'a' => "Oui, une application dédiée permet de consulter les caméras en direct et de recevoir des notifications en cas de détection de mouvement, selon le modèle choisi.",
				),
				array(
					'q' => "Intervenez-vous pour des bâtiments professionnels ou publics ?",
					'a' => "Oui, hôtels, commerces et collectivités font partie des configurations que nous équipons régulièrement.",
				),
			),
		)
	);
}

function antenniste91_content_service_visiophone() {
	return antenniste91_service_page(
		array(
			'hook'     => "Voir qui sonne avant d'ouvrir, depuis son téléphone même en son absence : un visiophone bien installé, couplé si besoin à une alarme, rassure autant les particuliers en pavillon que les professionnels qui gèrent plusieurs accès.",
			'signs'    => array(
				"Vous voulez voir qui sonne au portail ou à la porte avant d'ouvrir",
				"Vous êtes souvent absent et voulez ouvrir à distance depuis votre téléphone",
				"Votre interphone actuel est en panne ou trop ancien pour être couplé à une caméra",
				"Un commerce, un hôtel ou une collectivité a besoin de contrôler plusieurs accès",
			),
			'included' => array(
				array( 'Étude de l\'accès', 'Portail, porte ou plusieurs entrées : la solution est adaptée à votre configuration.' ),
				array( 'Pose soignée', 'Platine extérieure et moniteur ou application posés et câblés proprement.' ),
				array( 'Couplage alarme possible', 'Visiophone et système d\'alarme reliés sur demande.' ),
				array( 'Prise en main expliquée', 'Vous repartez en sachant répondre, ouvrir et consulter l\'historique.' ),
			),
			'process'  => "Après un repérage de vos accès, la platine et le moniteur (ou l'application mobile) sont installés et raccordés. Chaque fonction (voir, parler, ouvrir à distance) est testée avec vous avant la fin de la visite.",
			'pricing'  => "Le tarif dépend du nombre d'accès à équiper, du type de platine choisie et d'un éventuel couplage avec une alarme existante. Il est annoncé avant toute pose.",
			'photo1'      => 'Visiophone installé près d\'une porte d\'entrée',
			'photo1_real' => 'visiophone-entree.jpg',
			'faq'      => array(
				array(
					'q' => "Puis-je ouvrir le portail depuis mon téléphone, même à l'extérieur ?",
					'a' => "Oui, selon le modèle choisi, l'ouverture à distance et la consultation de l'historique des visites sont possibles depuis une application, où que vous soyez.",
				),
				array(
					'q' => "Le visiophone peut-il remplacer mon interphone actuel sans tout refaire ?",
					'a' => "Dans la majorité des cas, le câblage existant peut être réutilisé. Un diagnostic sur place permet de le confirmer avant de proposer un devis.",
				),
			),
		)
	);
}

function antenniste91_content_zone() {
	$c  = antenniste91_html_block( '<div class="zone-map-wrap zone-map-standalone">' . antenniste91_essonne_map_image() . '</div>' );
	$c .= antenniste91_paragraph_block( "Nous intervenons dans tout le département de l'Essonne (91), sans exception, ainsi que dans les communes limitrophes. Le déplacement est toujours inclus dans le devis." );
	$c .= antenniste91_heading_block( 'Quelques communes où nous intervenons régulièrement', 3 );
	$c .= antenniste91_chunk_zone_chips();
	$c .= antenniste91_heading_block( "Votre commune n'apparaît pas dans cette liste ?", 3 );
	$c .= antenniste91_paragraph_block( "Aucune inquiétude : nous couvrons l'ensemble du département. Appelez-nous directement, nous confirmons l'intervention au téléphone." );
	$c .= antenniste91_heading_block( "Délai moyen d'intervention", 3 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Délai à préciser (ex. 24 à 48h selon les secteurs et l\'urgence).</span>' );
	return $c;
}

function antenniste91_content_avis() {
	$c  = antenniste91_html_block( antenniste91_chunk_reviews_teaser_raw() );
	$c .= antenniste91_heading_block( 'Vous êtes déjà client ?', 3 );
	$link = get_theme_mod( 'antenniste91_google_reviews_link', '' );
	$c .= antenniste91_paragraph_block(
		$link
			? 'Un avis met deux minutes à écrire et aide directement les prochains foyers ou établissements qui cherchent un installateur de confiance en Essonne. <a href="' . esc_url( $link ) . '" target="_blank" rel="noopener">Laisser un avis sur Google</a>.'
			: "Un avis met deux minutes à écrire et aide directement les prochains foyers ou établissements qui cherchent un installateur de confiance en Essonne. <span class=\"ph\">Lien vers votre fiche Google à ajouter.</span>"
	);
	return $c;
}

/** Même logique que le résumé d'accueil : vrais avis si connectés, sinon un bloc d'attente honnête. */
function antenniste91_chunk_reviews_teaser_raw() {
	$embed = get_theme_mod( 'antenniste91_reviews_embed', '' );
	$html  = '<div class="rating-strip">' . antenniste91_reviews_stars() . '<strong>' . antenniste91_google_rating() . '/5</strong> ' . antenniste91_review_count_label() . '</div>';
	if ( $embed ) {
		$html .= '<div class="reviews-embed">' . do_shortcode( $embed ) . '</div>';
	} else {
		$html .= '<div class="reviews-pending"><p>Les avis Google s\'afficheront ici une fois le plugin d\'avis connecté (ex. « Reviews Feed for Google »), via Personnaliser &rarr; Coordonnées &amp; réglages.</p></div>';
	}
	return $html;
}

function antenniste91_content_contact() {
	$c  = antenniste91_paragraph_block( "La façon la plus rapide d'obtenir une réponse : appeler directement. Décrivez votre besoin, on vous donne un premier avis et, si nécessaire, un créneau d'intervention, gratuitement." );
	$c .= antenniste91_html_block(
		'<div class="contact-phone-card"><span class="tag">Par téléphone</span><h3>' . antenniste91_phone_display() . '</h3><p>Lun–Sam · 8h–19h</p><br/><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler maintenant</a></div>'
	);
	$c .= antenniste91_heading_block( 'Ou décrivez votre besoin par écrit', 3 );
	$c .= antenniste91_paragraph_block( "Le formulaire ci-dessous envoie directement un email, sans passer par un plugin tiers." );
	return $c;
}

function antenniste91_blog_posts() {
	$posts = array();

	$posts[] = array(
		'slug'    => 'antenne-tv-perd-signal-hiver',
		'title'   => "Pourquoi mon antenne TV perd-elle le signal en hiver ?",
		'excerpt' => "Vent, gel, givre : les chutes de signal TV sont plus fréquentes en hiver. Voici les causes les plus courantes et comment les repérer avant d'appeler.",
		'content' =>
			antenniste91_paragraph_block( "Chaque hiver, le même constat revient chez de nombreux foyers d'Essonne : la télévision « saute » davantage, certaines chaînes disparaissent, l'image se pixellise. Ce n'est pas une coïncidence : le froid et le vent fragilisent des installations qui tenaient très bien le reste de l'année." )
			. antenniste91_heading_block( "Le vent déplace l'antenne, même légèrement", 2 )
			. antenniste91_paragraph_block( "Une antenne orientée au degré près peut perdre une partie du signal après plusieurs jours de vent fort. Le désalignement est parfois invisible à l'œil nu depuis le sol, mais suffisant pour faire chuter la réception." )
			. antenniste91_heading_block( "Le gel et le givre sur les câbles et connecteurs", 2 )
			. antenniste91_paragraph_block( "L'humidité qui s'infiltre dans une prise ou un connecteur mal protégé gèle et se dilate, ce qui abîme progressivement la connexion. C'est une cause fréquente de pannes qui apparaissent « sans raison » en période de gel." )
			. antenniste91_heading_block( "Comment savoir si c'est votre antenne ou votre télévision ?", 2 )
			. antenniste91_card_list_block(
				array(
					"Le problème touche toutes les télévisions du logement : c'est rarement le téléviseur",
					"Le souci apparaît surtout par mauvais temps : c'est probablement l'antenne ou le câblage extérieur",
					"Une seule prise est concernée : le souci est souvent localisé sur cette ligne précise",
				)
			)
			. antenniste91_paragraph_block( "Dans le doute, un diagnostic reste la façon la plus rapide de trancher, c'est justement ce qu'on propose avant toute réparation." )
			. antenniste91_button_block( "Voir la page Antenne TV & TNT →", home_url( '/services/antenne-tv/' ), 'outline-navy' ),
	);

	$posts[] = array(
		'slug'    => 'autorisation-parabole-satellite',
		'title'   => "Faut-il une autorisation pour installer une parabole satellite ?",
		'excerpt' => "En maison individuelle ou en copropriété, les règles ne sont pas les mêmes. Voici ce qu'il faut vérifier avant de poser une parabole.",
		'content' =>
			antenniste91_paragraph_block( "La question revient souvent, en particulier en copropriété ou en secteur protégé : peut-on installer une parabole librement, ou faut-il une autorisation au préalable ?" )
			. antenniste91_heading_block( "En maison individuelle", 2 )
			. antenniste91_paragraph_block( "En règle générale, aucune autorisation n'est nécessaire tant que l'installation reste sur votre propriété et respecte les règles locales d'urbanisme. Dans une zone classée ou protégée, la mairie peut cependant imposer des restrictions, un point à vérifier avant la pose." )
			. antenniste91_heading_block( "En copropriété", 2 )
			. antenniste91_paragraph_block( "La règle de base : chaque copropriétaire a le droit de recevoir la télévision par satellite, mais l'installation doit respecter le règlement de copropriété et, si elle est visible depuis la voie publique, peut nécessiter une déclaration au syndic." )
			. antenniste91_callout_block( "En cas de doute, on vous indique la marche à suivre selon votre configuration avant toute intervention. Mieux vaut vérifier que démonter une parabole mal placée." )
			. antenniste91_button_block( "Voir la page Parabole & satellite →", home_url( '/services/parabole-satellite/' ), 'outline-navy' ),
	);

	$posts[] = array(
		'slug'    => 'starlink-essonne-vaut-le-coup',
		'title'   => "Starlink en Essonne : dans quels cas ça vaut vraiment le coup ?",
		'excerpt' => "Starlink n'est pas réservé aux zones isolées. Voici les situations où l'investissement se justifie vraiment.",
		'content' =>
			antenniste91_paragraph_block( "Starlink a d'abord été pensé pour les zones rurales sans fibre. Mais en Essonne, on le pose de plus en plus souvent pour des raisons différentes." )
			. antenniste91_heading_block( "Les trois cas les plus fréquents", 2 )
			. antenniste91_card_list_block(
				array(
					"Aucune offre fibre ou ADSL satisfaisante n'est disponible à l'adresse",
					"Une connexion de secours est nécessaire pour un usage professionnel critique",
					"Un déménagement récent dans une zone où la fibre n'est pas encore déployée",
				)
			)
			. antenniste91_heading_block( "Ce qui détermine si ça va fonctionner : le ciel, pas la distance", 2 )
			. antenniste91_paragraph_block( "Contrairement à une idée reçue, ce n'est pas la localisation géographique qui pose problème en Essonne, mais le dégagement du ciel autour du bâtiment (arbres, toitures voisines, reliefs proches). C'est pour cette raison qu'une étude précède toujours la pose." )
			. antenniste91_button_block( "Voir la page Internet Starlink →", home_url( '/services/starlink/' ), 'outline-navy' ),
	);

	$posts[] = array(
		'slug'    => 'videosurveillance-domicile-ce-quil-faut-savoir',
		'title'   => "Vidéosurveillance à domicile : ce qu'il faut savoir avant d'installer",
		'excerpt' => "Nombre de caméras, enregistrement, vie privée du voisinage : les questions à se poser avant d'équiper sa maison.",
		'content' =>
			antenniste91_paragraph_block( "Installer des caméras chez soi semble simple sur le papier. En pratique, quelques choix mal anticipés peuvent rendre l'installation inutile ou, pire, problématique sur le plan légal." )
			. antenniste91_heading_block( "Ne filmez pas la voie publique ni le jardin du voisin", 2 )
			. antenniste91_paragraph_block( "Une caméra orientée sur l'espace privé d'un tiers ou sur la rue peut poser un problème légal, même installée de bonne foi. Le positionnement se réfléchit avant la pose, pas après une plainte." )
			. antenniste91_heading_block( "Combien de caméras sont vraiment nécessaires ?", 2 )
			. antenniste91_paragraph_block( "Plus n'est pas toujours mieux. Une entrée principale et un accès secondaire bien couverts valent souvent mieux que cinq caméras mal positionnées. C'est l'objet du diagnostic initial : couvrir l'essentiel sans multiplier le matériel ni le coût, sans raison." )
			. antenniste91_heading_block( "Cloud ou enregistrement local ?", 2 )
			. antenniste91_card_list_block(
				array(
					"Le stockage local évite un abonnement mensuel mais dépend d'un support physique à protéger",
					"Le cloud sécurise les images hors du logement mais implique un coût récurrent",
				)
			)
			. antenniste91_button_block( "Voir la page Vidéosurveillance →", home_url( '/services/videosurveillance/' ), 'outline-navy' ),
	);

	$posts[] = array(
		'slug'    => 'copropriete-reparation-antenne-collective',
		'title'   => "Copropriété : qui doit payer la réparation de l'antenne collective ?",
		'excerpt' => "Panne sur l'antenne d'un immeuble : à qui revient la prise en charge, et comment agir sans attendre l'assemblée générale ?",
		'content' =>
			antenniste91_paragraph_block( "Quand l'antenne collective d'un immeuble tombe en panne, la question du « qui paie » arrive presque aussi vite que la perte de signal." )
			. antenniste91_heading_block( "Un élément commun, une charge commune", 2 )
			. antenniste91_paragraph_block( "L'antenne collective fait en général partie des parties communes : sa réparation relève donc de la copropriété, financée par les charges, et non d'un copropriétaire en particulier." )
			. antenniste91_heading_block( "Faut-il attendre l'assemblée générale ?", 2 )
			. antenniste91_paragraph_block( "Pas nécessairement. Une panne totale de réception peut relever de l'urgence, permettant au syndic de faire intervenir un professionnel sans attendre le prochain vote, à condition de pouvoir justifier la nécessité de l'intervention." )
			. antenniste91_callout_block( "On peut établir un diagnostic et un devis rapidement, pour donner au syndic les éléments nécessaires à sa décision." )
			. antenniste91_button_block( "Demander un diagnostic →", home_url( '/contact/' ), 'outline-navy' ),
	);

	return $posts;
}

function antenniste91_content_confidentialite() {
	$c  = antenniste91_paragraph_block( "Cette page explique quelles données sont collectées sur ce site, pourquoi, et comment les faire modifier ou supprimer." );
	$c .= antenniste91_heading_block( 'Responsable du traitement', 2 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Raison sociale à compléter</span>, contact : ' . antenniste91_phone_display() . ', <span class="ph">email à compléter</span>.' );
	$c .= antenniste91_heading_block( 'Quelles données sont collectées', 2 );
	$c .= antenniste91_list_block(
		array(
			"Les informations transmises volontairement via le formulaire de contact (nom, téléphone, email, message)",
			"Des données de navigation techniques nécessaires au bon fonctionnement du site (voir la section Cookies)",
		)
	);
	$c .= antenniste91_heading_block( 'Pourquoi ces données sont collectées', 2 );
	$c .= antenniste91_paragraph_block( "Uniquement pour répondre à votre demande (devis, question, rappel). Ces informations ne sont ni revendues, ni transmises à des tiers à des fins commerciales." );
	$c .= antenniste91_heading_block( 'Durée de conservation', 2 );
	$c .= antenniste91_paragraph_block( "Les demandes de contact sont conservées le temps nécessaire au traitement de votre demande, puis supprimées, sauf obligation légale de conservation plus longue (facturation, comptabilité)." );
	$c .= antenniste91_heading_block( 'Cookies', 2 );
	$c .= antenniste91_paragraph_block( "Ce site utilise des cookies strictement nécessaires à son fonctionnement, et, uniquement si vous y consentez via le bandeau affiché lors de votre première visite, un outil de mesure d'audience. Vous pouvez modifier votre choix à tout moment en effaçant les cookies de votre navigateur." );
	$c .= antenniste91_heading_block( 'Vos droits', 2 );
	$c .= antenniste91_paragraph_block( "Conformément au RGPD, vous disposez d'un droit d'accès, de rectification, de suppression et d'opposition sur vos données. Pour l'exercer, contactez-nous aux coordonnées ci-dessus. Vous pouvez également introduire une réclamation auprès de la CNIL (cnil.fr)." );
	$c .= antenniste91_heading_block( 'Hébergement', 2 );
	$c .= antenniste91_paragraph_block( 'Les données transitant par ce site sont hébergées par <span class="ph">Ionos (à confirmer)</span>.' );
	$c .= antenniste91_paragraph_block( '<span class="ph">Cette page doit être relue et validée avec un professionnel du droit avant mise en ligne.</span>' );
	return $c;
}

function antenniste91_content_cgu() {
	$c  = antenniste91_paragraph_block( "Les présentes conditions générales d'utilisation régissent l'accès et l'usage de ce site." );
	$c .= antenniste91_heading_block( "Objet du site", 2 );
	$c .= antenniste91_paragraph_block( "Ce site présente les services de " . esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ) . " (antenne TV, parabole, Starlink, vidéosurveillance, visiophone) et permet de demander un devis ou de prendre contact. Les informations qu'il contient (tarifs indicatifs, délais) sont données à titre général et confirmées au cas par cas lors du diagnostic." );
	$c .= antenniste91_heading_block( "Propriété intellectuelle", 2 );
	$c .= antenniste91_paragraph_block( "Les textes, images, logo et éléments graphiques de ce site sont la propriété de " . esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ) . ", sauf mention contraire, et ne peuvent être reproduits sans autorisation." );
	$c .= antenniste91_heading_block( "Disponibilité du site", 2 );
	$c .= antenniste91_paragraph_block( "Le site est accessible 24h/24, sauf interruption pour maintenance ou cas de force majeure. Aucune garantie de disponibilité continue n'est apportée." );
	$c .= antenniste91_heading_block( "Liens externes", 2 );
	$c .= antenniste91_paragraph_block( "Ce site peut contenir des liens vers des sites tiers. Nous ne sommes pas responsables de leur contenu." );
	$c .= antenniste91_heading_block( "Droit applicable", 2 );
	$c .= antenniste91_paragraph_block( "Les présentes conditions sont soumises au droit français. En cas de litige, une solution amiable sera recherchée avant toute action judiciaire." );
	$c .= antenniste91_heading_block( "Modification des CGU", 2 );
	$c .= antenniste91_paragraph_block( "Ces conditions peuvent être mises à jour à tout moment ; la version en vigueur est celle publiée sur cette page." );
	$c .= antenniste91_paragraph_block( '<span class="ph">Cette page doit être relue et validée avec un professionnel du droit avant mise en ligne.</span>' );
	return $c;
}

function antenniste91_content_mentions_legales() {
	$c  = antenniste91_heading_block( 'Éditeur du site', 3 );
	$c .= antenniste91_paragraph_block( 'Raison sociale : <span class="ph">à compléter</span><br>Forme juridique : <span class="ph">à compléter</span><br>SIRET : <span class="ph">à compléter</span><br>Adresse : <span class="ph">à compléter</span><br>Téléphone : ' . antenniste91_phone_display() . '<br>Email : <span class="ph">à compléter</span>' );
	$c .= antenniste91_heading_block( 'Hébergement', 3 );
	$c .= antenniste91_paragraph_block( 'Le site est hébergé par : <span class="ph">Ionos, coordonnées complètes à ajouter</span>' );
	$c .= antenniste91_heading_block( 'Directeur de la publication', 3 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Nom à compléter</span>' );
	$c .= antenniste91_heading_block( 'Données personnelles', 3 );
	$c .= antenniste91_paragraph_block( "Les informations transmises via ce site (téléphone, email) ne sont utilisées que pour répondre à votre demande. <span class=\"ph\">Cette section doit être complétée ou validée avec un professionnel du droit avant mise en ligne.</span>" );
	return $c;
}

/**
 * Bandeau d'appel final (404, contact, pages génériques, articles) : photo
 * réelle entourée de deux formes douces (accent + marine), texte et bouton
 * d'appel. Un seul helper pour ne pas répéter ce bloc dans 4 templates.
 */
function antenniste91_final_cta_html( $eyebrow, $heading, $lead = '' ) {
	$html  = '<div class="inner-cta">';
	$html .= '<span class="eyebrow inv">' . esc_html( $eyebrow ) . '</span><h2>' . esc_html( $heading ) . '</h2>';
	if ( $lead ) {
		$html .= '<p>' . esc_html( $lead ) . '</p>';
	}
	$html .= '<div class="hero-ctas"><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">';
	$html .= '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.7 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.2 1.2.4 2.5.6 3.9.6.6 0 1.1.5 1.1 1.1v3.8c0 .6-.5 1.1-1.1 1.1C9.8 21.6 2.4 14.2 2.4 2.5c0-.6.5-1.1 1.1-1.1H7.3c.6 0 1.1.5 1.1 1.1 0 1.4.2 2.7.6 3.9.1.4 0 .8-.3 1.1L6.6 10.8z"/></svg>';
	$html .= 'Appeler maintenant</a></div></div>';
	return $html;
}

function antenniste91_render_final_cta( $eyebrow, $heading, $lead = '' ) {
	echo antenniste91_final_cta_html( $eyebrow, $heading, $lead );
}
