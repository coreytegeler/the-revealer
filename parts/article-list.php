<?php
$title = $post->post_title;
$article_id = $post->ID;
$permalink = get_the_permalink();
$date = get_the_date();
$contributors = get_contributors_list( $article_id, true, true );
$i = $wp_query->current_post;
$column = get_the_terms( $post, 'columns' )[0];
$categories = get_the_category();
echo '<article class="cell" role="article" style="' . $style . '" data-id="' . $article_id . '">';
	echo '<div class="wrap">';
		echo '<div class="secondary">';
			echo '<a class="link_wrap" href="' . $permalink . '">';
				echo '<div class="title">';
					if( $categories ) {
						echo '<div class="categories label">';
							foreach( $categories as $i => $cat ) {
								if( $cat->slug == 'features' ) {
									echo '<span class="features">Featured </span>';
									array_splice( $categories, $i, 1 );
								}
							}
							foreach( $categories as $i => $cat ) {
								$cat_name = get_field( 'singular', $cat );
								if( sizeof( $cat_name ) < 1 ) {
									$cat_name = $cat->name;
								}
								echo '<span class="' . $cat->slug . '">' . $cat_name . '</span>';
								if( $i < sizeof( $categories ) - 1 ) {
									echo ', ';
								}
							}
						echo '</div> ';
					}
					echo '<h2>';
						if( $column ) {
							echo '<em class="column">' . $column->name . '</em>: ';
						}
						echo $title;
					echo '</h2>';
				echo '</div>';
			echo '</a>';
			echo '<div class="meta">';
				echo '<span class="date">' . $date . '</span>';
				if( $contributors ) {
					echo '<span class="writer">' . $contributors . '</span>';
				}
			echo '</div>';
			echo '<div class="blurb">';
				$limit = 300;
				$excerpt = wp_strip_all_tags( get_the_excerpt() );
			  $excerpt = explode(' ', $excerpt, $limit);
			  if (count( $excerpt ) >= $limit) {
			    array_pop( $excerpt );
			    $excerpt = implode( ' ', $excerpt ) . '...';
			  } else {
			    $excerpt = implode( ' ', $excerpt );
			  }	
			  $excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
				echo $excerpt;
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</article>';
?>