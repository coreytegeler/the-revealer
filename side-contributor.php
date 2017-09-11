<?php
global $post;
$title = $post->post_title;
$slug = $post->post_name;
$id = $post->ID;
$content = $post->post_content;
echo '<header role="contentinfo">';
	echo '<div class="info">';
		echo '<div class="title">';
			echo '<h1>' . $title . '</h1>';
		echo '</div>';
		echo '<div class="excerpt">';
			echo $content;
		echo '</div>';
	echo '</div>';
echo '</header>';
?>