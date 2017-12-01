<?php
/*
Template Name: Columns
*/
get_header();
echo '<div class="readable">';
	echo '<div class="columns rows">';
		$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
		$per_page = 10;
		$offset = ( $paged - 1 ) * $per_page;

		$columns = get_terms( array(
		  'taxonomy' => 'columns',
		  'hide_empty' => false,
		  'orderby' => 'date',
		  'order' => 'desc',
		  'number' => $per_page,
		  'offset' => $offset,
		  'hide_empty' => 0
		) );
		echo next_posts_link( 'NEXT' );
		foreach( $columns as $column ) {
			$title = $column->name;
			$slug = $column->slug;
			$id = $column->term_id;
			$link = '';
			$date = get_field( 'date', $column );
			$writer = get_field( 'writer', $column );
			$active = get_field( 'active', $column );
			$posts_args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'orderby' => 'date',
			  'order' => 'asc',
			  'tax_query' => array(
			  	array(
						'taxonomy' => 'columns',
						'field' => 'id',
						'terms' => $id
					)
			  )
			);
			$posts_query = new WP_Query( $posts_args );
			$post_count = $posts_query->post_count;
			$first_date = $posts_query->posts[0]->post_date;
			$last_date = $posts_query->posts[$post_count-1]->post_date;
			$begin = date( 'F, Y', strtotime( $first_date ) );
			if( $active ) {
				$end = 'Present';
			} else {
				$end = date( 'F, Y', strtotime( $last_date ) );
			}
			echo '<div class="sections column one_two" role="column">';
				echo '<section>';
					echo '<div class="text">';
						echo '<h1 class="title"><em>' . $title . '</em></h1>';
						echo '<h2 class="writer">by ' . $writer . '</h2>';
						echo '<h2 class="span">' . $begin . '&mdash;' . $end . '</h2>';
					echo '</div>';
				echo '</section>';

				echo '<section>';
					if ( $posts_query->have_posts() ) {
						echo '<div class="loop posts list">';
							while ( $posts_query->have_posts() ) {
								$posts_query->the_post();
								$thumb_id = get_post_thumbnail_id();
								$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
								$thumb_url = $thumb[0];
								$thumb_width = $thumb[1];
								$thumb_height = $thumb[2];
								$date = get_the_date();
								$permalink = get_permalink();
								echo '<article class="cell" role="article" style="' . $style . '" data-id="' . get_the_ID() . '">';
									echo '<h3>';
										echo '<em class="date">' . $date . '</em>';
										echo '<a class="title" href="' . $permalink . '">' . get_the_title() . '</a>';
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