<?php
/**
 * Template Name: Direct Support Organizations
 * Template Post Type: page, post
 */
?>
<?php get_header(); the_post(); ?>

	<article class="<?php echo $post->post_status; ?> post-list-item">
		<div class="container mt-4 mt-sm-5 mb-5 pb-sm-4">
			<?php theme_breadcrumbs(); the_content(); ?>
			
			<div class="container">
				<div class="row">
					<?php
					// Check if 'group' repeater field has rows
					if( have_rows('group') ):

						// Loop through 'group' repeater field rows
						while( have_rows('group') ): the_row();

							// Get 'organization_group_title' sub-field value
							$organization_group_title = get_sub_field('organization_group_title');
							?>
							<div class="col-12 col-md-6 p-3">
								<h2 class="heading-underline h3"><?php echo esc_html($organization_group_title); ?></h2>

								<?php
								// Check if 'unit' repeater field has rows
								if( have_rows('unit') ):

									// Loop through 'unit' repeater field rows
									while( have_rows('unit') ): the_row();

										// Get 'unit_name' and 'unit_url' sub-field values
										$unit_name = get_sub_field('unit_name');
										$unit_url = get_sub_field('unit_url');
										?>
										<a class="mb-2 pb-0" href="<?php echo esc_url($unit_url); ?>"><?php echo esc_html($unit_name); ?></a><br>
									<?php
									endwhile;
								else:
									echo '<p>No units available.</p>';
								endif;
								?>
							</div>
						<?php
						endwhile;
					else:
						echo '<div class="col-12"><p>No groups available.</p></div>';
					endif;
					?>
				</div>
			</div>
		</div>
	</article>

<?php get_footer(); ?>
