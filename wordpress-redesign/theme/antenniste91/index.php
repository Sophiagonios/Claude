<?php
/**
 * Fallback template (search results, 404, anything without a more specific template).
 */

get_header();
?>

<div class="wrap section-pad">
	<div class="entry-content">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php
			endwhile;
			?>
		<?php else : ?>
			<h1>Page introuvable</h1>
			<p>Le contenu demandé n'existe pas ou plus.
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l'accueil</a>.
			</p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
