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

function antenniste91_heading_block( $text, $level = 2, $class = '' ) {
	$class_attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';
	$json       = $class ? ',"className":"' . esc_attr( $class ) . '"' : '';
	return "<!-- wp:heading {\"level\":$level$json} -->\n<h$level$class_attr>$text</h$level>\n<!-- /wp:heading -->\n\n";
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
	$html = '<div class="audience-split">'
		. '<div class="aud-card"><span class="tag">Maison individuelle</span><h3>Particuliers</h3><ul class="check-list">'
		. '<li>Chaînes qui sautent, image pixellisée ou neige à l\'écran</li>'
		. '<li>Zone mal desservie par la fibre : installation Starlink</li>'
		. '<li>Caméra pour surveiller l\'entrée ou le jardin à distance</li>'
		. '<li>Remise en service après tempête ou déménagement</li>'
		. '</ul><br/><a class="btn btn-ghost" href="' . esc_url( home_url( '/contact/' ) ) . '">Décrire mon besoin</a></div>'
		. '<div class="aud-card pro"><span class="tag">Hôtels · Commerces · Collectivités</span><h3>Professionnels &amp; collectivités</h3><ul class="check-list">'
		. '<li>Antenne collective ou multiplex TV pour chambres et salles communes</li>'
		. '<li>Starlink principal ou de secours sur un ou plusieurs sites</li>'
		. '<li>Vidéosurveillance des accès, parkings et zones sensibles</li>'
		. '<li>Un interlocuteur unique pour vos devis-cadres et marchés</li>'
		. '</ul><br/><a class="btn btn-call" href="' . esc_url( home_url( '/contact/' ) ) . '">Demander un devis professionnel</a></div>'
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
	$c .= antenniste91_paragraph_block( "De l'antenne râteau à la vidéosurveillance connectée, chaque intervention est diagnostiquée avant devis." );
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
		'<div class="inner-cta" style="margin-top:0;"><span class="eyebrow inv">Prêt à commencer</span><h2>Un appel suffit pour lancer le diagnostic.</h2><p>Décrivez votre besoin, obtenez un créneau et un devis gratuit — sans engagement.</p><div class="hero-ctas" style="justify-content:center;"><a class="btn btn-call" href="' . esc_url( antenniste91_phone_href() ) . '">Appeler — ' . antenniste91_phone_display() . '</a><a class="btn btn-outline" href="' . esc_url( home_url( '/contact/' ) ) . '">Devis gratuit</a></div></div>'
	);

	return $c;
}

function antenniste91_service_page( $args ) {
	$c  = antenniste91_paragraph_block( $args['hook'] );
	$c .= antenniste91_heading_block( 'Les signes qui indiquent qu\'il est temps d\'appeler', 3 );
	$c .= antenniste91_list_block( $args['signs'] );
	$c .= antenniste91_heading_block( 'Ce qui est compris dans l\'intervention', 3 );
	$c .= antenniste91_list_block( $args['included'] );
	$c .= antenniste91_heading_block( 'Notre façon de faire', 3 );
	$c .= antenniste91_paragraph_block( $args['process'] );
	$c .= antenniste91_heading_block( 'Le prix', 3 );
	$c .= antenniste91_paragraph_block( $args['pricing'] );
	if ( ! empty( $args['faq'] ) ) {
		$c .= antenniste91_heading_block( 'Questions fréquentes', 3 );
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
				'Diagnostic complet de la réception (antenne, câblage, amplificateur, répartiteur)',
				"Réparation, réorientation ou remplacement de l'antenne si nécessaire",
				'Vérification de toutes les prises TV concernées',
				'Test des chaînes en direct avant la fin de la visite',
			),
			'process'  => "Le technicien commence par un diagnostic sur place pour localiser la cause réelle de la panne. Le devis est annoncé avant toute réparation ; l'intervention se termine par un test des chaînes, prise par prise, en votre présence.",
			'pricing'  => "Le tarif dépend de l'accès au toit, du câblage existant et du nombre de prises à vérifier. Il vous est communiqué avant l'intervention, jamais après.",
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
				"Choix et fixation du support adapté à la façade ou au toit",
				"Réglage précis de l'orientation (azimut, élévation, polarisation)",
				"Raccordement et test des bouquets reçus",
				'Passage des câbles propre et protégé des intempéries',
			),
			'process'  => "Après un premier échange pour cerner le ou les bouquets souhaités, l'installation est réalisée avec un instrument de mesure du signal — pas au jugé. Chaque chaîne est vérifiée avant la fin de la visite.",
			'pricing'  => "Le tarif dépend du support à poser, de la hauteur d'intervention et du nombre de récepteurs à raccorder. Il est annoncé avant toute pose.",
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
				"Étude du dégagement du ciel et repérage du meilleur emplacement",
				"Fixation durable du kit (mât, façade ou toiture)",
				"Passage de câble propre jusqu'au routeur",
				'Mise en réseau du foyer ou de l\'établissement (Wi-Fi, câblage existant)',
			),
			'process'  => "Une étude de dégagement du ciel précède toujours la pose : c'est elle qui détermine si l'installation tiendra dans le temps. Le kit est ensuite fixé durablement, câblé proprement, et le réseau testé en votre présence.",
			'pricing'  => "L'installation est facturée indépendamment du kit Starlink. Un devis est transmis après l'étude de dégagement, avant toute pose.",
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
				"Étude des zones à couvrir et choix du nombre de caméras adapté",
				'Pose de caméras intérieures et/ou extérieures',
				'Configuration de la consultation à distance sur smartphone',
				"Réglage de l'enregistrement (local ou cloud, selon votre choix)",
			),
			'process'  => "Après une visite ou un échange pour définir les zones sensibles, les caméras sont positionnées pour couvrir l'essentiel sans multiplier le matériel. L'application de consultation à distance est configurée et testée avec vous avant la fin de l'intervention.",
			'pricing'  => "Le tarif dépend du nombre de caméras, de la complexité du câblage et du mode d'enregistrement choisi. Il est annoncé avant la pose, jamais après.",
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
	$c .= antenniste91_heading_block( 'Formulaire de contact', 3 );
	$c .= antenniste91_paragraph_block( '<span class="ph">Ce site n\'a pas encore de formulaire actif — il faut brancher un plugin de formulaire (ex. Contact Form 7 ou WPForms) pour en avoir un qui envoie réellement les messages. En attendant, le téléphone et l\'email ci-dessus restent les moyens de contact fiables.</span>' );
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
