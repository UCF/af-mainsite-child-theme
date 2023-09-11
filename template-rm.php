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
					'container_class' => 'mb-1',
					'menu_class'      => 'nav flex-column',
					'walker'          => new BS4_Nav_Walker(),
				));
				?>
				<?php
				// Fetch ACF fields from the 'RMLO' options page
				$rmlo_image = get_field('photo', 'option');
				$rmlo_name = get_field('name', 'option');
				$rmlo_title = get_field('title', 'option');
				$rmlo_email = get_field('email', 'option');
				$rmlo_phone = get_field('phone', 'option');

				// Construct email and phone blocks, checking if they are not empty
				$email_block = (!empty($rmlo_email)) ? '<fa class="fa fa-envelope mr-2"></fa> <a href="mailto:' . esc_attr($rmlo_email) . '">' . esc_html($rmlo_email) . '</a><br>' : '';
				$phone_block = (!empty($rmlo_phone)) ? '<fa class="fa fa-phone mr-2"></fa> <a href="tel:' . esc_attr($rmlo_phone) . '">' . esc_html($rmlo_phone) . '</a>' : '';

				// Construct the HTML output for the RMLO section
				$output = '
				<div class="col col-12 p-2 pb-3">
					<img src="' . esc_url($rmlo_image['url']) . '" alt="' . esc_attr($rmlo_image['alt']) . '" class="img-thumbnail rmlo-image" />
					<h4 class="mt-2 h6">' . esc_html($rmlo_name) . '</h4>
					<p class="pb-1 mb-0">' . esc_html($rmlo_title) . '</p>
					' . $email_block . '
					' . $phone_block . '
				</div>';

				// Echo the output to display it in your template
				echo $output;
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
