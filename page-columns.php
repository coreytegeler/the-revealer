<?php
/*
Template Name: Issues
*/
get_header();
echo '<div class="readable">';
	echo '<div class="columns rows">';
		$paged = get_query_var( 'paged' );
		if( !$paged ) {
			$paged = 1;
		}

		$columns = get_terms( array(
		  'taxonomy' => 'columns',
		  'hide_empty' => false,
		  'orderby' => 'date',
		  'order' => 'desc',
		  'paged' => $paged
		) );
		foreach( $columns as $column ) {
			$title = $column->name;
			$slug = $column->slug;
			$id = $column->term_id;
			$link = '';
			$date = get_field( 'date', $column );
			$writer = get_field( 'writer', $column );

			echo '<div class="sections column one_two" role="column">';
				echo '<section>';
					echo '<div class="title">';
						echo '<h1 class="title"><em>' . $title . '</em></h1>';
						echo '<h2 class="writer">Written by ' . $writer . '</h2>';
					echo '</div>';
				echo '</section>';

				echo '<section>';
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
					if ( $posts_query->have_posts() ) {
						echo '<div class="loop posts xsmall masonry">';
							while ( $posts_query->have_posts() ) {
								$posts_query->the_post();
								$article_id = get_the_ID();
								$thumb_id = get_post_thumbnail_id();
								$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
								$thumb_url = $thumb[0];
								$thumb_width = $thumb[1];
								$thumb_height = $thumb[2];
								$permalink = get_permalink();
								echo '<article class="cell" role="article" style="' . $style . '" data-id="' . $article_id . '">';
									echo '<div class="wrap">';
										echo '<div class="primary">';
											echo '<a class="link_wrap" href="' . $permalink . '">';
												echo '<div class="' . ( $thumb ? 'image load' : 'missing') . '">';
													if ( $thumb ) {
														echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
													}
												echo '</div>';
											echo '</a>';
										echo '</div>';
									echo '</div>';
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