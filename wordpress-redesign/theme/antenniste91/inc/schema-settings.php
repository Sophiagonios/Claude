<?php
/**
 * Petite page de réglages (Réglages → Schéma antenne) pour les 6 légendes
 * (titre + description) du schéma technique utilisé sur la page
 * "Installation d'antenne TV" et la page géolocalisée — contenu partagé,
 * géré à un seul endroit. Le dessin (le SVG) lui-même reste fixe : c'est
 * un schéma technique, pas une photo, il continue de demander une
 * intervention en code pour être redessiné.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Les 6 légendes d'origine. */
function antenniste91_schema_legend_defaults() {
	return array(
		array( 'title' => 'Antenne', 'text' => "Capte le signal hertzien, orientée vers l'émetteur le plus proche." ),
		array( 'title' => 'Mât et fixations', 'text' => 'Fixation solide en toiture, dimensionnée pour résister au vent.' ),
		array( 'title' => 'Câble de descente', 'text' => "Relie l'antenne à l'amplificateur, protégé des intempéries." ),
		array( 'title' => 'Amplificateur', 'text' => 'Renforce le signal avant sa répartition dans le logement.' ),
		array( 'title' => 'Répartiteur', 'text' => 'Distribue le signal vers chaque prise TV du logement.' ),
		array( 'title' => 'Prise TV', 'text' => 'Un signal stable, prêt à brancher.' ),
	);
}

/** Les légendes actuelles : ce que la propriétaire a enregistré, sinon les valeurs d'origine. */
function antenniste91_get_schema_legend() {
	$saved = get_option( 'antenniste91_schema_legend' );
	if ( ! is_array( $saved ) || empty( $saved ) ) {
		return antenniste91_schema_legend_defaults();
	}

	$defaults = antenniste91_schema_legend_defaults();
	$items    = array();
	foreach ( $defaults as $i => $fallback ) {
		$items[] = array(
			'title' => ! empty( $saved[ $i ]['title'] ) ? $saved[ $i ]['title'] : $fallback['title'],
			'text'  => ! empty( $saved[ $i ]['text'] ) ? $saved[ $i ]['text'] : $fallback['text'],
		);
	}
	return $items;
}

function antenniste91_schema_settings_menu() {
	add_options_page(
		'Schéma antenne',
		'Schéma antenne',
		'manage_options',
		'antenniste91-schema',
		'antenniste91_schema_settings_page'
	);
}
add_action( 'admin_menu', 'antenniste91_schema_settings_menu' );

function antenniste91_schema_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['antenniste91_schema_nonce'] ) && wp_verify_nonce( $_POST['antenniste91_schema_nonce'], 'antenniste91_schema_save' ) ) {
		$posted = isset( $_POST['legend'] ) ? wp_unslash( $_POST['legend'] ) : array();
		$clean  = array();
		foreach ( $posted as $item ) {
			$clean[] = array(
				'title' => sanitize_text_field( $item['title'] ?? '' ),
				'text'  => sanitize_text_field( $item['text'] ?? '' ),
			);
		}
		update_option( 'antenniste91_schema_legend', $clean );
		echo '<div class="notice notice-success"><p>Enregistré.</p></div>';
	}

	$items = antenniste91_get_schema_legend();
	?>
	<div class="wrap">
		<h1>Légendes du schéma antenne</h1>
		<p>Ce schéma et ses 6 légendes apparaissent sur la page « Installation d'antenne TV » et sur la page géolocalisée. Le dessin lui-même reste à me demander pour le modifier ; les titres et les textes se changent ici.</p>
		<form method="post">
			<?php wp_nonce_field( 'antenniste91_schema_save', 'antenniste91_schema_nonce' ); ?>
			<table class="form-table">
				<?php foreach ( $items as $i => $item ) : ?>
					<tr>
						<th><label>Étape <?php echo (int) $i + 1; ?></label></th>
						<td>
							<input type="text" class="regular-text" placeholder="Titre" name="legend[<?php echo (int) $i; ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>" style="margin-bottom:6px; display:block;">
							<input type="text" class="large-text" placeholder="Description" name="legend[<?php echo (int) $i; ?>][text]" value="<?php echo esc_attr( $item['text'] ); ?>">
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<?php
}
