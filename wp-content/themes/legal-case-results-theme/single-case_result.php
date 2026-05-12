<?php
/**
 * Single Case Result template.
 *
 * @package Legal_Case_Results
 */

get_header();

if (have_posts()) :
	while (have_posts()) :
		the_post();

		$post_id           = get_the_ID();
		$case_type         = get_post_meta($post_id, '_lcr_case_type', true);
		$settlement_amount = get_post_meta($post_id, '_lcr_settlement_amount', true);
		$case_duration     = get_post_meta($post_id, '_lcr_case_duration', true);
		$client_city       = get_post_meta($post_id, '_lcr_client_city', true);
		$client_state      = get_post_meta($post_id, '_lcr_client_state', true);
		$case_year         = get_post_meta($post_id, '_lcr_case_year', true);
		$case_type_label   = lcr_get_case_type_label($case_type);
		?>

		<section class="container my-5">
			<div class="row">
				<div class="col-lg-8">
					<article
						<?php post_class('lcr-single-case'); ?>
						itemscope
						itemtype="https://schema.org/LegalCase"
					>
						<span class="badge bg-primary mb-3" itemprop="about">
							<?php echo esc_html($case_type_label); ?>
						</span>

						<h1 class="display-6 fw-bold mb-4" itemprop="name">
							<?php the_title(); ?>
						</h1>

						<?php if (has_post_thumbnail()) : ?>
							<div class="mb-4">
								<?php the_post_thumbnail('large', ['class' => 'img-fluid rounded shadow-sm']); ?>
							</div>
						<?php endif; ?>

						<div class="content mb-4" itemprop="description">
							<?php the_content(); ?>
						</div>
					</article>
				</div>

				<div class="col-lg-4">
					<div class="card border-0 shadow-sm sticky-lg-top lcr-single-sidebar">
						<div class="card-body">
							<h2 class="h5 mb-3">
								<?php esc_html_e('Case Summary', 'legal-case-results'); ?>
							</h2>

							<ul class="list-group list-group-flush">
								<li class="list-group-item px-0 d-flex justify-content-between">
									<strong><?php esc_html_e('Settlement', 'legal-case-results'); ?></strong>
									<span itemprop="award">
										<?php echo esc_html(lcr_format_currency($settlement_amount)); ?>
									</span>
								</li>

								<li class="list-group-item px-0 d-flex justify-content-between">
									<strong><?php esc_html_e('Duration', 'legal-case-results'); ?></strong>
									<span>
										<?php echo esc_html($case_duration); ?>
										<?php esc_html_e('months', 'legal-case-results'); ?>
									</span>
								</li>

								<li class="list-group-item px-0 d-flex justify-content-between">
									<strong><?php esc_html_e('Location', 'legal-case-results'); ?></strong>
									<span itemprop="location">
										<?php echo esc_html($client_city . ', ' . $client_state); ?>
									</span>
								</li>

								<li class="list-group-item px-0 d-flex justify-content-between">
									<strong><?php esc_html_e('Year', 'legal-case-results'); ?></strong>
									<span itemprop="dateCreated">
										<?php echo esc_html($case_year); ?>
									</span>
								</li>
							</ul>

							<a href="<?php echo esc_url(get_post_type_archive_link('case_result')); ?>" class="btn btn-primary w-100 mt-4">
								<?php esc_html_e('Back to Case Results', 'legal-case-results'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php
	endwhile;
endif;

get_footer();
