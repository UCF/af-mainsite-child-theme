<?php

// Theme foundation
include_once AFMAINSITE_THEME_DIR . 'includes/config.php';
include_once AFMAINSITE_THEME_DIR . 'includes/meta.php';

// Add other includes to this file as needed.

function theme_breadcrumbs() {
	global $post;

	echo '<ol class="breadcrumb" role="navigation" aria-label="breadcrumb">';

	// Home link
	echo '<li class="breadcrumb-item"><a href="' . get_home_url() . '">Home</a></li>';

	if (is_home() || is_front_page()) {
		// Do nothing, you're already home
	} elseif (is_single()) {
		// For single posts, show the post category and then the post title
		$categories = get_the_category();
		if ($categories) {
			$cat = $categories[0]; // Let's just take the first category
			echo '<li class="breadcrumb-item"><a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a></li>';
		}
		echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
	} elseif (is_page()) {
		// For pages, show the page hierarchy
		if ($post->post_parent) {
			$ancestors = get_post_ancestors($post->ID);
			foreach ($ancestors as $ancestor) {
				echo '<li class="breadcrumb-item"><a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
			}
		}
		echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
	} elseif (is_category()) {
		// For category archives, show the category name
		echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title('', false) . '</li>';
	}
	// Extend this for other content types (tags, custom post types, etc.)

	echo '</ol>';
}
