<?php
/**
 * Petite page de réglages (Réglages → Avant / Après) pour les deux paires
 * de photos avant/après utilisées à la fois sur la page d'accueil et sur
 * la page "Installation d'antenne TV" — contenu partagé entre les deux,
 * donc géré à un seul endroit plutôt que dans chaque page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Valeurs d'origine, utilisées tant que la propriétaire n'a rien changé. */
function antenniste91_avant_apres_defaults() {
	return array(
		array(
			'title'        => "Remplacement d'une antenne de toit",
			'caption'      => 'Remplacement par une antenne plus compacte, en toiture.',
			'before_url'   => get_template_directory_uri() . '/assets/photos/antenne-avant.jpg',
			'before_alt'   => 'Grande antenne râteau multi-éléments avant remplacement',
			'after_url'    => get_template_directory_uri() . '/assets/photos/antenne-apres.jpg',
			'after_alt'    => 'Petite antenne compacte installée en remplacement',
		),
		array(
			'title'        => "Réglage d'une antenne de toit",
			'caption'      => "Réorientation d'une antenne existante pour retrouver un signal stable.",
			'before_url'   => get_template_directory_uri() . '/assets/photos/antenne-reglage-avant.jpg',
			'before_alt'   => 'Antenne de toit avant réglage',
			'after_url'    => get_template_directory_uri() . '/assets/photos/antenne-reglage-apres.jpg',
			'after_alt'    => 'Antenne de toit réorientée après réglage',
		),
	);
}

/** Les paires actuelles : ce que la propriétaire a enregistré, sinon les valeurs d'origine. */
function antenniste91_get_avant_apres_items() {
	$saved = get_option( 'antenniste91_avant_apres' );
	if ( ! is_array( $saved ) || empty( $saved ) ) {
		return antenniste91_avant_apres_defaults();
	}

	$defaults = antenniste91_avant_apres_defaults();
	$items    = array();
	foreach ( $saved as $i => $pair ) {
		$fallback = isset( $defaults[ $i ] ) ? $defaults[ $i ] : $defaults[0];
		$items[]  = array(
			'title'      => ! empty( $pair['title'] ) ? $pair['title'] : $fallback['title'],
			'caption'    => ! empty( $pair['caption'] ) ? $pair['caption'] : $fallback['caption'],
			'before_url' => ! empty( $pair['before_url'] ) ? $pair['before_url'] : $fallback['before_url'],
			'before_alt' => ! empty( $pair['before_alt'] ) ? $pair['before_alt'] : $fallback['before_alt'],
			'after_url'  => ! empty( $pair['after_url'] ) ? $pair['after_url'] : $fallback['after_url'],
			'after_alt'  => ! empty( $pair['after_alt'] ) ? $pair['after_alt'] : $fallback['after_alt'],
		);
	}
	return $items;
}

function antenniste91_avant_apres_menu() {
	add_options_page(
		'Avant / Après',
		'Avant / Après',
		'manage_options',
		'antenniste91-avant-apres',
		'antenniste91_avant_apres_page'
	);
}
add_action( 'admin_menu', 'antenniste91_avant_apres_menu' );

