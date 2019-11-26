<?php
global $issue;
$title = $issue->name;
$slug = $issue->slug;
$id = $issue->term_id;
$issue_url = get_term_link( $id, 'issues' );
$date = get_field( 'date', $issue );
$thumb = get_field( 'featured_image', $issue );
echo '<div class="col ' . ( $col_size ? $col_size : 'col-12') . ' issue" role="issue">';
	echo '<a class="link_wrap" href="' . $issue_url . '">';
		echo '<div class="text">';
			echo '<h1 class="title">' . $title . '</h1>';
			echo '<h3 class="date">published ' . $date . '</h3>';
		echo '</div>';
		if( $thumb ) {
			$thumb_id = $thumb['ID'];
			$thumb = wp_get_attachment_image_src( $thumb_id, 'large' );
			$thumb_url = $thumb[0];
			$thumb_width = $thumb[1];
			$thumb_height = $thumb[2];
			echo '<div class="image load">';
				echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
			echo '</div>';
		}
	echo '</a>';
	// $mag_svg = get_template_directory_uri() . '/assets/images/mag.svg';
	// echo file_get_contents( $mag_svg );
echo '</div>';
?>