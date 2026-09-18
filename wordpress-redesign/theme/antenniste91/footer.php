</main><!-- #site-main -->

<footer class="site-footer">
	<div class="wrap">
		<div class="fgrid">
			<div>
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" style="margin-bottom:14px; display:inline-flex;">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.png' ); ?>" alt="" style="height:34px; width:auto;">
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
				<?php $siret = get_theme_mod( 'antenniste91_siret', '' ); if ( $siret ) : ?>— SIRET <?php echo esc_html( $siret ); ?><?php endif; ?>
			</span>
			<span><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>">Mentions légales</a></span>
		</div>
	</div>
</footer>

<div class="sticky-call">
	<a class="btn btn-call" href="<?php echo esc_url( antenniste91_phone_href() ); ?>">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
		Appeler maintenant
	</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
