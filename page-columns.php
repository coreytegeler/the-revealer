<?php
/*
Template Name: Columns
*/
get_header();
echo '<div class="readable">';
	echo '<div class="columns-list">';

		$columns = get_terms( array(
		  'taxonomy' => 'columns',
		  'hide_empty' => false,
		  'number' => 0,
		  'hide_empty' => 0
		) );

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
		    'order' => 'desc',
		    'tax_query' => array(
		      array(
		        'taxonomy' => 'columns',
		        'field' => 'id',
		        'terms' => $id
		      )
		    )
		  );
		  $posts_query = new WP_Query( $posts_args );
			$col_span = get_col_span( $id, $posts_query );
			echo '<div class="sections column one_two" role="column">';
				echo '<section>';
					echo '<div class="text">';
						echo '<h2 class="writer">' . $writer . '\'s</h3>';
						echo '<h1 class="title"><em>' . $title . '</em></h1>';
						echo '<h3 class="span">' . $col_span . '</h3>';
					echo '</div>';
				echo '</section>';
				echo '<section class="articles toggler" data-toggle="' . $slug . '">';
					if ( $posts_query->have_posts() ) {
						echo '<div class="loop posts list intra">';
							while ( $posts_query->have_posts() ) {
								$posts_query->the_post();
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
					echo '<div class="toggle" data-toggle="' . $slug . '">';
						echo '<div class="circle">';
							$down_svg = get_template_directory_uri() . '/assets/images/down.svg';
							echo file_get_contents( $down_svg );
						echo '</div>';
					echo '</div>';
				echo '</section>';
				wp_reset_query();
			echo '</div>';
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>