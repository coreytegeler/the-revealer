<?php
/*
Template Name: Issues
*/
get_header();
echo '<div class="readable">';
	echo '<div class="issues rows">';
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
		echo next_posts_link( 'NEXT' );
		foreach( $issues as $issue ) {
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
			echo '<div class="sections issue one_two" role="issue">';
				echo '<section>';
					echo '<div class="text">';
						echo '<h1 class="title">' . $title . '</h1>';
						if( get_field( 'special', $issue ) ) {
							echo '<h2 class="date">published ' . $date . '</h2>';
						}
					echo '</div>';
				echo '</section>';

				echo '<section>';
					if ( $posts_query->have_posts() ) {
						echo '<div class="loop posts list">';
							while ( $posts_query->have_posts() ) {
								$posts_query->the_post();
								$writers = get_contributors_list( true, false );
								$permalink = get_permalink();
								echo '<article class="cell" role="article" style="' . $style . '" data-id="' . get_the_ID() . '">';
									echo '<h3>';
										echo '<a class="title" href="' . $permalink . '">' . get_the_title() . '</a>';
										echo '<em class="writer">' . $writers . '</em>';
									echo '</h3>';
								echo '</article>';
							}
						echo '</div>';
					}
				echo '</section>';
				wp_reset_query();
			echo '</div>';
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_template_part( 'parts/pagination' );
get_footer();
?>