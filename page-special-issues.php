<?php
/*
Template Name: Special Issues
*/
get_header();
$issues_page = get_page_by_path( 'issues' );
$content = apply_filters( 'the_content', $post->post_content );
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<div class="page-title-wrapper">';
			echo '<h1 class="page-title">' . $post->post_title . '</h1>';
			echo '<div class="special-note">';
				echo '<a href="' . get_the_permalink( $issues_page ) . '">View all issues</a>';
			echo '</div>';
		echo '</div>';
		echo '<div class="body">' . $content . '</div>';
		echo '<div class="issues loop row">';
			$issues = get_terms( array(
				'taxonomy' => 'issues',
				'hide_empty' => false,
				'order' => 'DESC',
				'orderby' => 'meta_value',
				'meta_key' => 'date',
				'meta_query' => array(
					array(
						'key' => 'special',
						'value' => true,
						'compare' => '=',
					),
				),
				'number' => 0,
				'hide_empty' => 0
			) );
			foreach( $issues as $issue ) {
				set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
				get_template_part( 'parts/issue-cover' );
			}
			wp_reset_query();
		echo '</div>';
	echo '</div>';
echo '</div>';
get_footer();
?>