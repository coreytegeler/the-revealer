<?php
/*
Template Name: About
*/
global $post;
get_header();
$title = $post->post_title;
$content = apply_filters( 'the_content', $post->post_content );
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<div class="body">';
			echo '<h1 class="page-title">' . $title . '</h1>';
			echo $content;
		echo '</div>';
	echo '</div>';
get_footer();
?>