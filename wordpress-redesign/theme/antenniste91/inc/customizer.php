<?php
/**
 * Customizer: everything the owner needs to edit without touching code
 * (Apparence > Personnaliser > Coordonnées & réglages).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function antenniste91_customize_register( $wp_customize ) {

	$wp_customize->add_section(
		'antenniste91_contact',
		array(
			'title'    => __( 'Coordonnées & réglages', 'antenniste91' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'antenniste91_company_name' => array(
			'label'   => __( 'Nom commercial / raison sociale', 'antenniste91' ),
			'default' => 'France Technique Antenne',
			'type'    => 'text',
		),
		'antenniste91_phone' => array(
			'label'   => __( 'Numéro de téléphone (format tel: — ex. 0663806197)', 'antenniste91' ),
			'default' => '0663806197',
			'type'    => 'text',
		),
		'antenniste91_phone_display' => array(
			'label'   => __( 'Numéro affiché (ex. 06 63 80 61 97)', 'antenniste91' ),
			'default' => '06 63 80 61 97',
			'type'    => 'text',
		),
		'antenniste91_google_rating' => array(
			'label'   => __( 'Note Google (ex. 4.9)', 'antenniste91' ),
			'default' => '4.9',
			'type'    => 'text',
		),
		'antenniste91_google_review_count' => array(
			'label'   => __( 'Nombre d\'avis Google (laisser vide si inconnu)', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_stat_installs' => array(
			'label'   => __( 'Nombre d\'installations réalisées (laisser vide pour masquer)', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_stat_years' => array(
			'label'   => __( 'Années d\'expérience (laisser vide pour masquer)', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_stat_delay' => array(
			'label'   => __( 'Délai moyen d\'intervention (ex. 24h — laisser vide pour masquer)', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_email' => array(
			'label'   => __( 'Email de contact', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_address' => array(
			'label'   => __( 'Adresse postale', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_siret' => array(
			'label'   => __( 'SIRET', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_hours' => array(
			'label'   => __( 'Horaires (bandeau du haut)', 'antenniste91' ),
			'default' => __( 'Lun–Sam · 8h–19h', 'antenniste91' ),
			'type'    => 'text',
		),
		'antenniste91_zone_label' => array(
			'label'   => __( 'Zone d\'intervention (bandeau du haut)', 'antenniste91' ),
			'default' => __( 'Essonne (91) & alentours', 'antenniste91' ),
			'type'    => 'text',
		),
		'antenniste91_hero_title_part' => array(
			'label'   => __( 'Héro — titre (particuliers)', 'antenniste91' ),
			'default' => __( 'Votre expert antenne, Starlink et vidéosurveillance en Essonne.', 'antenniste91' ),
			'type'    => 'textarea',
		),
		'antenniste91_hero_lead_part' => array(
			'label'   => __( 'Héro — texte (particuliers)', 'antenniste91' ),
			'default' => __( 'Un seul technicien, qualifié sur les quatre métiers, pour répondre à toutes vos questions : diagnostic clair, solution expliquée en français, et un contact direct si besoin après la pose. Vous n\'êtes jamais seul face au problème.', 'antenniste91' ),
			'type'    => 'textarea',
		),
		'antenniste91_hero_title_pro' => array(
			'label'   => __( 'Héro — titre (professionnels)', 'antenniste91' ),
			'default' => __( 'Installateur antenne, Starlink et vidéosurveillance pour vos établissements en Essonne.', 'antenniste91' ),
			'type'    => 'textarea',
		),
		'antenniste91_hero_lead_pro' => array(
			'label'   => __( 'Héro — texte (professionnels)', 'antenniste91' ),
			'default' => __( 'Hôtels, commerces, collectivités : un seul expert pour cadrer le besoin, chiffrer et coordonner l\'installation sur un ou plusieurs sites — avec la réactivité et le sérieux qu\'exige un cahier des charges professionnel.', 'antenniste91' ),
			'type'    => 'textarea',
		),
	);

	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $field['default'],
				'sanitize_callback' => 'textarea' === $field['type'] ? 'sanitize_textarea_field' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field['label'],
				'section' => 'antenniste91_contact',
				'type'    => $field['type'],
			)
		);
	}
}
add_action( 'customize_register', 'antenniste91_customize_register' );

/**
 * Small helpers used in the templates.
 */
function antenniste91_phone_href() {
	$phone = get_theme_mod( 'antenniste91_phone', '' );
	if ( ! $phone ) {
		return 'tel:0000000000';
	}
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

function antenniste91_phone_display() {
	return esc_html( get_theme_mod( 'antenniste91_phone_display', '06 63 80 61 97' ) );
}

function antenniste91_google_rating() {
	return esc_html( get_theme_mod( 'antenniste91_google_rating', '4.9' ) );
}

/** Returns "128 avis" if a count is set, otherwise "avis vérifiés" — never a fabricated number. */
function antenniste91_review_count_label() {
	$count = get_theme_mod( 'antenniste91_google_review_count', '' );
	if ( $count ) {
		/* translators: %s: number of reviews */
		return sprintf( __( 'sur %s avis', 'antenniste91' ), esc_html( $count ) );
	}
	return __( 'avis clients vérifiés', 'antenniste91' );
}

function antenniste91_stat( $key ) {
	return esc_html( get_theme_mod( 'antenniste91_stat_' . $key, '' ) );
}
