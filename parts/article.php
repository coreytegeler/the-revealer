<?php
$post = $article;
$title = $post->post_title;
$post_id = $post->ID;
$thumb_id = get_post_thumbnail_id();
$thumb = wp_get_attachment_image_src( $thumb_id, 'article-thumb' );
$thumb_url = $thumb[0];
$thumb_width = $thumb[1];
$thumb_height = $thumb[2];
$thumb_ratio = $thumb_width && $thumb_height ? $thumb_width/$thumb_height : 1;



$permalink = get_the_permalink();
$date = get_the_date();
$contributors = get_contributors_list( $post_id, true, true );
$i = $wp_query->current_post;
$missing_url = get_template_directory_uri() . '/assets/images/missing.svg';
$missing_svg = file_get_contents( $missing_url );
$column = get_the_terms( $post, 'columns' )[0];
$categories = get_cat_list( $post_id );
$category_class = '';
foreach( get_the_category( $id ) as $i => $cat ) {
	$category_class .= $cat->slug.' ';
}
$img_class = $thumb_ratio > 1.334 ? 'narrow' : null;

echo '<article class="col ' . ( $col_size ? $col_size : 'col-12') . ' ' . ( $thumb ? 'has_image' : 'no_image') . ' ' . $category_class . '" role="article" data-id="' . $post_id . '">';
	echo '<div class="wrap">';
		echo '<div class="primary">';
			if( $categories ) {
				echo '<div class="categories label">';
					echo $categories;
				echo '</div> ';
			}
			echo '<a class="link_wrap" href="' . $permalink . '">';
				if ( $thumb ) {
					echo '<div class="image load">';
						echo '<img data-src="'.$thumb_url.'" data-no-width="'.$thumb_width.'" data-no-height="'.$thumb_height.'" class="'.$img_class.'"/>';
						echo '<noscript><img data-src="'.$thumb_url.'"/></noscript>';
					echo '</div>';
				} else {
					echo '<div class="image missing">' . $missing_svg . '</div>';
				}
			echo '</a>';
		echo '</div>';
		echo '<div class="secondary">';
			echo '<a class="link_wrap" href="' . $permalink . '">';
				echo '<div class="title">';
					echo '<h2>';
						if( $column ) {
							echo '<span class="column">' . $column->name . '</span>: ';
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
				excerpt( $post, 20 );
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</article>';
?>