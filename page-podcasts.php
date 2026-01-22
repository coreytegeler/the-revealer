<?php
/*
Template Name: Podcast
*/
get_header();
$content = apply_filters( 'the_content', $post->post_content );
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$podcasts_args = array(
	'post_type' => 'podcast',
	'paged' => $paged,
	'posts_per_page' => 12,
	'order' => 'DESC',
	'orderby' => 'meta_value',
	'meta_key' => 'date'
);

echo '<div class="readable">';
	
	echo '<div class="max">';

		echo '<div class="about">';
			echo '<h1 class="page-title">' . $post->post_title . '</h1>';
			echo '<div class="body"><h3>' . $content . '</h3></div>';
		echo '</div>';


		echo '<div class="loop podcasts">';
			query_posts( $podcasts_args );
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'parts/podcast' );
				}
			}
			get_template_part( 'parts/pagination' );
			wp_reset_query();
		echo '</div>';

	echo '</div>';
echo '</div>';
get_footer();
?>