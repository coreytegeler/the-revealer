<?php
get_header();
echo '<div class="readable">';
	echo '<div class="loop categories medium masonry">';
		echo '<div class="max">';
			$categories = get_categories( array(
			    'orderby' => 'name',
			    'order'   => 'ASC'
			) );
			foreach( $categories as $category ) {
				$title = $category->name;
				$slug = $category->slug;
				$id = $category->cat_ID;
				$permalink = get_category_link( $id );
				$excerpt = strip_tags( get_the_content() );
				$description = $category->category_description;
				$images = get_option('taxonomy_image_plugin');
				$thumb = wp_get_attachment_image_src( $images[$id], 'large' );
				$thumb_url = $thumb[0];
				$thumb_width = $thumb[1];
				$thumb_height = $thumb[2];
				$count = $category->category_count;
				$missing_url = get_template_directory_uri() . '/assets/images/question.svg';
				$missing_svg = file_get_contents( $missing_url );
				echo '<div class="cell category ' . ( $thumb ? 'has_thumb' : 'no_thumb') . '" role="category">';
					echo '<div class="wrap">';
						echo '<a class="link_wrap" href="' . $permalink . '">';
							echo '<div class="' . ( $thumb ? 'image load' : 'missing') . '">';
								if ( $thumb ) {
									echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
								} else {
									echo $missing_svg;
								}
							echo '</div>';
							echo '<div class="title">';
								echo '<h2>' . $title . '</h2>';
							echo '</div>';
						echo '</a>';
						echo '<div class="excerpt"><em>' . $description . '</em></div>';
						echo '<div class="info">';
							// echo '<div class="meta">';
							// echo '<div class="meta">';
								// echo '<span>' . $count . ' Posts</span>';
							// echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		echo '</div>';
	echo '</div>';
echo '</div>';
get_footer();
?>