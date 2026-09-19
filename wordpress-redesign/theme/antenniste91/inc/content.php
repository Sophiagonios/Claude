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
function antenniste91_photo_placeholder_block( $captions ) {
	$cols = count( $captions ) >= 2 ? 2 : 1;
	$html = '<div class="photo-grid cols-' . $cols . '">';
	foreach ( $captions as $caption ) {
		$html .= '<div class="photo-ph"><svg viewBox="0 0 24 24" fill="currentColor"><rect x="2" y="7" width="16" height="12" rx="3"/><circle cx="10" cy="13" r="3.2" fill="var(--paper-2)"/><path d="M18 11l4-2.5v9L18 15Z"/></svg><span>Photo à ajouter — ' . esc_html( $caption ) . '</span></div>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
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
function antenniste91_showcase_block( $illustration_svg, $features ) {
	$check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
	$left  = array_slice( $features, 0, 2 );
	$right = array_slice( $features, 2, 2 );

	$col = function( $items, $class ) use ( $check ) {
		$out = '<div class="showcase-feat ' . $class . '">';
		foreach ( $items as $f ) {
			$out .= '<div class="item"><span class="ico">' . $check . '</span><span><h4>' . $f[0] . '</h4><p>' . $f[1] . '</p></span></div>';
		}
		$out .= '</div>';
		return $out;
	};

	$html = '<div class="showcase" id="inclus"><div class="showcase-grid">'
		. $col( $left, 'showcase-col-left' )
		. '<div class="showcase-illust"><svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">' . $illustration_svg . '</svg></div>'
		. $col( $right, 'showcase-col-right' )
		. '</div></div>';
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
			'icon' => '<rect x="3" y="5" width="18" height="12" rx="2"/><rect x="8" y="19" width="8" height="2" rx="1"/><rect x="6.3" y="1.8" width="2" height="4.4" rx="1" transform="rotate(-25 7.3 4)"/><rect x="15.7" y="1.8" width="2" height="4.4" rx="1" transform="rotate(25 16.7 4)"/>',
			'title' => "Antenne TV &amp; TNT",
			'text'  => "Chaînes qui sautent, image pixellisée : on identifie la cause — antenne, câble ou amplificateur — et on répare en une seule visite.",
			'url'   => home_url( '/services/antenne-tv/' ),
		),
		array(
			'icon' => '<path d="M3 16c0-7.2 5.8-13 13-13v3c-5.5 0-10 4.5-10 10H3Z"/><circle cx="19" cy="5" r="1.7"/><rect x="10.5" y="16" width="3" height="5" rx="1"/><rect x="8" y="20" width="8" height="2" rx="1"/>',
			'title' => "Parabole &amp; satellite",
			'text'  => "Parabole mal orientée, panne après une tempête ou envie de bouquets internationaux : installation et réglage précis.",
			'url'   => home_url( '/services/parabole-satellite/' ),
		),
		array(
			'icon' => '<rect x="5" y="2" width="14" height="9" rx="2" transform="rotate(-15 12 6.5)"/><rect x="11" y="12" width="2" height="7" rx="1"/><rect x="8" y="19" width="8" height="2" rx="1"/>',
			'title' => 'Internet Starlink',
			'text'  => "Pas de fibre chez vous ? Étude de dégagement du ciel, pose du kit et mise en réseau, pour un débit qui tient dans la durée.",
			'url'   => home_url( '/services/starlink/' ),
		),
		array(
			'icon' => '<rect x="2" y="7" width="14" height="10" rx="3"/><circle cx="9" cy="12" r="2.8" fill="var(--navy)"/><path d="M16 10.5 22 7v10l-6-3.5Z"/>',
			'title' => 'Vidéosurveillance',
			'text'  => "Caméras intérieures et extérieures consultables depuis votre téléphone, pour surveiller votre bien à distance.",
			'url'   => home_url( '/services/videosurveillance/' ),
		),
	);

	$html = '<div class="services-grid">';
	foreach ( $services as $s ) {
		$html .= '<div class="card">'
			. '<div class="icon-tile"><svg viewBox="0 0 24 24" fill="currentColor">' . $s['icon'] . '</svg></div>'
			. '<h3>' . $s['title'] . '</h3>'
			. '<p>' . $s['text'] . '</p>'
			. '<a class="go" href="' . esc_url( $s['url'] ) . '">Voir le détail du service →</a>'
			. '</div>';
	}
	$html .= '</div>';
	return antenniste91_html_block( $html );
}

