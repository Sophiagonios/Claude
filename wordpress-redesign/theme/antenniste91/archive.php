<?php
/**
 * Blog listing.
 */

get_header();
?>

<div class="page-banner">
	<div class="wrap">
		<span class="eyebrow">Le blog</span>
		<h1><?php echo is_home() ? 'Conseils antenne, Starlink et vidéosurveillance' : wp_kses_post( get_the_archive_title() ); ?></h1>
	</div>
</div>

<div class="wrap section-pad">
	<?php if ( have_posts() ) : ?>
		<div class="services-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<a class="card" href="<?php the_permalink(); ?>">
					<div class="card-photo"><?php echo antenniste91_photo_ph_inner( get_the_title() ); ?></div>
					<div class="card-body">
						<span class="post-meta"><?php echo esc_html( get_the_date() ); ?></span>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						<span class="link-arrow">Lire l'article →</span>
					</div>
				</a>
				<?php
			endwhile;
			?>
		</div>
		<div style="display:flex; justify-content:space-between; margin-top:36px;">
			<?php
			the_posts_pagination(
				array(
					'prev_text' => '← Plus récents',
					'next_text' => 'Plus anciens →',
				)
			);
			?>
		</div>
	<?php else : ?>
		<p>Aucun article pour le moment.</p>
	<?php endif; ?>
</div>

<?php
get_footer();
