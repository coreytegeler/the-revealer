<?php
global $post;
get_header();
echo '<article class="post readable">';
	$content = $post->post_content;
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$stripped_content = strip_tags( $content );

	$excerpt = wp_strip_all_tags( get_the_excerpt() );

	$title = $post->post_title;
	$column = get_the_terms( $post, 'columns' )[0];
	echo '<div class="max">';
		echo '<div class="text">';
			echo '<div class="excerpt">';
				echo '<h2>' . $excerpt . '</h2>';
			echo '</div>';
			echo '<div class="content">';
				echo $content;
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</article>';
echo '<div class="transport top"><div class="circle"><span>top</span></div></div>';
get_footer();
?>