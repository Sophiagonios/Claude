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
			'default' => 'Antenniste 91',
			'type'    => 'text',
		),
		'antenniste91_phone' => array(
			'label'   => __( 'Numéro de téléphone (format tel: — ex. 0123456789)', 'antenniste91' ),
			'default' => '',
			'type'    => 'text',
		),
		'antenniste91_phone_display' => array(
			'label'   => __( 'Numéro affiché (ex. 01 23 45 67 89)', 'antenniste91' ),
			'default' => __( '01 XX XX XX XX', 'antenniste91' ),
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
			'default' => __( 'Un signal qui tient, sans les allers-retours.', 'antenniste91' ),
			'type'    => 'textarea',
		),
		'antenniste91_hero_lead_part' => array(
			'label'   => __( 'Héro — texte (particuliers)', 'antenniste91' ),
			'default' => __( 'Antenne TV, parabole, Starlink ou caméra de surveillance : un technicien intervient chez vous en Essonne, diagnostique le problème et vous explique ce qu\'il a fait.', 'antenniste91' ),
			'type'    => 'textarea',
		),
		'antenniste91_hero_title_pro' => array(
			'label'   => __( 'Héro — titre (professionnels)', 'antenniste91' ),
			'default' => __( 'Vos sites équipés et suivis par un seul interlocuteur.', 'antenniste91' ),
			'type'    => 'textarea',
		),
		'antenniste91_hero_lead_pro' => array(
			'label'   => __( 'Héro — texte (professionnels)', 'antenniste91' ),
			'default' => __( 'Hôtels, commerces, collectivités : diagnostic, devis et installation coordonnés sur un ou plusieurs établissements en Essonne.', 'antenniste91' ),
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
	return esc_html( get_theme_mod( 'antenniste91_phone_display', __( '01 XX XX XX XX', 'antenniste91' ) ) );
}
