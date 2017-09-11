<?php
/*
Template Name: About
*/
global $post;
get_header();
$content = apply_filters( 'the_content', $post->post_content );
// echo '<div class="about">';
	// echo '<div class="inner">';
		// echo '<span class="title">The Revealer</span>';
		// echo '<span class="body">' . $content . '</span>';
	// echo '</div>';
// echo '</div>';
$people = get_field( 'people' );
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<ul class="nested parent people">';
			foreach( $people as $role ) {
				$title = $role['title'];
				$these_people = $role['person'];
				echo '<li>';
					echo '<h2>' . $title . '</h2>';
					echo '<ul class="child people">';
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
	echo '</div>';
get_footer();
?>