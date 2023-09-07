<?php
//namespace AFMainSite\Theme;

define( 'AFMAINSITE_THEME_DIR', trailingslashit( get_stylesheet_directory() ) );


// Theme foundation
include_once AFMAINSITE_THEME_DIR . 'includes/config.php';
include_once AFMAINSITE_THEME_DIR . 'includes/meta.php';

// Add other includes to this file as needed.


//Fetch breadcrumbs to display at top of template page
if ( ! function_exists( 'theme_breadcrumbs' ) ) {
	function theme_breadcrumbs() {
		global $post;

		echo '<div class="container mt-4">';
		echo '<ol class="breadcrumb" role="navigation" aria-label="breadcrumb">';

		// Home link
		echo '<li class="breadcrumb-item"><a href="' . home_url() . '">Home</a></li>';

		// If it's a page and it has a parent
		if (is_page() && $post->post_parent) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();

			while ($parent_id) {
				$page          = get_page($parent_id);
				$breadcrumbs[] = '<li class="breadcrumb-item"><a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a></li>';
				$parent_id     = $page->post_parent;
			}

			// Display the parent pages in correct order
			echo implode(array_reverse($breadcrumbs));
		}

		// Current page or post
		if (is_page() || is_single()) {
			echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
		}

		echo '</ol>';
		echo '</div>';
	}
}
//Set default page template to breadcrumbs for new pages
function set_default_page_template() {
	global $post;
	if ('page' == $post->post_type && 'auto-draft' == $post->post_status ) {
		$default_template = locate_template( array( 'template-breadcrumbs.php' ) ); // Change 'breadcrumbs-template.php' to your template's filename
		if (!empty($default_template)) {
			update_post_meta($post->ID, '_wp_page_template', 'template-breadcrumbs.php');
		}
	}
}

add_action('admin_init', 'set_default_page_template');

//Add custom post type for people
function create_people_post_type() {
	$labels = array(
		'name'               => _x( 'People', 'post type general name', 'af-mainsite-child-theme' ),
		'singular_name'      => _x( 'Person', 'post type singular name', 'af-mainsite-child-theme' ),
		'menu_name'          => _x( 'People', 'admin menu', 'af-mainsite-child-theme' ),
		'name_admin_bar'     => _x( 'Person', 'add new on admin bar', 'af-mainsite-child-theme' ),
		'add_new'            => _x( 'Add New', 'person', 'af-mainsite-child-theme' ),
		'add_new_item'       => __( 'Add New Person', 'af-mainsite-child-theme' ),
		'new_item'           => __( 'New Person', 'af-mainsite-child-theme' ),
		'edit_item'          => __( 'Edit Person', 'af-mainsite-child-theme' ),
		'view_item'          => __( 'View Person', 'af-mainsite-child-theme' ),
		'all_items'          => __( 'All People', 'af-mainsite-child-theme' ),
		'search_items'       => __( 'Search People', 'af-mainsite-child-theme' ),
		'not_found'          => __( 'No people found.', 'af-mainsite-child-theme' ),
		'not_found_in_trash' => __( 'No people found in Trash.', 'af-mainsite-child-theme' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'people' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'         => array('category')
	);

	register_post_type( 'people', $args );
}

add_action( 'init', 'create_people_post_type' );

//Add meta_boxes categories for people post type
function add_category_meta_boxes() {
	register_taxonomy_for_object_type('category', 'people');
}

add_action('init', 'add_category_meta_boxes');

//Add shortcode to fetch and display people post types.
//Ex usage - [af_people layout="leadership"]
function people_by_category_shortcode($atts) {
	$atts = shortcode_atts(array(
		'category' => '',
		'layout' => 'leadership'
	), $atts);

	$output = '<div class="people-posts">';

	if ($atts['layout'] === 'team') {
		$categories = get_categories(array(
			'orderby' => 'name',
			'order' => 'ASC',
			'exclude' => array(get_cat_ID('leadership-team'), get_cat_ID('uncategorized'))
		));

		foreach ($categories as $category) {
			// Skip the 'leadership-team' category
			if ( 'leadership-team' === $category->slug ) {
				continue;
			}

			// Fetch posts in the current category, sorted by 'team_order' and then by 'date'
			$args = array(
				'post_type' => 'people',
				'posts_per_page' => -1,
				'cat' => $category->term_id,
				'meta_key' => 'team_order',
				'orderby' => array(
					'meta_value_num' => 'ASC',
					'date' => 'DESC'
				),
			);

			$query = new WP_Query($args);

			if ($query->have_posts()) {
				$output .= '<div class="container pb-3">';
				$output .= '<h3 class="heading-underline pb-2">' . $category->name . '</h3>';
				$output .= '<div class="row">';

				while ($query->have_posts()) {
					$query->the_post();

					// Fetch ACF fields using the specified prefix
					$team_image = get_field('image');
					$team_name = get_field('name');
					$team_title = get_field('title');
					$team_email = get_field('email');
					$team_phone = get_field('phone_number');

					$email_block = (!empty($team_email)) ? '<fa class="fa fa-envelope mr-2"></fa> <a href="mailto:' . esc_attr($team_email) . '">' . esc_html($team_email) . '</a><br>' : '';
					$phone_block = (!empty($team_phone)) ? '<fa class="fa fa-phone mr-2"></fa> <a href="tel:' . esc_attr($team_phone) . '">' . esc_html($team_phone) . '</a>' : '';

					$output .= '
						<div class="col col-4 col-md-3 p-2 pb-3">
							<img src="' . esc_url($team_image['url']) . '" alt="' . esc_attr($team_image['alt']) . '" class="img-thumbnail" />
							<h4 class="mt-2 h6">' . esc_html($team_name) . '</h4>
							<p class="pb-1 mb-0">' . esc_html($team_title) . '</p>
							' . $email_block . '
							' . $phone_block . '
						</div>';
				}

				$output .= '</div></div>';
			}

			wp_reset_postdata();
		}
	} else {
		$args = array(
			'post_type' => 'people',
			'posts_per_page' => -1,
			'meta_key' => 'leadership_order',
			'orderby' => array(
				'meta_value_num' => 'ASC',
				'date' => 'DESC'
			)
		);

		if (!empty($atts['category'])) {
			$args['category_name'] = $atts['category'];
		}

		$query = new WP_Query($args);

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();

				// Assume ACF fields are used
				$person_name = get_field('name', $post_id);
				$person_title = get_field('title', $post_id);
				$person_email = get_field('email', $post_id);
				$person_phone = get_field('phone_number', $post_id);

				$output .= '
                <div class="container pb-5">
                    <div class="row">
                        <div class="col col-4 col-md-3">' . get_the_post_thumbnail($post_id) . '</div>
                        <div class="col col-8 col-md-9">
                            <h3>' . esc_html($person_name) . '</h3>
                            <p class="pb-3">' . esc_html($person_title) . '</p>
                            <fa class="fa fa-envelope mr-2"></fa> <a href="mailto:' . esc_attr($person_email) . '">' . esc_html($person_email) . '</a><br>
                            <fa class="fa fa-phone mr-2"></fa> <a href="tel:' . esc_attr($person_phone) . '">' . esc_html($person_phone) . '</a><br>
                            <a class="btn btn-primary btn-sm" data-toggle="collapse" href="' . get_permalink($post_id) . '">Bio</a>
                        </div>
                    </div>
                </div>';
			}
			wp_reset_postdata();
		}
	}

	$output .= '</div>';
	return $output;
}

add_shortcode('af_people', 'people_by_category_shortcode');
