<?php
/**
 * Theme functions.
 *
 * @package Legal_Case_Results
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme setup.
 */
function lcr_theme_setup() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');

	register_nav_menus([
		'primary_menu' => __('Primary Menu', 'legal-case-results'),
	]);
}
add_action('after_setup_theme', 'lcr_theme_setup');

/**
 * Enqueue styles and scripts.
 */
function lcr_enqueue_assets() {
	wp_enqueue_style(
		'bootstrap-css',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
		[],
		'5.3.3'
	);

	wp_enqueue_style(
		'lcr-custom-css',
		get_template_directory_uri() . '/assets/css/custom.css',
		['bootstrap-css'],
		'1.0.0'
	);

	wp_enqueue_script(
		'bootstrap-js',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		[],
		'5.3.3',
		true
	);

	wp_enqueue_script(
		'lcr-case-results-js',
		get_template_directory_uri() . '/assets/js/case-results.js',
		['jquery'],
		'1.0.0',
		true
	);

	wp_localize_script('lcr-case-results-js', 'lcr_ajax', [
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce'    => wp_create_nonce('lcr_case_filter_nonce'),
	]);
}
add_action('wp_enqueue_scripts', 'lcr_enqueue_assets');

/**
 * Register Case Results custom post type.
 */
function lcr_register_case_results_cpt() {
	$labels = [
		'name'               => __('Case Results', 'legal-case-results'),
		'singular_name'      => __('Case Result', 'legal-case-results'),
		'menu_name'          => __('Case Results', 'legal-case-results'),
		'name_admin_bar'     => __('Case Result', 'legal-case-results'),
		'add_new'            => __('Add New', 'legal-case-results'),
		'add_new_item'       => __('Add New Case Result', 'legal-case-results'),
		'new_item'           => __('New Case Result', 'legal-case-results'),
		'edit_item'          => __('Edit Case Result', 'legal-case-results'),
		'view_item'          => __('View Case Result', 'legal-case-results'),
		'all_items'          => __('All Case Results', 'legal-case-results'),
		'search_items'       => __('Search Case Results', 'legal-case-results'),
		'not_found'          => __('No case results found.', 'legal-case-results'),
		'not_found_in_trash' => __('No case results found in Trash.', 'legal-case-results'),
	];

	$args = [
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => ['slug' => 'case-results'],
		'menu_icon'          => 'dashicons-portfolio',
		'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
		'show_in_rest'       => true,
		'capability_type'    => 'post',
		'query_var'          => true,
	];

	register_post_type('case_result', $args);
}
add_action('init', 'lcr_register_case_results_cpt');

/**
 * Case type options.
 */
function lcr_get_case_type_options() {
	return [
		'personal_injury'     => __('Personal Injury', 'legal-case-results'),
		'car_accident'        => __('Car Accident', 'legal-case-results'),
		'slip_and_fall'       => __('Slip & Fall', 'legal-case-results'),
		'medical_malpractice' => __('Medical Malpractice', 'legal-case-results'),
	];
}

/**
 * Add metabox.
 */
