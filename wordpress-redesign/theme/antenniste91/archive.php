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
	<div class="post-list">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article>
					<span class="meta"><?php echo esc_html( get_the_date() ); ?></span>
					<h2><a href="<?php the_permalink(); ?>" style="text-decoration:none; color:inherit;"><?php the_title(); ?></a></h2>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
				</article>
				<?php
			endwhile;
			?>
			<div style="display:flex; justify-content:space-between;">
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
</div>

<?php
get_footer();
