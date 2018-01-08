<?php
/*
Template Name: Issues
*/
get_header();
echo '<div class="readable">';
	// echo '<div class="issues rows">';
echo '<div class="masonry issues loop three_col">';
		$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
		$per_page = 10;
		$offset = ( $paged - 1 ) * $per_page;
		$issues = get_terms( array(
		  'taxonomy' => 'issues',
		  'hide_empty' => false,
		  'orderby' => 'date',
		  'order' => 'desc',
		  'number' => $per_page,
		  'offset' => $offset,
		  'hide_empty' => 0
		) );
		foreach( $issues as $issue ) {
			$title = $issue->name;
			$slug = $issue->slug;
			$id = $issue->term_id;
			$issue_url = get_term_link( $id, 'issues' );
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
			// echo '<div class="sections issue one_two" role="issue">';
			echo '<div class="cell issue" role="issue">';
				echo '<a class="link_wrap" href="' . $issue_url . '">';
					echo '<div class="text">';
						echo '<h1 class="title">' . $title . '</h1>';
						echo '<h3 class="date">published ' . $date . '</h3>';
					echo '</div>';
					$mag_svg = get_template_directory_uri() . '/assets/images/mag.svg';
					echo file_get_contents( $mag_svg );
					// if ( $posts_query->have_posts() ) {
					// 	echo '<div class="list">';
					// 		while ( $posts_query->have_posts() ) {
					// 			$posts_query->the_post();
					// 			$post_id = get_the_ID();
					// 			$writers = get_contributors_list( $post_id, true, false );
					// 			$permalink = get_permalink();
					// 			echo '<article class="row" role="article" style="' . $style . '" data-id="' . $post_id . '">';
					// 				echo '<h3>';
					// 					echo '<a class="title" href="' . $permalink . '">' . get_the_title() . '</a>';
					// 					if( $writers ) {
					// 						echo '<em class="writer">by ' . $writers . '</em>';
					// 					}
					// 				echo '</h3>';
					// 			echo '</article>';
					// 		}
					// 		wp_reset_query();
					// 	echo '</div>';
					// }
					// echo '<div class="toggle">';
					// 	echo '<div class="circle">';
					// 		$up_svg = get_template_directory_uri() . '/assets/images/up.svg';
					// 		echo file_get_contents( $up_svg );
					// 	echo '</div>';
					// echo '</div>';
				echo '</a>';
			echo '</div>';
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>