function lcr_add_case_result_metaboxes() {
	add_meta_box(
		'lcr_case_result_details',
		__('Case Result Marketing Data', 'legal-case-results'),
		'lcr_render_case_result_metabox',
		'case_result',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'lcr_add_case_result_metaboxes');

/**
 * Render metabox fields.
 */
function lcr_render_case_result_metabox($post) {
	wp_nonce_field('lcr_save_case_result_meta', 'lcr_case_result_nonce');

	$case_type         = get_post_meta($post->ID, '_lcr_case_type', true);
	$settlement_amount = get_post_meta($post->ID, '_lcr_settlement_amount', true);
	$case_duration     = get_post_meta($post->ID, '_lcr_case_duration', true);
	$client_city       = get_post_meta($post->ID, '_lcr_client_city', true);
	$client_state      = get_post_meta($post->ID, '_lcr_client_state', true);
	$case_year         = get_post_meta($post->ID, '_lcr_case_year', true);

	$current_year = (int) date('Y');
	?>

	<div class="lcr-admin-box">
		<style>
			.lcr-admin-box {
				padding: 15px;
				background: #f8f9fa;
				border: 1px solid #ddd;
				border-radius: 8px;
			}

			.lcr-admin-grid {
				display: grid;
				grid-template-columns: repeat(2, minmax(0, 1fr));
				gap: 20px;
			}

			.lcr-admin-field label {
				display: block;
				font-weight: 600;
				margin-bottom: 6px;
			}

			.lcr-admin-field input,
			.lcr-admin-field select {
				width: 100%;
				max-width: 100%;
				padding: 8px;
			}

			.lcr-admin-help {
				color: #666;
				font-size: 12px;
				margin-top: 5px;
			}

			@media (max-width: 782px) {
				.lcr-admin-grid {
					grid-template-columns: 1fr;
				}
			}
		</style>

		<div class="lcr-admin-grid">

			<div class="lcr-admin-field">
				<label for="lcr_case_type">
					<?php esc_html_e('Case Type', 'legal-case-results'); ?>
				</label>

				<select name="lcr_case_type" id="lcr_case_type" required>
					<option value="">
						<?php esc_html_e('Select Case Type', 'legal-case-results'); ?>
					</option>

					<?php foreach (lcr_get_case_type_options() as $key => $label) : ?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected($case_type, $key); ?>>
							<?php echo esc_html($label); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="lcr-admin-field">
				<label for="lcr_settlement_amount">
					<?php esc_html_e('Settlement Amount', 'legal-case-results'); ?>
				</label>

				<input
					type="number"
					name="lcr_settlement_amount"
					id="lcr_settlement_amount"
					value="<?php echo esc_attr($settlement_amount); ?>"
					min="0"
					step="0.01"
					placeholder="100000.00"
					required
				>

				<p class="lcr-admin-help">
					<?php esc_html_e('Enter amount without currency symbol. Example: 150000', 'legal-case-results'); ?>
				</p>
			</div>

			<div class="lcr-admin-field">
				<label for="lcr_case_duration">
					<?php esc_html_e('Case Duration in Months', 'legal-case-results'); ?>
				</label>

				<input
					type="number"
					name="lcr_case_duration"
					id="lcr_case_duration"
					value="<?php echo esc_attr($case_duration); ?>"
					min="1"
					max="120"
					step="1"
					placeholder="12"
					required
				>
			</div>

			<div class="lcr-admin-field">
				<label for="lcr_case_year">
					<?php esc_html_e('Case Year', 'legal-case-results'); ?>
				</label>

				<select name="lcr_case_year" id="lcr_case_year" required>
					<option value="">
						<?php esc_html_e('Select Year', 'legal-case-results'); ?>
					</option>

					<?php for ($year = $current_year; $year >= 1990; $year--) : ?>
						<option value="<?php echo esc_attr($year); ?>" <?php selected((int) $case_year, $year); ?>>
							<?php echo esc_html($year); ?>
						</option>
					<?php endfor; ?>
				</select>
			</div>

			<div class="lcr-admin-field">
				<label for="lcr_client_city">
					<?php esc_html_e('Client City', 'legal-case-results'); ?>
				</label>

				<input
					type="text"
					name="lcr_client_city"
					id="lcr_client_city"
					value="<?php echo esc_attr($client_city); ?>"
					placeholder="Chicago"
					maxlength="100"
					required
				>
			</div>

			<div class="lcr-admin-field">
				<label for="lcr_client_state">
					<?php esc_html_e('Client State', 'legal-case-results'); ?>
				</label>

				<select name="lcr_client_state" id="lcr_client_state" required>
					<option value="">
						<?php esc_html_e('Select State', 'legal-case-results'); ?>
					</option>

					<?php foreach (lcr_get_us_state_options() as $state_code => $state_name) : ?>
						<option value="<?php echo esc_attr($state_code); ?>" <?php selected($client_state, $state_code); ?>>
							<?php echo esc_html($state_code . ' - ' . $state_name); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

		</div>
	</div>

	<?php
}

/**
 * Save metabox values.
 */
function lcr_save_case_result_meta($post_id) {
	if (!isset($_POST['lcr_case_result_nonce'])) {
		return;
	}

	if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['lcr_case_result_nonce'])), 'lcr_save_case_result_meta')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	$case_types = lcr_get_case_type_options();

	$case_type = isset($_POST['lcr_case_type'])
		? sanitize_key(wp_unslash($_POST['lcr_case_type']))
		: '';

	if (!array_key_exists($case_type, $case_types)) {
		$case_type = '';
	}

	$settlement_amount = isset($_POST['lcr_settlement_amount'])
		? (float) wp_unslash($_POST['lcr_settlement_amount'])
		: 0;

	$case_duration = isset($_POST['lcr_case_duration'])
		? absint(wp_unslash($_POST['lcr_case_duration']))
		: 0;

	$client_city = isset($_POST['lcr_client_city'])
		? sanitize_text_field(wp_unslash($_POST['lcr_client_city']))
		: '';

	$client_state = isset($_POST['lcr_client_state'])
		? sanitize_text_field(wp_unslash($_POST['lcr_client_state']))
		: '';

	$case_year = isset($_POST['lcr_case_year'])
		? absint(wp_unslash($_POST['lcr_case_year']))
		: 0;

	$current_year = (int) date('Y');

	if ($settlement_amount < 0) {
		$settlement_amount = 0;
	}

	if ($case_duration < 1 || $case_duration > 120) {
		$case_duration = 1;
	}

	if ($case_year < 1990 || $case_year > $current_year) {
		$case_year = $current_year;
	}

	update_post_meta($post_id, '_lcr_case_type', $case_type);
	update_post_meta($post_id, '_lcr_settlement_amount', $settlement_amount);
	update_post_meta($post_id, '_lcr_case_duration', $case_duration);
	update_post_meta($post_id, '_lcr_client_city', $client_city);
	update_post_meta($post_id, '_lcr_client_state', $client_state);
	update_post_meta($post_id, '_lcr_case_year', $case_year);
}
add_action('save_post_case_result', 'lcr_save_case_result_meta');

