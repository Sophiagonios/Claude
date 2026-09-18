<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="topstrip">
	<div class="wrap">
		<span>
			📡 Antenne, parabole, Starlink ou caméra : un technicien vous répond en Essonne —
			<a href="<?php echo esc_url( antenniste91_phone_href() ); ?>">Appelez directement →</a>
		</span>
	</div>
</div>

<header class="site-header">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.png' ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ); ?>" style="height:40px; width:auto;">
			<?php endif; ?>
			<span class="name"><?php echo esc_html( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ); ?></span>
		</a>

		<?php antenniste91_render_desktop_nav( antenniste91_get_menu_tree( 'primary' ) ); ?>

		<div class="hdr-actions">
			<a class="btn btn-call btn-devis" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
				Appeler — <?php echo antenniste91_phone_display(); ?>
			</a>
			<button class="hamburger" id="mnav-open" aria-label="Ouvrir le menu" aria-expanded="false">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
			</button>
		</div>
	</div>
</header>

<div class="mobile-nav" id="mnav">
	<div class="panel">
		<div class="mnav-top">
			<span class="disp" style="font-size:18px;"><?php bloginfo( 'name' ); ?></span>
			<button class="hamburger" id="mnav-close" aria-label="Fermer le menu">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
			</button>
		</div>

		<?php antenniste91_render_mobile_nav( antenniste91_get_menu_tree( 'primary' ) ); ?>

		<div class="mcta">
			<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
				Appeler — <?php echo antenniste91_phone_display(); ?>
			</a>
		</div>
	</div>
</div>

<main id="site-main">
