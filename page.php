<?php
/*
Template Name: Page
*/
global $post;
$title = $post->post_title;
get_header();
echo '<article class="readable show">';
	echo '<div class="text">';
		echo '<div class="lead">';
			echo '<div class="header">';
				echo '<h1 class="title">';
					echo $title;
				echo '</h1>';
			echo '</div>';
		echo '</div>';
		echo '<div class="content">';
			the_content();
		echo '</div>';
	echo '</div>';
echo '</article>';
get_footer();
?>