/**
 * Format settlement amount.
 */
function lcr_format_currency($amount) {
	$amount = (float) $amount;

	return '$' . number_format($amount, 2);
}

/**
 * Get case type label.
 */
function lcr_get_case_type_label($case_type) {
	$options = lcr_get_case_type_options();

	return isset($options[$case_type]) ? $options[$case_type] : __('Unknown', 'legal-case-results');
}

/**
 * Case card HTML.
 */
function lcr_render_case_card($post_id) {
	$case_type         = get_post_meta($post_id, '_lcr_case_type', true);
	$settlement_amount = get_post_meta($post_id, '_lcr_settlement_amount', true);
	$case_duration     = get_post_meta($post_id, '_lcr_case_duration', true);
	$client_city       = get_post_meta($post_id, '_lcr_client_city', true);
	$client_state      = get_post_meta($post_id, '_lcr_client_state', true);
	$case_year         = get_post_meta($post_id, '_lcr_case_year', true);

	$case_type_label = lcr_get_case_type_label($case_type);
	$permalink       = get_permalink($post_id);
	$title           = get_the_title($post_id);
	?>

	<div class="col-12 col-md-6 col-lg-3 mb-4">
		<article
			class="card h-100 border-0 shadow-sm lcr-case-card"
			itemscope
			itemtype="https://schema.org/LegalCase"
		>
			<?php if (has_post_thumbnail($post_id)) : ?>
				<a
					href="<?php echo esc_url($permalink); ?>"
					class="lcr-case-link"
					data-case-type="<?php echo esc_attr($case_type_label); ?>"
					data-settlement-amount="<?php echo esc_attr($settlement_amount); ?>"
				>
					<?php echo get_the_post_thumbnail($post_id, 'medium_large', ['class' => 'card-img-top lcr-card-img']); ?>
				</a>
			<?php endif; ?>

			<div class="card-body">
				<span class="badge bg-primary mb-3" itemprop="about">
					<?php echo esc_html($case_type_label); ?>
				</span>

				<h3 class="h5 card-title" itemprop="name">
					<a
						href="<?php echo esc_url($permalink); ?>"
						class="text-decoration-none text-dark lcr-case-link"
						data-case-type="<?php echo esc_attr($case_type_label); ?>"
						data-settlement-amount="<?php echo esc_attr($settlement_amount); ?>"
					>
						<?php echo esc_html($title); ?>
					</a>
				</h3>

				<ul class="list-unstyled mb-3 lcr-case-meta">
					<li>
						<strong><?php esc_html_e('Settlement:', 'legal-case-results'); ?></strong>
						<span itemprop="award">
							<?php echo esc_html(lcr_format_currency($settlement_amount)); ?>
						</span>
					</li>

					<li>
						<strong><?php esc_html_e('Duration:', 'legal-case-results'); ?></strong>
						<?php echo esc_html($case_duration); ?>
						<?php esc_html_e('months', 'legal-case-results'); ?>
					</li>

					<li>
						<strong><?php esc_html_e('Location:', 'legal-case-results'); ?></strong>
						<span itemprop="location">
							<?php echo esc_html($client_city . ', ' . $client_state); ?>
						</span>
					</li>

					<li>
						<strong><?php esc_html_e('Year:', 'legal-case-results'); ?></strong>
						<span itemprop="dateCreated">
							<?php echo esc_html($case_year); ?>
						</span>
					</li>
				</ul>

				<p class="card-text text-muted">
					<?php echo esc_html(wp_trim_words(get_the_excerpt($post_id), 18)); ?>
				</p>
			</div>

			<div class="card-footer bg-white border-0 pt-0">
				<a
					href="<?php echo esc_url($permalink); ?>"
					class="btn btn-outline-primary w-100 lcr-case-link"
					data-case-type="<?php echo esc_attr($case_type_label); ?>"
					data-settlement-amount="<?php echo esc_attr($settlement_amount); ?>"
				>
					<?php esc_html_e('View Case Result', 'legal-case-results'); ?>
				</a>
			</div>
		</article>
	</div>

	<?php
}

