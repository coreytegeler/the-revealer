<?php
get_header();
$issue = $wp_query->get_queried_object();
echo '<div class="readable">';
	// echo '<div class="issues rows">';
	$title = $issue->name;
	$slug = $issue->slug;
	$id = $issue->term_id;
	$link = '';
	$date = get_field( 'date', $issue );
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
	$post_count = $posts_query->post_count;
	echo '<div class="masonry issues loop three_col">';
		echo '<div class="cell issue" role="issue">';
			echo '<div class="text">';
				echo '<h1 class="title">' . $title . '</h1>';
				echo '<h3 class="date">published ' . $date . '</h3>';
			echo '</div>';
			$mag_svg = get_template_directory_uri() . '/assets/images/mag.svg';
			echo file_get_contents( $mag_svg );
		echo '</div>';
		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				get_template_part( 'parts/article' );
			}
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>