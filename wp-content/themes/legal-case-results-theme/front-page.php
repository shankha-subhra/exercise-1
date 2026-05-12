<?php
/**
 * Front page template.
 *
 * Shows the Case Results filter, AJAX grid, Bootstrap pagination,
 * and GTM tracking on the homepage.
 *
 * @package Legal_Case_Results
 */

get_header();

$current_page = max(1, get_query_var('paged'));
$case_types   = lcr_get_case_type_options();

$states = lcr_get_us_state_options();

$args = [
	'post_type'              => 'case_result',
	'post_status'            => 'publish',
	'posts_per_page'         => 6,
	'paged'                  => $current_page,
	'orderby'                => 'date',
	'order'                  => 'DESC',
	'update_post_meta_cache' => true,
	'update_post_term_cache' => false,
];

$case_query = new WP_Query($args);
?>

<section class="lcr-archive-hero py-5 bg-light">
	<div class="container">
		<div class="row align-items-center gy-4">
			<div class="col-lg-8">
				<span class="badge bg-primary mb-3">
					<?php esc_html_e('Legal Case Results', 'legal-case-results'); ?>
				</span>

				<h1 class="display-5 fw-bold mb-3">
					<?php esc_html_e('Proven Case Results for Real Clients', 'legal-case-results'); ?>
				</h1>

				<p class="lead text-muted mb-0">
					<?php esc_html_e('Browse recent case results by case type, settlement value, location, and year.', 'legal-case-results'); ?>
				</p>
			</div>

			<div class="col-lg-4 text-lg-end">
				<a href="<?php echo esc_url(get_post_type_archive_link('case_result')); ?>" class="btn btn-primary btn-lg">
					<?php esc_html_e('View All Case Results', 'legal-case-results'); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<section class="lcr-home-hero py-5 bg-light border-bottom">
	<div class="container">
		<div class="card border-0 shadow-sm mb-4">
			<div class="card-body">
				<form id="lcr-filter-form" class="row g-3 align-items-end">

					<div class="col-12 col-md-5">
						<label for="lcr-case-type-filter" class="form-label fw-semibold">
							<?php esc_html_e('Filter by Case Type', 'legal-case-results'); ?>
						</label>

						<select id="lcr-case-type-filter" class="form-select" name="case_type">
							<option value="">
								<?php esc_html_e('All Case Types', 'legal-case-results'); ?>
							</option>

							<?php foreach ($case_types as $key => $label) : ?>
								<option value="<?php echo esc_attr($key); ?>">
									<?php echo esc_html($label); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-12 col-md-5">
						<label for="lcr-state-filter" class="form-label fw-semibold">
							<?php esc_html_e('Filter by State', 'legal-case-results'); ?>
						</label>

						<select id="lcr-state-filter" class="form-select" name="client_state">
							<option value="">
								<?php esc_html_e('All States', 'legal-case-results'); ?>
							</option>

							<?php foreach ($states as $state_code => $state_name) : ?>
								<option value="<?php echo esc_attr($state_code); ?>">
									<?php echo esc_html($state_code . ' - ' . $state_name); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-12 col-md-2">
						<button type="submit" class="btn btn-primary w-100">
							<?php esc_html_e('Filter', 'legal-case-results'); ?>
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
</section>

<section class="container my-5">
	<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
		<div>
			<h2 class="h3 fw-bold mb-1">
				<?php esc_html_e('Latest Case Results', 'legal-case-results'); ?>
			</h2>
			<p class="text-muted mb-0">
				<?php esc_html_e('Use the filter above to view case results by legal practice area.', 'legal-case-results'); ?>
			</p>
		</div>
	</div>

	<div id="lcr-loading" class="text-center py-5 d-none">
		<div class="spinner-border text-primary" role="status">
			<span class="visually-hidden">
				<?php esc_html_e('Loading...', 'legal-case-results'); ?>
			</span>
		</div>
	</div>

	<div id="lcr-case-results-wrap">
		<?php if ($case_query->have_posts()) : ?>
			<div class="row">
				<?php
				while ($case_query->have_posts()) :
					$case_query->the_post();
					lcr_render_case_card(get_the_ID());
				endwhile;
				?>
			</div>
		<?php else : ?>
			<div class="alert alert-warning">
				<?php esc_html_e('No case results found.', 'legal-case-results'); ?>
			</div>
		<?php endif; ?>
	</div>

	<div id="lcr-pagination-wrap">
		<?php lcr_render_bootstrap_pagination($case_query->max_num_pages, $current_page); ?>
	</div>
</section>

<?php
wp_reset_postdata();

get_footer();