/**
 * Display 5 most recent high-value cases over $100,000.
 *
 * Usage:
 * echo lcr_display_high_value_cases();
 */
function lcr_display_high_value_cases() {
	$query = new WP_Query([
		'post_type'              => 'case_result',
		'post_status'            => 'publish',
		'posts_per_page'         => 5,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'no_found_rows'          => true,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
		'meta_query'             => [
			[
				'key'     => '_lcr_settlement_amount',
				'value'   => 100000,
				'type'    => 'NUMERIC',
				'compare' => '>',
			],
		],
	]);

	ob_start();

	if ($query->have_posts()) :
		?>

		<section class="container my-5">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<div>
					<h2 class="h3 mb-1">
						<?php esc_html_e('Recent High-Value Case Results', 'legal-case-results'); ?>
					</h2>
					<p class="text-muted mb-0">
						<?php esc_html_e('Selected case results over $100,000.', 'legal-case-results'); ?>
					</p>
				</div>
			</div>

			<div class="row">
				<?php
				while ($query->have_posts()) :
					$query->the_post();
					lcr_render_case_card(get_the_ID());
				endwhile;
				?>
			</div>
		</section>

		<?php
	endif;

	wp_reset_postdata();

	return ob_get_clean();
}

/**
 * AJAX filter case results.
 */
