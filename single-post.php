<?php
global $post;
get_header();
echo '<article class="post readable">';
	$content = $post->post_content;
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$stripped_content = strip_tags( $content );
	echo '<div class="max">';
		echo '<div class="text">';
			echo '<div class="content">';
				echo $content;
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</article>';
get_footer();
?>