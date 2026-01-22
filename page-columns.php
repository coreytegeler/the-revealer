<?php
/*
Template Name: Columns
*/
get_header();
echo '<div class="readable">';
	echo '<h1 class="page-title">' . $post->post_title . '</h1>';
	echo '<br /><br />';
	echo '<div class="columns-list">';
		echo '<div class="sections column one_one" role="column">';

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
				$column_url = get_term_link( $id, 'columns' );
				$link = '';
				$date = get_field( 'date', $column );
				$writer = get_field( 'writer', $column );
				$posts_args = array(
			    'post_type' => 'post',
			    'posts_per_page' => 3,
			    'orderby' => 'tax_position',
			    'order' => 'desc',
			    'tax_query' => array(
			      array(
			        'taxonomy' => 'columns',
			        'field' => 'id',
			        'terms' => $id
			      )
			    )
			  );
			  // $posts = get_posts( $posts_args );
				// $col_span = get_col_span( $id, $posts_query );
				
				echo '<section>';
					echo '<div class="text">';
						echo '<a href="' . $column_url . '">';
							echo $writer ? '<h2 class="writer">' . $writer . '\'s</h3>' : '';
							echo $title ? '<h1 class="title"><em>' . $title . '</em></h1>' : '';
							// echo $col_span ? '<h3 class="span">' . $col_span . '</h3>' : '';
							echo '<em>Read the articles</em>';
						echo '</a>';
					echo '</div>';
					echo '<br /><br />';
				echo '</section>';
				// 	echo '<section class="articles toggler" data-toggle="' . $slug . '">';
				// 		// if ( sizeof( $posts ) ) {
				// 			// echo '<div class="loop posts list intra">';
				// 			// 	foreach ( $posts as $post ) {
				// 			// 		$date = $post->post_date;
				// 			// 		$permalink = get_permalink( $post );
				// 			// 		echo '<article class="column" role="article" data-id="' .$post->ID . '">';
				// 			// 			echo '<h3>';
				// 			// 				echo '<em class="date">' . $date . '</em>';
				// 			// 				echo '<a class="title" href="' . $permalink . '">' . $post->post_title . '</a>';
				// 			// 			echo '</h3>';
				// 			// 		echo '</article>';
				// 			// 	}
				// 			// echo '</div>';
				// 		// }
				// 		echo '<div class="toggle" data-toggle="' . $slug . '">';
				// 			echo '<div class="circle">';
				// 				$down_svg = get_template_directory_uri() . '/assets/images/down.svg';
				// 				echo file_get_contents( $down_svg );
				// 			echo '</div>';
				// 		echo '</div>';
				// 	echo '</section>';				
			}
			// wp_reset_query();
		echo '</div>';
	echo '</div>';
echo '</div>';
get_footer();
?>