function lcr_ajax_filter_case_results() {
	check_ajax_referer('lcr_case_filter_nonce', 'nonce');

	$case_type = isset($_POST['case_type'])
		? sanitize_key(wp_unslash($_POST['case_type']))
		: '';

	$client_state = isset($_POST['client_state'])
		? sanitize_key(wp_unslash($_POST['client_state']))
		: '';

	$client_state = strtoupper($client_state);

	$paged = isset($_POST['page'])
		? absint(wp_unslash($_POST['page']))
		: 1;

	if ($paged < 1) {
		$paged = 1;
	}

	$args = [
		'post_type'              => 'case_result',
		'post_status'            => 'publish',
		'posts_per_page'         => 6,
		'paged'                  => $paged,
		'orderby'                => 'date',
		'order'                  => 'DESC',
		'update_post_meta_cache' => true,
		'update_post_term_cache' => false,
	];

	$meta_query = [
		'relation' => 'AND',
	];

	if (!empty($case_type) && array_key_exists($case_type, lcr_get_case_type_options())) {
		$meta_query[] = [
			'key'     => '_lcr_case_type',
			'value'   => $case_type,
			'compare' => '=',
		];
	}

	if (!empty($client_state) && array_key_exists($client_state, lcr_get_us_state_options())) {
		$meta_query[] = [
			'key'     => '_lcr_client_state',
			'value'   => $client_state,
			'compare' => '=',
		];
	}

	if (count($meta_query) > 1) {
		$args['meta_query'] = $meta_query;
	}

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) :
		?>

		<div class="row">
			<?php
			while ($query->have_posts()) :
				$query->the_post();
				lcr_render_case_card(get_the_ID());
			endwhile;
			?>
		</div>

		<?php
	else :
		?>

		<div class="alert alert-warning">
			<?php esc_html_e('No case results found for this filter.', 'legal-case-results'); ?>
		</div>

		<?php
	endif;

	$html = ob_get_clean();

	ob_start();

	lcr_render_bootstrap_pagination($query->max_num_pages, $paged);

	$pagination = ob_get_clean();

	wp_reset_postdata();

	wp_send_json_success([
		'html'       => $html,
		'pagination' => $pagination,
	]);
}
add_action('wp_ajax_lcr_filter_case_results', 'lcr_ajax_filter_case_results');
add_action('wp_ajax_nopriv_lcr_filter_case_results', 'lcr_ajax_filter_case_results');

/**
 * Bootstrap pagination using ul/li.
 */
function lcr_render_bootstrap_pagination($total_pages, $current_page = 1) {
	$total_pages  = absint($total_pages);
	$current_page = absint($current_page);

	if ($total_pages <= 1) {
		return;
	}
	?>

	<nav class="mt-4" aria-label="<?php esc_attr_e('Case results pagination', 'legal-case-results'); ?>">
		<ul class="pagination justify-content-center flex-wrap">

			<li class="page-item <?php echo $current_page <= 1 ? 'disabled' : ''; ?>">
				<a
					class="page-link lcr-pagination-link"
					href="#"
					data-page="<?php echo esc_attr(max(1, $current_page - 1)); ?>"
				>
					<?php esc_html_e('Previous', 'legal-case-results'); ?>
				</a>
			</li>

			<?php for ($i = 1; $i <= $total_pages; $i++) : ?>
				<li class="page-item <?php echo $i === $current_page ? 'active' : ''; ?>">
					<a
						class="page-link lcr-pagination-link"
						href="#"
						data-page="<?php echo esc_attr($i); ?>"
					>
						<?php echo esc_html($i); ?>
					</a>
				</li>
			<?php endfor; ?>

			<li class="page-item <?php echo $current_page >= $total_pages ? 'disabled' : ''; ?>">
				<a
					class="page-link lcr-pagination-link"
					href="#"
					data-page="<?php echo esc_attr(min($total_pages, $current_page + 1)); ?>"
				>
					<?php esc_html_e('Next', 'legal-case-results'); ?>
				</a>
			</li>

		</ul>
	</nav>

	<?php
}

/**
 * Add archive body class.
 */
