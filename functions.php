<?php
namespace AFMainSite\Theme;

define( 'AFMAINSITE_THEME_DIR', trailingslashit( get_stylesheet_directory() ) );


// Theme foundation
include_once AFMAINSITE_THEME_DIR . 'includes/config.php';
include_once AFMAINSITE_THEME_DIR . 'includes/meta.php';

// Add other includes to this file as needed.

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
