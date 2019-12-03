<?php
get_header();
$issue = $wp_query->get_queried_object();
echo '<div class="readable">';
	$id = $issue->term_id;
	$posts_args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'orderby' => 'date',
	  'order' => 'asc',
	  'tax_query' => array(
	  	array(
				'taxonomy' => 'issues',
				'field' => 'id',
				'terms' => $id
			)
	  )
	);
	$posts_query = new WP_Query( $posts_args );
	echo '<div class="loop articles row issue">';
		set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
		get_template_part( 'parts/issue-cover' );
		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
				get_template_part( 'parts/article' );
			}
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>