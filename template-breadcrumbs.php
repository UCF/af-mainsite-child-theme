<?php
/**
 * Template Name: Breadcrumbs
 * Template Post Type: page, post
 */
?>
<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<?php theme_breadcrumbs(); the_content(); ?>
</article>

<?php get_footer(); ?>
