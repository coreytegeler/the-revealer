<?php
/*
Template Name: Issues
*/
get_header();
echo '<div class="readable">';
	echo '<div class="issues loop row">';
		$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
		$per_page = 0;
		$offset = ( $paged - 1 ) * $per_page;
		$issues = get_terms( array(
		  'taxonomy' => 'issues',
		  'hide_empty' => false,
		  'order' => 'DESC',
			'orderby' => 'meta_value',
			'meta_key' => 'date',
		  'number' => $per_page,
		  'offset' => $offset,
		  'hide_empty' => 0
		) );
		foreach( $issues as $issue ) {
			set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
			get_template_part( 'parts/issue-cover' );
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>