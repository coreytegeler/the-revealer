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
echo '<div class="stats">';
	echo '<h1>Explore ' . $age . ' years and ' . $article_count . ' articles of</h1>';
	echo '<h1 class="title">The Revealer</h1>';
echo '</div>';
?>