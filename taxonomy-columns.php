<?php
get_header();
$column = $wp_query->get_queried_object();
$title = $column->name;
$slug = $column->slug;
$id = $column->term_id;
$link = '';
$date = get_field( 'date', $column );
$writer = get_field( 'writer', $column );
echo '<div class="readable">';
	$id = $column->term_id;
	$posts_args = array(
		'post_type' => 'post',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'asc',
		'tax_query' => array(
			array(
				'taxonomy' => 'columns',
				'field' => 'id',
				'terms' => $id
			)
		)
	);
	$posts_query = new WP_Query( $posts_args );
	$col_span = get_col_span( $id, $posts_query );
	echo '<section class="columns-list">';
		echo '<div class="text column">';
			echo $writer ? '<h2 class="writer">' . $writer . '\'s</h3>' : '';
			echo $title ? '<h1 class="title"><em>' . $title . '</em></h1>' : '';
			echo $col_span ? '<h3 class="span">' . $col_span . '</h3>' : '';
		echo '</div>';
	echo '</section>';

	echo '<br><br>';

	echo '<div class="loop articles row column">';
		set_query_var( 'col_size', 'col-12 col-md-6 col-lg-4' );
		set_query_var( 'no_link', true );
		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
				set_query_var( 'article', $post );
				get_template_part( 'parts/article' );
			}
		}
		wp_reset_query();
	echo '</div>';
echo '</div>';
get_footer();
?>