function lcr_archive_body_class($classes) {
	if (is_post_type_archive('case_result')) {
		$classes[] = 'lcr-case-results-archive';
	}

	return $classes;
}
add_filter('body_class', 'lcr_archive_body_class');


/**
 * Duplicate post / case result.
 */
function lcr_duplicate_post_as_draft() {
	if (!isset($_GET['post']) || !isset($_GET['action'])) {
		wp_die(esc_html__('Invalid duplicate request.', 'legal-case-results'));
	}

	if ($_GET['action'] !== 'lcr_duplicate_post') {
		wp_die(esc_html__('Invalid duplicate action.', 'legal-case-results'));
	}

	$post_id = absint($_GET['post']);

	if (!$post_id) {
		wp_die(esc_html__('Invalid post ID.', 'legal-case-results'));
	}

	if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'lcr_duplicate_post_' . $post_id)) {
		wp_die(esc_html__('Security check failed.', 'legal-case-results'));
	}

	if (!current_user_can('edit_posts')) {
		wp_die(esc_html__('You do not have permission to duplicate this post.', 'legal-case-results'));
	}

	$post = get_post($post_id);

	if (!$post) {
		wp_die(esc_html__('Original post not found.', 'legal-case-results'));
	}

	$new_post_args = [
		'post_title'     => $post->post_title . ' - Copy',
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_status'    => 'draft',
		'post_type'      => $post->post_type,
		'post_author'    => get_current_user_id(),
		'post_parent'    => $post->post_parent,
		'menu_order'     => $post->menu_order,
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
	];

	$new_post_id = wp_insert_post($new_post_args);

	if (is_wp_error($new_post_id)) {
		wp_die(esc_html__('Duplicate post failed.', 'legal-case-results'));
	}

	/**
	 * Copy featured image.
	 */
	$thumbnail_id = get_post_thumbnail_id($post_id);

	if ($thumbnail_id) {
		set_post_thumbnail($new_post_id, $thumbnail_id);
	}

	/**
	 * Copy custom fields / post meta.
	 */
	$post_meta = get_post_meta($post_id);

	if (!empty($post_meta)) {
		foreach ($post_meta as $meta_key => $meta_values) {
			if (in_array($meta_key, ['_edit_lock', '_edit_last'], true)) {
				continue;
			}

			foreach ($meta_values as $meta_value) {
				add_post_meta($new_post_id, $meta_key, maybe_unserialize($meta_value));
			}
		}
	}

	/**
	 * Copy taxonomies.
	 */
	$taxonomies = get_object_taxonomies($post->post_type);

	if (!empty($taxonomies)) {
		foreach ($taxonomies as $taxonomy) {
			$terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);

			if (!is_wp_error($terms)) {
				wp_set_object_terms($new_post_id, $terms, $taxonomy);
			}
		}
	}

	wp_safe_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
	exit;
}
add_action('admin_action_lcr_duplicate_post', 'lcr_duplicate_post_as_draft');


/**
 * Add Duplicate link in post row actions.
 */
function lcr_add_duplicate_post_link($actions, $post) {
	$allowed_post_types = ['post', 'page', 'case_result'];

	if (!in_array($post->post_type, $allowed_post_types, true)) {
		return $actions;
	}

	if (!current_user_can('edit_post', $post->ID)) {
		return $actions;
	}

	$url = wp_nonce_url(
		admin_url('admin.php?action=lcr_duplicate_post&post=' . $post->ID),
		'lcr_duplicate_post_' . $post->ID
	);

	$actions['lcr_duplicate'] = sprintf(
		'<a href="%s">%s</a>',
		esc_url($url),
		esc_html__('Duplicate', 'legal-case-results')
	);

	return $actions;
}
add_filter('post_row_actions', 'lcr_add_duplicate_post_link', 10, 2);
add_filter('page_row_actions', 'lcr_add_duplicate_post_link', 10, 2);


/**
 * US state options.
 */
function lcr_get_us_state_options() {
	return [
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	];
}
