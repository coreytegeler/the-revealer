<?php
$title = $post->post_title;
$article_id = $post->ID;
$thumb_id = get_post_thumbnail_id();
$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
$thumb_url = $thumb[0];
$thumb_width = $thumb[1];
$thumb_height = $thumb[2];
$permalink = get_the_permalink();
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$date = get_the_date();
$contributors = get_contributors_list();
$i = $wp_query->current_post;
$missing_url = get_template_directory_uri() . '/assets/images/question.svg';
$missing_svg = file_get_contents( $missing_url );
$column = get_the_terms( $post, 'columns' )[0];
$categories = get_the_category();
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
		echo '<div class="secondary">';
			echo '<a class="link_wrap" href="' . $permalink . '">';
				echo '<div class="title">';
					if( $column ) {
						echo '<div class="column label">' . $column->name . '</div> ';
					} else if( $categories ) {
						echo '<div class="categories label">';
							foreach( $categories as $i => $cat ) {
								$cat_name = get_field( 'singular', $cat );
								if( !sizeof( $cat_name ) ) {
									$cat_name = $cat->name;
								}
								echo '<span class="' . $cat->slug . '">' . $cat_name . '</span>';
								if( $i < sizeof( $categories ) - 1 ) {
									echo ', ';
								}
							}
						echo '</div> ';
					}
					echo '<h2>' . $title . '</h2>';
				echo '</div>';
			echo '</a>';
			echo '<div class="meta">';
				if( $contributors ) {
					echo '<span class="writer">' . $contributors . '</span>';
				}
				echo '<span class="date">' . $date . '</span>';
			echo '</div>';
			echo '<div class="blurb">';
				echo $excerpt;
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</article>';
?>