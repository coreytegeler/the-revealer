<?php
$start_date = new DateTime( '2003-09-30' );
$current_date = new DateTime();
$date_diff = date_diff( $start_date, $current_date );
$age = $date_diff->y;

$article_count =  number_format( wp_count_posts()->publish, '0', '.', ',' );

echo '<div class="stats">';
	if( is_404() ) {
		echo '<h2><div class="animation glisten bounce">' . wrap_words( 'Oops, this page is lost.' ) . '</div></h2>';
	}
	echo '<h2><div class="animation glisten bounce">' . wrap_words( 'Explore ' . $age . ' years and ' . $article_count . ' articles of' ) . '</div></h2>';
	echo '<h2><div class="title animation glisten bounce">' . wrap_words( 'The Revealer' ) . '</div></h2>';
echo '</div>';
?>