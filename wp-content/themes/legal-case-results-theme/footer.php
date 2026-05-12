<?php
/**
 * Footer template.
 *
 * @package Legal_Case_Results
 */

if (!defined('ABSPATH')) {
	exit;
}
?>

</main>

<footer class="site-footer bg-dark text-white py-4 mt-5">
	<div class="container text-center">
		<p class="mb-0">
			&copy; <?php echo esc_html(date('Y')); ?>
			<?php bloginfo('name'); ?>.
			<?php esc_html_e('All rights reserved.', 'legal-case-results'); ?>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
