<?php
$article_count =  number_format( wp_count_posts()->publish, '0', '.', ',' );
$query = new WP_Query( array(
	'post_type' => 'post',
	'orderby' => 'date',
	'order' => 'asc',
	'posts_per_page' => 1
) );
if( $first_post = $query->posts[0] ) {
	$first_date = new DateTime( $first_post->post_date );
	$current_date = new DateTime();
	$date_diff = date_diff( $first_date, $current_date );
	$age = $date_diff->y;
}
// echo '<div class="stats">';
// 	if( is_404() ) {
// 		echo '<h1 class="animation glisten bounce">Oops, this page is lost.</h1>';
// 	}
// 	echo '<h1 class="animation glisten bounce">Explore ' . $age . ' years and ' . $article_count . ' articles of</h1>';
// 	echo '<h1 class="title animation glisten bounce">The Revealer</h1>';
// echo '</div>';



echo '<div class="stats">';
	if( is_404() ) {
		echo '<h1><div class="animation glisten bounce">' . wrap_words( 'Oops, this page is lost.' ) . '</div></h1>';
	}
	echo '<h1><div class="animation glisten bounce">' . wrap_words( 'Explore ' . $age . ' years and ' . $article_count . ' articles of' ) . '</div></h1>';
	echo '<h1><div class="title animation glisten bounce">' . wrap_words( 'The Revealer' ) . '</div></h1>';
echo '</div>';
?>