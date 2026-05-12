<?php
/**
 * Header template.
 *
 * @package Legal_Case_Results
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header bg-dark text-white">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand fw-bold" href="<?php echo esc_url(home_url('/')); ?>">
				<?php bloginfo('name'); ?>
			</a>

			<button
				class="navbar-toggler"
				type="button"
				data-bs-toggle="collapse"
				data-bs-target="#primaryNavbar"
				aria-controls="primaryNavbar"
				aria-expanded="false"
				aria-label="<?php esc_attr_e('Toggle navigation', 'legal-case-results'); ?>"
			>
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="primaryNavbar">
				<?php
				wp_nav_menu([
					'theme_location' => 'primary_menu',
					'container'      => false,
					'menu_class'     => 'navbar-nav ms-auto mb-2 mb-lg-0',
					'fallback_cb'    => false,
					'depth'          => 2,
				]);
				?>
			</div>
		</div>
	</nav>
</header>

<main class="site-main">