function antenniste91_avant_apres_enqueue( $hook ) {
	if ( 'settings_page_antenniste91-avant-apres' !== $hook ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'antenniste91_avant_apres_enqueue' );

function antenniste91_avant_apres_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['antenniste91_avant_apres_nonce'] ) && wp_verify_nonce( $_POST['antenniste91_avant_apres_nonce'], 'antenniste91_avant_apres_save' ) ) {
		$posted = isset( $_POST['pairs'] ) ? wp_unslash( $_POST['pairs'] ) : array();
		$clean  = array();
		foreach ( $posted as $pair ) {
			$clean[] = array(
				'title'      => sanitize_text_field( $pair['title'] ?? '' ),
				'caption'    => sanitize_text_field( $pair['caption'] ?? '' ),
				'before_url' => esc_url_raw( $pair['before_url'] ?? '' ),
				'before_alt' => sanitize_text_field( $pair['before_alt'] ?? '' ),
				'after_url'  => esc_url_raw( $pair['after_url'] ?? '' ),
				'after_alt'  => sanitize_text_field( $pair['after_alt'] ?? '' ),
			);
		}
		update_option( 'antenniste91_avant_apres', $clean );
		echo '<div class="notice notice-success"><p>Enregistré.</p></div>';
	}

	$items = antenniste91_get_avant_apres_items();
	?>
	<div class="wrap">
		<h1>Photos avant / après</h1>
		<p>Ces deux paires de photos apparaissent sur la page d'accueil et sur la page « Installation d'antenne TV ». Les changer ici les met à jour aux deux endroits.</p>
		<form method="post">
			<?php wp_nonce_field( 'antenniste91_avant_apres_save', 'antenniste91_avant_apres_nonce' ); ?>
			<?php foreach ( $items as $i => $item ) : ?>
				<h2>Paire <?php echo (int) $i + 1; ?></h2>
				<table class="form-table">
					<tr>
						<th><label>Titre</label></th>
						<td><input type="text" class="regular-text" name="pairs[<?php echo (int) $i; ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>"></td>
					</tr>
					<tr>
						<th><label>Légende</label></th>
						<td><input type="text" class="regular-text" name="pairs[<?php echo (int) $i; ?>][caption]" value="<?php echo esc_attr( $item['caption'] ); ?>"></td>
					</tr>
					<tr>
						<th><label>Photo « avant »</label></th>
						<td>
							<img src="<?php echo esc_url( $item['before_url'] ); ?>" style="max-width:160px; height:auto; display:block; margin-bottom:8px; border-radius:8px;">
							<input type="hidden" class="antenniste91-aa-url" name="pairs[<?php echo (int) $i; ?>][before_url]" value="<?php echo esc_attr( $item['before_url'] ); ?>">
							<input type="text" class="regular-text antenniste91-aa-alt" placeholder="Texte alternatif" name="pairs[<?php echo (int) $i; ?>][before_alt]" value="<?php echo esc_attr( $item['before_alt'] ); ?>" style="display:block; margin-top:6px;">
							<button type="button" class="button antenniste91-aa-pick">Choisir une photo</button>
						</td>
					</tr>
					<tr>
						<th><label>Photo « après »</label></th>
						<td>
							<img src="<?php echo esc_url( $item['after_url'] ); ?>" style="max-width:160px; height:auto; display:block; margin-bottom:8px; border-radius:8px;">
							<input type="hidden" class="antenniste91-aa-url" name="pairs[<?php echo (int) $i; ?>][after_url]" value="<?php echo esc_attr( $item['after_url'] ); ?>">
							<input type="text" class="regular-text antenniste91-aa-alt" placeholder="Texte alternatif" name="pairs[<?php echo (int) $i; ?>][after_alt]" value="<?php echo esc_attr( $item['after_alt'] ); ?>" style="display:block; margin-top:6px;">
							<button type="button" class="button antenniste91-aa-pick">Choisir une photo</button>
						</td>
					</tr>
				</table>
			<?php endforeach; ?>
			<?php submit_button( 'Enregistrer' ); ?>
		</form>
	</div>
	<script>
	(function($){
		$('.antenniste91-aa-pick').on('click', function(e){
			e.preventDefault();
			var $btn = $(this);
			var $cell = $btn.closest('td');
			var frame = wp.media({ title: 'Choisir une photo', multiple: false });
			frame.on('select', function(){
				var attachment = frame.state().get('selection').first().toJSON();
				$cell.find('img').attr('src', attachment.url);
				$cell.find('.antenniste91-aa-url').val( attachment.url );
				if ( ! $cell.find('.antenniste91-aa-alt').val() && attachment.alt ) {
					$cell.find('.antenniste91-aa-alt').val( attachment.alt );
				}
			});
			frame.open();
		});
	})(jQuery);
	</script>
	<?php
}
