<?php
/*
Template Name: Page
*/
global $post;
$title = $post->post_title;
$content = get_the_content();
get_header();
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<h1>';
			echo $title;
		echo '</h1>';
	echo '</div>';
echo '</div>';
get_footer();
?>