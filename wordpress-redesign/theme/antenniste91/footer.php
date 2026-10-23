</main><!-- #site-main -->

<footer class="site-footer">
	<div class="wrap">
		<div class="fgrid">
			<div>
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" style="margin-bottom:14px; display:inline-flex;">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.png' ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'antenniste91_company_name', 'France Technique Antenne' ) ); ?>" style="height:34px; width:auto;">
					<?php endif; ?>
				</a>
				<p class="tag">Antenne TV, parabole, Starlink et vidéosurveillance pour particuliers, professionnels et collectivités en Essonne.</p>
			</div>
			<div>
				<h5>Services</h5>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/services/antenne-tv/' ) ); ?>">Antenne TV &amp; TNT</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/parabole-satellite/' ) ); ?>">Parabole &amp; satellite</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/starlink/' ) ); ?>">Internet Starlink</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/videosurveillance/' ) ); ?>">Vidéosurveillance</a></li>
				</ul>
			</div>
			<div>
				<h5>Entreprise</h5>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/zone-intervention/' ) ); ?>">Zone d'intervention</a></li>
					<li><a href="<?php echo esc_url( home_url( '/avis-clients/' ) ); ?>">Avis clients</a></li>
					<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">Questions fréquentes</a></li>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
				</ul>
			</div>
			<div>
				<h5>Contact</h5>
				<ul>
					<li><a href="<?php echo esc_url( antenniste91_phone_href() ); ?>"><?php echo antenniste91_phone_display(); ?></a></li>
					<li><?php $email = get_theme_mod( 'antenniste91_email', '' ); echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '<span class="ph">email à renseigner</span>'; ?></li>
					<li><?php $addr = get_theme_mod( 'antenniste91_address', '' ); echo $addr ? esc_html( $addr ) : '<span class="ph">adresse à renseigner</span>'; ?></li>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<span>
				© <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<?php echo esc_html( get_theme_mod( 'antenniste91_company_name', 'Antenniste 91' ) ); ?>
				<?php $siret = get_theme_mod( 'antenniste91_siret', '' ); if ( $siret ) : ?>, SIRET <?php echo esc_html( $siret ); ?><?php endif; ?>
			</span>
			<span>
				<a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>">Mentions légales</a> ·
				<a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">Confidentialité</a> ·
				<a href="<?php echo esc_url( home_url( '/cgu/' ) ); ?>">CGU</a>
			</span>
		</div>
	</div>
</footer>

<div class="sticky-call">
	<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
		<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.7 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.2 1.2.4 2.5.6 3.9.6.6 0 1.1.5 1.1 1.1v3.8c0 .6-.5 1.1-1.1 1.1C9.8 21.6 2.4 14.2 2.4 2.5c0-.6.5-1.1 1.1-1.1H7.3c.6 0 1.1.5 1.1 1.1 0 1.4.2 2.7.6 3.9.1.4 0 .8-.3 1.1L6.6 10.8z"/></svg>
		Appeler maintenant
	</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
