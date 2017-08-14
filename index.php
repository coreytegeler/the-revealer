<?php
get_header();
$posts_args = array(
	'posts_per_page' => 10
);
query_posts( $posts_args );
get_template_part( 'snippets/loop' );
get_footer();
?>