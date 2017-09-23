<?php
/*
Template Name: Page
*/
global $post;
get_header();
echo '<div class="readable">';
	echo '<div class="max">';
		echo $post->post_title;
	echo '</div>';
echo '</div>';
get_footer();
?>