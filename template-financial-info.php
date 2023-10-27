<?php
/**
 * Template Name: Financial Information
 * Template Post Type: page, post
 */
get_header();
the_post();
?>

<article class="<?php echo esc_attr( $post->post_status ); ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php theme_breadcrumbs(); ?>
		<div class="row">
			<div class="col-12 col-md-3 d-none d-md-block"> <!-- Visible on md viewports and above -->
				<!-- Sidebar with Navigation Menu for md viewports and above -->
				<?php
				if (has_nav_menu('records-management-menu')) { // Check if the menu location exists
					$walker_menu = class_exists('BS4_Nav_Walker') ? new BS4_Nav_Walker() : '';
					wp_nav_menu(array(
						'theme_location'  => 'financial-information-menu',
						'container'       => 'nav',
						'container_class' => 'mb-1',
						'menu_class'      => 'nav flex-column',
						'walker'          => $walker_menu,
					));
				} else {
					echo '<p>Menu "financial-information-menu" not defined. Please set it in wp-admin.</p>';
				}
				?>
			</div>

			<!-- Main Content Area -->
			<div class="col-12 col-md-9">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
