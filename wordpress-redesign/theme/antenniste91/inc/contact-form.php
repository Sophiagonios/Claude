<?php
/**
 * Native contact form handling — no plugin dependency.
 *
 * Nonces expire, so the form can never be baked into the page's stored
 * content (post_content); it has to be rendered at request time. That's
 * why it lives in page-contact.php rather than inc/content.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function antenniste91_handle_contact_form() {
	if ( ! isset( $_POST['antenniste91_contact_nonce'] ) || ! wp_verify_nonce( $_POST['antenniste91_contact_nonce'], 'antenniste91_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', home_url( '/contact/' ) ) );
		exit;
	}

	// Honeypot: a field real visitors never see or fill in. Any value here means a bot.
	if ( ! empty( $_POST['antenniste91_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', home_url( '/contact/' ) ) ); // Pretend success so bots don't learn.
		exit;
	}

	$name    = isset( $_POST['antenniste91_name'] ) ? sanitize_text_field( wp_unslash( $_POST['antenniste91_name'] ) ) : '';
	$phone   = isset( $_POST['antenniste91_tel'] ) ? sanitize_text_field( wp_unslash( $_POST['antenniste91_tel'] ) ) : '';
	$email   = isset( $_POST['antenniste91_email'] ) ? sanitize_email( wp_unslash( $_POST['antenniste91_email'] ) ) : '';
	$message = isset( $_POST['antenniste91_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['antenniste91_message'] ) ) : '';

	$errors = array();
	if ( strlen( $name ) < 2 ) {
		$errors[] = 'name';
	}
	if ( ! $phone && ! is_email( $email ) ) {
		$errors[] = 'contact';
	}
	if ( strlen( $message ) < 5 ) {
		$errors[] = 'message';
	}

	if ( $errors ) {
		wp_safe_redirect( add_query_arg( 'contact', 'invalid', home_url( '/contact/' ) ) );
		exit;
	}

	$to = get_theme_mod( 'antenniste91_email', get_option( 'admin_email' ) );
	$subject = 'Nouvelle demande depuis le site — ' . $name;
	$body  = "Nom : $name\n";
	$body .= "Téléphone : $phone\n";
	$body .= "Email : $email\n\n";
	$body .= "Message :\n$message\n";
	$headers = array();
	if ( is_email( $email ) ) {
		$headers[] = "Reply-To: $name <$email>";
	}

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'sent' : 'error', home_url( '/contact/' ) ) );
	exit;
}
add_action( 'admin_post_antenniste91_contact', 'antenniste91_handle_contact_form' );
add_action( 'admin_post_nopriv_antenniste91_contact', 'antenniste91_handle_contact_form' );
