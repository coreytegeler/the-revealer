<?php
global $post;
$title = $post->post_title;
$thumb_id = get_post_thumbnail_id();
$thumb = wp_get_attachment_image_src( $thumb_id, 'small' );
$thumb_url = $thumb[0];
$thumb_width = $thumb[1];
$thumb_height = $thumb[2];
$link = get_field( 'link' );
$date = get_field( 'event_date' );
$i = $wp_query->current_post;
echo '<article class="cell event">';
	echo '<div class="wrap">';
		echo '<div class="primary">';
			echo '<a class="link_wrap" target="_blank" href="' . $link . '">';
				echo '<div class="' . ( $thumb ? 'image load' : 'missing') . '">';
					if ( $thumb ) {
						echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
					}
				echo '</div>';
			echo '</div>';
			echo '<div class="secondary">';
				echo '<div class="title">';
					echo '<h2>' . $title . '</h2>';
				echo '</div>';
				if( $date ) {
					echo '<div class="meta"><span class="date">' . $date . '</span></div>';
				}
			echo '</div>';
		echo '</a>';
	echo '</div>';
echo '</article>';
?>