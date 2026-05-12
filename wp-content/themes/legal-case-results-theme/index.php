<?php
/**
 * Default index template.
 *
 * @package Legal_Case_Results
 */

get_header();
?>

<section class="container my-5">
	<?php if (have_posts()) : ?>
		<div class="row">
			<?php while (have_posts()) : the_post(); ?>
				<div class="col-12 col-md-6 col-lg-4 mb-4">
					<article <?php post_class('card h-100 shadow-sm border-0'); ?>>
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('medium_large', ['class' => 'card-img-top']); ?>
						<?php endif; ?>

						<div class="card-body">
							<h2 class="h5">
								<a href="<?php the_permalink(); ?>" class="text-decoration-none">
									<?php the_title(); ?>
								</a>
							</h2>

							<p class="text-muted">
								<?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
							</p>
						</div>
					</article>
				</div>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php esc_html_e('No posts found.', 'legal-case-results'); ?>
		</div>
	<?php endif; ?>
</section>

<?php
get_footer();
