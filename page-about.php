<?php
/*
Template Name: About
*/
global $post;
get_header();

$content = $post->post_content;
$people = get_field( 'people' );

echo '<div class="max">';
	echo '<ul class="nested top about">';
		echo '<li>';
			echo '<h2>' . $post->post_title . '</h2>';
			echo '<div class="sub">';
				echo '<span class="title"><em>The Revealer</em></span>';
				echo '<span class="body"> ' . $content . '</span>';
			echo '</div>';
		echo '</li>';
	echo '</ul>';
	echo '<ul class="nested top people">';
		foreach( $people as $role ) {
			$title = $role['title'];
			$these_people = $role['person'];
			echo '<li>';
				echo '<h2>' . $title . '</h2>';
				echo '<ul class="sub people">';
					foreach( $these_people as $person ) {
						echo '<li>';
							if( $name = $person['name'] ) {
								echo '<span class="title"><em>' . $name . '</em></span>';
								if( $years = $person['years'] ) {
									echo '<span class="years">' . $years . '</span>';
								}
								if( $bio = $person['bio'] ) {
									echo '<span class="body">' . $bio . '</span>';
								}
							}
							if( $links = $person['links'] ) {
								echo '<div class="links">';
									foreach( $links as $link ) {
										echo '<a href="' . $link['url'] . '" target="_blank">' . $link['title'] . '</a>';
									}
								echo '</div>';
							}
						echo '</li>';
					}
				echo '</ul>';
			echo '</li>';
		}
	echo '</ul>';
get_footer();
?>