function antenniste91_chunk_expertise() {
	$html = '<div class="expertise-list">'
		. '<div class="exp-item"><h4>Un vrai diagnostic, pas un devis à l\'aveugle</h4><p>La cause exacte identifiée avant toute proposition — vous savez toujours pourquoi vous payez.</p></div>'
		. '<div class="exp-item"><h4>Un prix qui ne bouge pas</h4><p>Le montant annoncé au téléphone est celui de la facture, sans surprise le jour de l\'intervention.</p></div>'
		. '<div class="exp-item"><h4>Un expert qui prend le temps d\'expliquer</h4><p>Le technicien montre ce qu\'il a fait et répond à toutes vos questions, en clair, sans jargon.</p></div>'
		. '<div class="exp-item"><h4>Toujours le même interlocuteur</h4><p>Une question après la pose ? Vous rappelez la même personne, qui connaît déjà votre installation.</p></div>'
		. '</div>';
	return antenniste91_html_block( $html );
}

function antenniste91_chunk_process() {
	$html = '<div class="band-navy section-pad"><div class="process">'
		. '<div class="step"><span class="step-num">01 · Appel</span><h4>Diagnostic par téléphone</h4><p>Vous décrivez la panne ou le projet, on cerne ce qui est probablement en cause — gratuitement.</p></div>'
		. '<div class="step"><span class="step-num">02 · Devis</span><h4>Prix ferme, sans surprise</h4><p>Un devis détaillé transmis avant intervention. Rien n\'est engagé tant qu\'il n\'est pas validé.</p></div>'
		. '<div class="step"><span class="step-num">03 · Pose</span><h4>Installation &amp; tests</h4><p>Chaînes, débit ou image caméra testés en direct avant que le technicien ne reparte.</p></div>'
		. '<div class="step"><span class="step-num">04 · Suivi</span><h4>Garantie &amp; SAV</h4><p>Un contact direct en cas de question après la pose — vous ne repartez pas de zéro.</p></div>'
		. '</div></div>';
	return antenniste91_html_block( $html );
}

function antenniste91_chunk_audience() {
	$icon_tv     = '<rect x="3" y="5" width="18" height="12" rx="2"/><rect x="8" y="19" width="8" height="2" rx="1"/><rect x="6.3" y="1.8" width="2" height="4.4" rx="1" transform="rotate(-25 7.3 4)"/><rect x="15.7" y="1.8" width="2" height="4.4" rx="1" transform="rotate(25 16.7 4)"/>';
	$icon_star   = '<rect x="5" y="2" width="14" height="9" rx="2" transform="rotate(-15 12 6.5)"/><rect x="11" y="12" width="2" height="7" rx="1"/><rect x="8" y="19" width="8" height="2" rx="1"/>';
	$icon_cam    = '<rect x="2" y="7" width="14" height="10" rx="3"/><circle cx="9" cy="12" r="2.8" fill="var(--navy-tint)"/><path d="M16 10.5 22 7v10l-6-3.5Z"/>';
	$icon_house  = '<path d="M12 3 3 10v11h6v-6h6v6h6V10Z"/>';
	$icon_person = '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7Z"/>';

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
			$out .= '<li><span class="ico"><svg viewBox="0 0 24 24" fill="currentColor">' . $item[0] . '</svg></span><span>' . $item[1] . '</span></li>';
		}
		$out .= '</ul>';
		return $out;
	};

	$html = '<div class="audience-split">'
		. '<div class="aud-card"><span class="tag">Maison individuelle</span><h3>Particuliers</h3>'
		. $render_list( $particulier_items )
		. '<br/><a class="btn btn-ghost" href="' . esc_url( home_url( '/contact/' ) ) . '">Décrire mon besoin</a></div>'
		. '<div class="aud-card pro"><span class="tag">Hôtels · Commerces · Collectivités</span><h3>Professionnels &amp; collectivités</h3>'
		. $render_list( $pro_items )
		. '<br/><a class="btn btn-outline" href="' . esc_url( home_url( '/contact/' ) ) . '">Demander un devis professionnel</a></div>'
		. '</div>';
	return antenniste91_html_block( $html );
}

