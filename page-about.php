<?php
/*
Template Name: About
*/
global $post;
get_header();
$content = apply_filters( 'the_content', $post->post_content );

$people = get_field( 'people' );
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<ul class="nested parent about">';
			echo '<li><ul class="child">';
				echo '<li>';
					echo '<h1>About</h1>';
					echo '<span class="body">' . $content . '</span>';
				echo '</li>';
			echo '</ul></li>';
		echo '</ul>';
		echo '<ul class="nested parent people masonry">';
			foreach( $people as $role ) {
				$title = $role['title'];
				$these_people = $role['person'];
				echo '<li class="role cell">';
					echo '<h1>' . $title . '</h1>';
					echo '<ul class="child people">';
						foreach( $these_people as $person ) {
							echo '<li>';
								if( $name = $person['name'] ) {
									echo '<strong class="title"><em>' . $name . '</em></strong>';
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