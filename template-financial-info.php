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
			<div class="col-12 d-md-none"> <!-- Visible on small viewports and below -->
				<!-- Responsive Navbar for sm viewports and below -->
				<nav class="navbar navbar-expand-sm navbar-light bg-light"> <!-- Adjust for small viewports -->
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerRecords" aria-controls="navbarTogglerRecords" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarTogglerRecords">
						<?php
						wp_nav_menu(array(
							'theme_location'  => 'financial-information-menu',
							'depth'           => 2,
							'container'       => false,
							'menu_class'      => 'navbar-nav mr-auto',
							'walker'          => new BS4_Nav_Walker(),
						));
						?>
					</div>
				</nav>
			</div>
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
