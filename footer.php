<?php
echo '</main>';
echo '</div>';
echo '<div id="discover">';
	echo '<div class="inner">';
		echo '<div class="loop discovery xsmall grid">';
			global $post;
			$rand_post_args = array(
				'post_type' => 'post',
				'posts_per_page' => 15,
				'orderby' => 'rand'
			);
			$rand_posts = get_posts( $rand_post_args );

			$rand_cat_args = array(
		    'number' => 5,
		    'orderby' => 'rand'
			);
			$rand_cats = get_categories(  $rand_cat_args );

			$rand_cells = array_merge( $rand_posts, $rand_cats );
			shuffle( $rand_cells );

			foreach( $rand_cells as $cell ) {
				$type = $cell->post_type;
				if( !$type ) {
					$type = $cell->taxonomy;
				}
				$title = ( $type == 'post' ? $cell->post_title : $cell->name );
				$id = ( $type == 'post' ? $cell->ID : $cell->cat_ID );
				$permalink = ( $type == 'post' ? get_permalink( $id ) : get_category_link( $id ) );
				echo '<div class="cell xsmall discovery">';
					echo '<div class="wrap">';
						echo '<a href="' . $permalink . '">';
							$thumb_id = get_post_thumbnail_id( $id );
							$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
							if( $thumb ) {
								echo '<div class="image load">';
									echo '<img data-src="'.$thumb[0].'" data-width="'.$thumb[1].'" data-height="'.$thumb[2].'"/>';
								echo '</div>';
							} else {
								echo $title;
							}
						echo '</a>';
					echo '</div>';
				echo '</div>';
				
			}

		echo '</div>';	
	echo '</div>';
echo '</div>';
echo '<footer>';
	echo '<div class="social">';
		$social = array( 'facebook', 'twitter', 'instagram' );
		foreach( $social as $name ) {
			$svg = get_template_directory_uri() . '/assets/images/' . $name . '.svg';
			echo '<a href="#" class="' . $name . '">';
				echo file_get_contents( $svg );
			echo '</a>';
		}
	echo '</div>';
	
	echo '<div class="note">The Revealer is published by the Center for Religion and Media at NYU</div>';
	echo '<div class="note">Copyright © 2017 The Revealer All Rights Reserved.</div>';
echo '</footer>';
wp_footer();
?>
</body>
</html>