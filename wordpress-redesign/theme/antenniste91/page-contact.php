<?php
/**
 * WordPress automatically picks this template for the page whose slug is
 * "contact" (template hierarchy: page-{slug}.php). The form needs a fresh
 * nonce on every request, so it's rendered here rather than stored in the
 * page's own block content.
 */

get_header();

$status = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : '';

while ( have_posts() ) :
	the_post();
	?>
	<div class="page-banner">
		<div class="wrap">
			<p class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a> / <?php the_title(); ?></p>
			<span class="eyebrow"><?php bloginfo( 'name' ); ?></span>
			<h1><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="wrap section-pad">
		<div class="entry-content is-wide">
			<?php the_content(); ?>

			<?php if ( 'sent' === $status ) : ?>
				<div class="callout" style="border-left-color:var(--success);">
					<p><strong>Message envoyé.</strong> Nous vous répondons au plus vite — ou appelez directement si c'est urgent : <?php echo antenniste91_phone_display(); ?>.</p>
				</div>
			<?php elseif ( 'invalid' === $status ) : ?>
				<div class="callout" style="border-left-color:#B3261E;">
					<p><strong>Merci de vérifier votre message :</strong> le nom, un moyen de vous recontacter (téléphone ou email) et un message sont nécessaires.</p>
				</div>
			<?php elseif ( 'error' === $status ) : ?>
				<div class="callout" style="border-left-color:#B3261E;">
					<p><strong>L'envoi a échoué.</strong> Merci d'appeler directement en attendant : <?php echo antenniste91_phone_display(); ?>.</p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
				<input type="hidden" name="action" value="antenniste91_contact">
				<?php wp_nonce_field( 'antenniste91_contact', 'antenniste91_contact_nonce' ); ?>

				<div style="position:absolute; left:-9999px; width:1px; height:1px; overflow:hidden;" aria-hidden="true">
					<label for="antenniste91_website">Laissez ce champ vide</label>
					<input type="text" id="antenniste91_website" name="antenniste91_website" tabindex="-1" autocomplete="off">
				</div>

				<div style="display:flex; flex-direction:column; gap:16px; max-width:520px;">
					<label>Nom
						<input type="text" name="antenniste91_name" required minlength="2" autocomplete="name"
							style="width:100%; margin-top:6px; padding:12px 14px; border:1px solid var(--line); border-radius:10px; font-size:15px;">
					</label>
					<label>Téléphone
						<input type="tel" name="antenniste91_tel" autocomplete="tel"
							style="width:100%; margin-top:6px; padding:12px 14px; border:1px solid var(--line); border-radius:10px; font-size:15px;">
					</label>
					<label>Email
						<input type="email" name="antenniste91_email" autocomplete="email"
							style="width:100%; margin-top:6px; padding:12px 14px; border:1px solid var(--line); border-radius:10px; font-size:15px;">
					</label>
					<p style="margin:-8px 0 0; font-size:12.5px; color:var(--muted);">Renseignez au moins un moyen de vous recontacter (téléphone ou email).</p>
					<label>Message
						<textarea name="antenniste91_message" required minlength="5" rows="5"
							style="width:100%; margin-top:6px; padding:12px 14px; border:1px solid var(--line); border-radius:10px; font-size:15px; font-family:inherit;"></textarea>
					</label>
					<button type="submit" class="btn btn-call" style="align-self:flex-start;">Envoyer le message</button>
				</div>
			</form>
		</div>
	</div>
	<?php
endwhile;
?>

<div class="inner-cta">
	<span class="eyebrow inv">Plus rapide qu'un formulaire</span>
	<h2>Un appel suffit pour lancer le diagnostic.</h2>
	<div class="hero-ctas">
		<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
			Appeler maintenant
		</a>
	</div>
</div>

<?php
get_footer();
