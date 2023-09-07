<?php
/**
 * Template Name: Records Management
 * Template Post Type: page, post
 */
get_header();
the_post();
?>

<article class="<?php echo esc_attr( $post->post_status ); ?> post-list-item">
	<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
		<?php theme_breadcrumbs(); ?>
		<div class="row">
			<!-- Sidebar with Navigation Menu -->
			<div class="col-12 col-md-3">
				<?php
				wp_nav_menu(array(
					'theme_location'  => 'records-management-menu',
					'container'       => 'nav',
					'container_class' => 'mb-4',
					'menu_class'      => 'nav flex-column',
					'walker'          => new BS4_Nav_Walker(),
				));
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