function antenniste91_chunk_reviews_teaser() {
	$html = '<div class="rating-strip" style="margin-bottom:22px;"><strong>4,9/5</strong> — note Google <span class="ph">(nombre d\'avis à renseigner)</span></div>'
		. '<div class="reviews-grid">'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review feat"><p class="ph">« Exemple d\'avis mis en avant — à remplacer. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '</div>';
	return antenniste91_html_block( $html );
}

function antenniste91_chunk_zone_chips() {
	$towns = array( 'Évry-Courcouronnes', 'Corbeil-Essonnes', 'Massy', 'Palaiseau', 'Étampes', 'Savigny-sur-Orge', 'Sainte-Geneviève-des-Bois', 'Draveil', 'Brétigny-sur-Orge', 'Longjumeau', '+ tout le département (91)' );
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
	$c .= antenniste91_paragraph_block( "Installateur antenne TV, parabole satellite, Starlink et vidéosurveillance en Essonne (91) : de l'antenne râteau à la caméra connectée, chaque intervention est diagnostiquée avant devis, pour particuliers, professionnels et collectivités." );
	$c .= antenniste91_chunk_services_grid();

	$c .= antenniste91_html_block( '<span class="eyebrow">Pourquoi nous</span>' );
	$c .= antenniste91_heading_block( 'Ce qui change, concrètement.' );
	$c .= antenniste91_chunk_expertise();

	$c .= antenniste91_chunk_process();

	$c .= antenniste91_html_block( '<span class="eyebrow">Pour qui</span>' );
	$c .= antenniste91_heading_block( 'Particuliers &amp; professionnels.' );
	$c .= antenniste91_chunk_audience();

	$c .= antenniste91_html_block( '<span class="eyebrow">Zone d\'intervention</span>' );
	$c .= antenniste91_heading_block( "Une intervention en Essonne, pas un rendez-vous manqué." );
	$c .= antenniste91_paragraph_block( "Nous intervenons dans tout le département de l'Essonne (91) et les communes limitrophes." );
	$c .= antenniste91_button_block( "Voir la zone d'intervention →", home_url( '/zone-intervention/' ), 'outline-navy' );

	$c .= antenniste91_html_block( '<span class="eyebrow">Avis clients</span>' );
	$c .= antenniste91_heading_block( "Ce qu'en disent les clients." );
	$c .= antenniste91_chunk_reviews_teaser();
	$c .= antenniste91_button_block( 'Voir tous les avis →', home_url( '/avis-clients/' ), 'outline-navy' );

	$c .= antenniste91_html_block( '<span class="eyebrow">Questions fréquentes</span>' );
	$c .= antenniste91_heading_block( "Avant d'appeler." );
	$c .= antenniste91_details_block(
		"Combien coûte une installation d'antenne TV ou d'une parabole ?",
		"Le prix dépend de la configuration du toit, du câblage existant et du nombre de prises à raccorder. Un devis gratuit est établi après un diagnostic rapide, avant toute intervention."
	);
	$c .= antenniste91_details_block(
		"Quel est le délai d'intervention en Essonne ?",
		"Le délai dépend de votre secteur et de l'urgence de la demande. Appelez-nous, on vous donne un créneau réaliste au téléphone."
	);
	$c .= antenniste91_details_block(
		"Intervenez-vous pour des collectivités ou des marchés publics ?",
		"Oui. Contactez-nous pour évoquer vos besoins (devis-cadre, multi-sites, facturation administrative)."
	);
	$c .= antenniste91_button_block( 'Voir toutes les questions →', home_url( '/faq/' ), 'outline-navy' );

	$c .= antenniste91_html_block(
		'<div class="inner-cta" style="margin-top:0;"><span class="eyebrow inv">Prêt à commencer</span><h2>Un appel suffit pour lancer le diagnostic.</h2><p>Devis gratuit, sans engagement.</p><div class="hero-ctas" style="justify-content:center;"><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler maintenant</a></div></div>'
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

function antenniste91_service_illustration( $key ) {
	// Silhouettes littérales (antenne râteau, parabole vue de profil, panneau Starlink, caméra tourelle) —
	// pensées pour être reconnaissables au premier coup d'œil, pas juste décoratives.
	$illustrations = array(
		'antenne'  => '<path d="M12 50H88"/><path d="M20 30V70"/><path d="M30 36V64"/><path d="M40 40V60"/><path d="M50 43V57"/><path d="M60 45V55"/><path d="M70 47V53"/><path d="M50 50V86"/><path d="M40 86H60"/>',
		'parabole' => '<ellipse cx="42" cy="42" rx="30" ry="22" transform="rotate(-18 42 42)"/><path d="M42 42 76 24"/><circle cx="76" cy="24" r="4" fill="currentColor" stroke="none"/><path d="M42 64V88"/><path d="M28 88H56"/>',
		'starlink' => '<rect x="20" y="8" width="54" height="32" rx="5" transform="rotate(-14 47 24)"/><path d="M50 42V86"/><path d="M34 86H66"/>',
		'camera'   => '<rect x="12" y="32" width="50" height="32" rx="9"/><circle cx="37" cy="48" r="10"/><circle cx="37" cy="48" r="3.5" fill="currentColor" stroke="none"/><path d="M62 40 86 26v44l-24-14Z"/><path d="M22 32V22a6 6 0 0 1 6-6h8"/>',
	);
	return isset( $illustrations[ $key ] ) ? $illustrations[ $key ] : '';
}

function antenniste91_service_page( $args ) {
	$c  = antenniste91_callout_block( $args['hook'] );
	$c .= antenniste91_showcase_block( antenniste91_service_illustration( $args['illustration'] ), $args['included'] );
	$c .= antenniste91_heading_block( 'Les signes qui indiquent qu\'il est temps d\'appeler', 3, '', 'signes' );
	$c .= antenniste91_card_list_block( $args['signs'] );
	$c .= antenniste91_photo_placeholder_block( array( $args['photo1'] ) );
	$c .= antenniste91_heading_block( 'Notre méthode', 3, '', 'methode' );
	$c .= antenniste91_paragraph_block( $args['process'] );
	$c .= antenniste91_heading_block( 'Le prix', 3, '', 'prix' );
	$c .= antenniste91_price_box_block( $args['pricing'] );
	$c .= antenniste91_photo_placeholder_block( array( 'avant l\'intervention', 'après l\'intervention' ) );
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
			'hook'     => "Une chaîne qui saute, une image qui se pixellise, une neige persistante à l'écran : dans neuf cas sur dix, le problème vient de l'antenne, du câble ou de l'amplificateur — pas de la télévision. On identifie la cause exacte avant de réparer, pour ne pas payer deux fois.",
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
			'illustration' => 'antenne',
			'photo1'   => 'antenne en cours de réglage sur toiture',
			'faq'      => array(
				array(
					'q' => "Faut-il changer toute l'antenne ou peut-on la réparer ?",
					'a' => "La plupart des pannes viennent d'un câble, d'un connecteur ou d'un amplificateur défectueux — l'antenne elle-même est rarement à remplacer entièrement. Le diagnostic sur place permet de le savoir avant de proposer une solution.",
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
			'hook'     => "Parabole mal orientée après un coup de vent, envie de recevoir des bouquets internationaux, ou premier équipement : une parabole bien réglée se voit au millimètre — un mauvais alignement suffit à perdre le signal.",
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
			'process'  => "Après un premier échange pour cerner le ou les bouquets souhaités, l'installation est réalisée avec un instrument de mesure du signal — pas au jugé. Chaque chaîne est vérifiée avant la fin de la visite.",
			'pricing'  => "Le tarif dépend du support à poser, de la hauteur d'intervention et du nombre de récepteurs à raccorder. Il est annoncé avant toute pose.",
			'illustration' => 'parabole',
			'photo1'   => 'parabole fixée et alignée en façade',
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
			'illustration' => 'starlink',
			'photo1'   => 'kit Starlink fixé en toiture',
			'faq'      => array(
				array(
					'q' => "Fournissez-vous le kit Starlink ou seulement l'installation ?",
					'a' => "Précisez-nous votre situation au moment de la prise de contact — nous vous indiquerons la formule la plus adaptée.",
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
			'illustration' => 'camera',
			'photo1'   => 'caméra extérieure installée près d\'une entrée',
			'faq'      => array(
				array(
					'q' => "Puis-je consulter les images à distance depuis mon téléphone ?",
					'a' => "Oui, une application dédiée permet de consulter les caméras en direct et de recevoir des notifications en cas de détection de mouvement, selon le modèle choisi.",
				),
				array(
					'q' => "Intervenez-vous pour des bâtiments professionnels ou publics ?",
					'a' => "Oui — hôtels, commerces et collectivités font partie des configurations que nous équipons régulièrement.",
				),
			),
		)
	);
}

function antenniste91_content_zone() {
	$c  = antenniste91_paragraph_block( "Nous intervenons dans tout le département de l'Essonne (91) ainsi que dans les communes limitrophes. Le déplacement est inclus dans le devis pour les secteurs listés ci-dessous." );
	$c .= antenniste91_chunk_zone_chips();
	$c .= antenniste91_paragraph_block( '<span class="ph">Liste à ajuster selon votre rayon réel d\'intervention.</span>' );
	$c .= antenniste91_heading_block( "Un secteur qui n'est pas dans la liste ?", 3 );
	$c .= antenniste91_paragraph_block( "Appelez-nous : la plupart des demandes en dehors de cette liste restent réalisables, avec un délai ou un forfait de déplacement adapté à la distance." );
	$c .= antenniste91_heading_block( "Délai moyen d'intervention", 3 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Délai à préciser (ex. 24 à 48h selon les secteurs et l\'urgence).</span>' );
	return $c;
}

function antenniste91_content_avis() {
	$c  = antenniste91_html_block( antenniste91_chunk_reviews_teaser_raw() );
	$c .= antenniste91_heading_block( 'Vous êtes déjà client ?', 3 );
	$c .= antenniste91_paragraph_block( "Un avis met deux minutes à écrire et aide directement les prochains foyers ou établissements qui cherchent un installateur de confiance en Essonne. <span class=\"ph\">Lien vers votre fiche Google à ajouter.</span>" );
	return $c;
}

/** Same visual block as the homepage teaser, without duplicating the function name. */
function antenniste91_chunk_reviews_teaser_raw() {
	return '<div class="rating-strip" style="margin-bottom:22px;"><strong>4,9/5</strong> — note Google <span class="ph">(nombre d\'avis à renseigner)</span></div>'
		. '<div class="reviews-grid">'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review feat"><p class="ph">« Exemple d\'avis mis en avant — à remplacer. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '<div class="review"><p class="ph">« Exemple d\'avis — à remplacer par un vrai retour client. »</p><div class="who ph">Prénom, Ville</div></div>'
		. '</div>';
}

function antenniste91_content_faq() {
	$groups = array(
		'Général' => array(
			array( 'q' => "Dans quelles communes intervenez-vous ?", 'a' => "Toute l'Essonne (91) et les communes limitrophes. Voir le détail sur la page Zone d'intervention." ),
			array( 'q' => "Le devis est-il vraiment gratuit ?", 'a' => "Oui, systématiquement, et sans engagement de votre part." ),
			array( 'q' => "Quel est le délai d'intervention ?", 'a' => '<span class="ph">Délai à préciser (ex. 24 à 48h selon les secteurs et l\'urgence).</span>' ),
		),
		'Antenne &amp; parabole' => array(
			array( 'q' => "Combien coûte une réparation d'antenne ?", 'a' => "Cela dépend de la panne et de l'accès au toit. Le prix exact est annoncé après diagnostic, avant toute réparation." ),
			array( 'q' => "Faut-il une autorisation pour une parabole en copropriété ?", 'a' => "Selon la visibilité depuis la voie publique, une déclaration au syndic ou à la mairie peut être nécessaire. On vous guide selon votre cas." ),
		),
		'Starlink' => array(
			array( 'q' => "Fournissez-vous le kit Starlink ?", 'a' => '<span class="ph">Réponse à préciser : installation seule, ou installation + fourniture du kit.</span>' ),
			array( 'q' => "Le dégagement du ciel est-il vraiment important ?", 'a' => "Oui, c'est le facteur numéro un pour la stabilité de la connexion. C'est pour cela qu'une étude précède systématiquement la pose." ),
		),
		'Vidéosurveillance' => array(
			array( 'q' => "Puis-je voir les caméras depuis mon téléphone ?", 'a' => "Oui, via une application dédiée, avec notifications de mouvement selon le modèle choisi." ),
			array( 'q' => "L'enregistrement se fait où ?", 'a' => "Localement ou sur le cloud, selon vos préférences et votre budget — on en discute avant la pose." ),
		),
		'Professionnels &amp; collectivités' => array(
			array( 'q' => "Travaillez-vous avec des collectivités ?", 'a' => '<span class="ph">Réponse à préciser : références, devis-cadre, facturation administrative, etc.</span>' ),
			array( 'q' => "Pouvez-vous intervenir sur plusieurs sites pour un même client ?", 'a' => "Oui, avec un interlocuteur unique pour coordonner les interventions et les devis." ),
		),
	);

	$c = '';
	foreach ( $groups as $title => $items ) {
		$c .= antenniste91_heading_block( $title, 3 );
		foreach ( $items as $item ) {
			$c .= antenniste91_details_block( $item['q'], $item['a'] );
		}
	}
	return $c;
}

function antenniste91_content_contact() {
	$c  = antenniste91_paragraph_block( "La façon la plus rapide d'obtenir une réponse : appeler directement. Décrivez votre besoin, on vous donne un premier avis et, si nécessaire, un créneau d'intervention — gratuitement." );
	$c .= antenniste91_html_block(
		'<div class="audience-split"><div class="aud-card"><span class="tag">Par téléphone</span><h3>' . antenniste91_phone_display() . '</h3><p>Lun–Sam · 8h–19h</p><br/><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler maintenant</a></div>'
		. '<div class="aud-card"><span class="tag">Par email</span><h3 class="ph">email à renseigner</h3><p>Réponse sous 24 à 48h ouvrées.</p></div></div>'
	);
	$c .= antenniste91_heading_block( 'Ou décrivez votre besoin par écrit', 3 );
	$c .= antenniste91_paragraph_block( "Le formulaire ci-dessous envoie directement un email — sans passer par un plugin tiers." );
	return $c;
}

function antenniste91_blog_posts() {
	$posts = array();

	$posts[] = array(
		'slug'    => 'antenne-tv-perd-signal-hiver',
		'title'   => "Pourquoi mon antenne TV perd-elle le signal en hiver ?",
		'excerpt' => "Vent, gel, givre : les chutes de signal TV sont plus fréquentes en hiver. Voici les causes les plus courantes et comment les repérer avant d'appeler.",
		'content' =>
			antenniste91_paragraph_block( "Chaque hiver, le même constat revient chez de nombreux foyers d'Essonne : la télévision « saute » davantage, certaines chaînes disparaissent, l'image se pixellise. Ce n'est pas une coïncidence — le froid et le vent fragilisent des installations qui tenaient très bien le reste de l'année." )
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
			. antenniste91_paragraph_block( "Dans le doute, un diagnostic reste la façon la plus rapide de trancher — c'est justement ce qu'on propose avant toute réparation." )
			. antenniste91_button_block( "Voir la page Antenne TV & TNT →", home_url( '/services/antenne-tv/' ), 'outline-navy' ),
	);

	$posts[] = array(
		'slug'    => 'autorisation-parabole-satellite',
		'title'   => "Faut-il une autorisation pour installer une parabole satellite ?",
		'excerpt' => "En maison individuelle ou en copropriété, les règles ne sont pas les mêmes. Voici ce qu'il faut vérifier avant de poser une parabole.",
		'content' =>
			antenniste91_paragraph_block( "La question revient souvent, en particulier en copropriété ou en secteur protégé : peut-on installer une parabole librement, ou faut-il une autorisation au préalable ?" )
			. antenniste91_heading_block( "En maison individuelle", 2 )
			. antenniste91_paragraph_block( "En règle générale, aucune autorisation n'est nécessaire tant que l'installation reste sur votre propriété et respecte les règles locales d'urbanisme. Dans une zone classée ou protégée, la mairie peut cependant imposer des restrictions — un point à vérifier avant la pose." )
			. antenniste91_heading_block( "En copropriété", 2 )
			. antenniste91_paragraph_block( "La règle de base : chaque copropriétaire a le droit de recevoir la télévision par satellite, mais l'installation doit respecter le règlement de copropriété et, si elle est visible depuis la voie publique, peut nécessiter une déclaration au syndic." )
			. antenniste91_callout_block( "En cas de doute, on vous indique la marche à suivre selon votre configuration avant toute intervention — mieux vaut vérifier que démonter une parabole mal placée." )
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
			. antenniste91_paragraph_block( "Contrairement à une idée reçue, ce n'est pas la localisation géographique qui pose problème en Essonne, mais le dégagement du ciel autour du bâtiment — arbres, toitures voisines, reliefs proches. C'est pour cette raison qu'une étude précède toujours la pose." )
			. antenniste91_button_block( "Voir la page Internet Starlink →", home_url( '/services/starlink/' ), 'outline-navy' ),
	);

	$posts[] = array(
		'slug'    => 'videosurveillance-domicile-ce-quil-faut-savoir',
		'title'   => "Vidéosurveillance à domicile : ce qu'il faut savoir avant d'installer",
		'excerpt' => "Nombre de caméras, enregistrement, vie privée du voisinage : les questions à se poser avant d'équiper sa maison.",
		'content' =>
			antenniste91_paragraph_block( "Installer des caméras chez soi semble simple sur le papier — en pratique, quelques choix mal anticipés peuvent rendre l'installation inutile ou, pire, problématique sur le plan légal." )
			. antenniste91_heading_block( "Ne filmez pas la voie publique ni le jardin du voisin", 2 )
			. antenniste91_paragraph_block( "Une caméra orientée sur l'espace privé d'un tiers ou sur la rue peut poser un problème légal, même installée de bonne foi. Le positionnement se réfléchit avant la pose, pas après une plainte." )
			. antenniste91_heading_block( "Combien de caméras sont vraiment nécessaires ?", 2 )
			. antenniste91_paragraph_block( "Plus n'est pas toujours mieux. Une entrée principale et un accès secondaire bien couverts valent souvent mieux que cinq caméras mal positionnées. C'est l'objet du diagnostic initial : couvrir l'essentiel sans multiplier le matériel — et le coût — sans raison." )
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
			. antenniste91_paragraph_block( "Pas nécessairement. Une panne totale de réception peut relever de l'urgence, permettant au syndic de faire intervenir un professionnel sans attendre le prochain vote — à condition de pouvoir justifier la nécessité de l'intervention." )
			. antenniste91_callout_block( "On peut établir un diagnostic et un devis rapidement, pour donner au syndic les éléments nécessaires à sa décision." )
			. antenniste91_button_block( "Demander un diagnostic →", home_url( '/contact/' ), 'outline-navy' ),
	);

	return $posts;
}

function antenniste91_content_confidentialite() {
	$c  = antenniste91_paragraph_block( "Cette page explique quelles données sont collectées sur ce site, pourquoi, et comment les faire modifier ou supprimer." );
	$c .= antenniste91_heading_block( 'Responsable du traitement', 2 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Raison sociale à compléter</span> — contact : ' . antenniste91_phone_display() . ', <span class="ph">email à compléter</span>.' );
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
	$c .= antenniste91_paragraph_block( 'Les données transitant par ce site sont hébergées par <span class="ph">Ionos — à confirmer</span>.' );
	$c .= antenniste91_paragraph_block( '<span class="ph">Cette page doit être relue et validée avec un professionnel du droit avant mise en ligne.</span>' );
	return $c;
}

function antenniste91_content_cgu() {
	$c  = antenniste91_paragraph_block( "Les présentes conditions générales d'utilisation régissent l'accès et l'usage de ce site." );
	$c .= antenniste91_heading_block( "Objet du site", 2 );
	$c .= antenniste91_paragraph_block( "Ce site présente les services de " . esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ) . " (antenne TV, parabole, Starlink, vidéosurveillance) et permet de demander un devis ou de prendre contact. Les informations qu'il contient (tarifs indicatifs, délais) sont données à titre général et confirmées au cas par cas lors du diagnostic." );
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
	$c .= antenniste91_paragraph_block( 'Le site est hébergé par : <span class="ph">Ionos — coordonnées complètes à ajouter</span>' );
	$c .= antenniste91_heading_block( 'Directeur de la publication', 3 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Nom à compléter</span>' );
	$c .= antenniste91_heading_block( 'Données personnelles', 3 );
	$c .= antenniste91_paragraph_block( "Les informations transmises via ce site (téléphone, email) ne sont utilisées que pour répondre à votre demande. <span class=\"ph\">Cette section doit être complétée ou validée avec un professionnel du droit avant mise en ligne.</span>" );
	return $c;
}
