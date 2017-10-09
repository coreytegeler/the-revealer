<?php
get_header();

print_r( $home );

$issues = get_terms( array(
  'taxonomy' => 'issues',
  'hide_empty' => false,
  'orderby' => 'date',
  'order' => 'asc'
) );
$current_issue = $issues[0];
$current_issue_id = $current_issue->term_id;

$issue_args = array(
	'post_type' => 'post',
	'orderby' => 'date',
  'order' => 'asc',
  'tax_query' => array(
  	array(
			'taxonomy' => 'issues',
			'field' => 'id',
			'terms' => $current_issue_id
		)
  )
);

$already_used = array();

$features_amount = 1;
$medium_amount = 15;
$tagged_amount = 4;

$articles_page = get_page_by_path( 'articles' );
if( $articles_page ) {
	$page_url = get_permalink( $articles_page );
} else {
	$page_url = get_site_url() . '/articles/';
}

// print_r( $current_issue );
echo '<div class="readable">';
	echo '<div class="split_box one_one">';
		echo '<section id="cover">';
			echo '<h1 class="title">Read our current issue&mdash;<strong>' . $current_issue->name  . '</strong>.</h1>';
			$issue_date = get_field( 'date', $current_issue );
			echo '<h2 class="date">Published on ' . $issue_date . '</h2>';
			echo '<div class="circle"></div>';
			echo '<div class="newsletter">';
			echo '<h2>' . get_field( 'newsletter_title', 'option' ) . '</h2>';
				echo '<form>';
					echo '<input type="text" placeholder="Enter your email"/>';
					echo '<input type="submit" value="Subscribe"/>';
				echo '</form>';
			echo '</div>';
		echo '</section>';
		echo '<section>';
			$features_args = array_merge( $issue_args, array(
				'posts_per_page' => $features_amount,
				'category_name' => 'features'
			) );
			$features_query = new WP_Query( $features_args );

			if ( $features_query->have_posts() ) {
				echo '<div class="loop articles large grid">';
					while ( $features_query->have_posts() ) {
						$features_query->the_post();
						get_template_part( 'parts/article' );
						$already_used[] = get_the_ID();
					}
					wp_reset_query();
				echo '</div>';
			}
		echo '</section>';	
	echo '</div>';

	$medium_args = array_merge( $issue_args, array(
		'posts_per_page' => $medium_amount,
		'post__not_in' => $already_used
	) );
	$medium_query = new WP_Query( $medium_args );
	if ( $medium_query->have_posts() ) {
		echo '<div class="loop articles medium grid">';
			while ( $medium_query->have_posts() ) {
				$medium_query->the_post();
				get_template_part( 'parts/article' );
				$already_used[] = get_the_ID();
			}
			wp_reset_query();
		echo '</div>';
	}

	echo '<div class="split_box one_one">';

		echo '<section>';
			$col_args = array(
				'post_type' => 'post',
				'orderby' => 'date',
			  'order' => 'asc',
				'posts_per_page' => 1,
				'post__not_in' => $already_used,
				'tax_query' => array(
					array(
						'taxonomy' => 'columns',
						'operator' => 'EXISTS'
					)
				)
			);
			$col_query = new WP_Query( $col_args );
			if ( $col_query->have_posts() ) {
				$featured_col = get_the_terms( $col_query->posts[0], 'columns' )[0];
				$featured_col_name = $featured_col->name;
				$featured_col_slug = $featured_col->slug;
				$featured_col_writer = get_field( 'writer', $featured_col );
				$featured_col_url = add_query_arg( 'column', $featured_col_slug, $page_url );
				echo '<h2 class="title">Another recent article from, ';
					echo '<a href="' . $col_url . '">';
							echo '<em>' . $featured_col_name . '</em>';
						echo '</a>';
				echo ', a column by <em>' . $featured_col_writer  . '</em>.</h2>';
				echo '<div class="loop articles large grid" id="recent_column">';
					while ( $col_query->have_posts() ) {
						$col_query->the_post();
						get_template_part( 'parts/article' );
						$already_used[] = get_the_ID();
					}
					wp_reset_query();
				echo '</div>';
			}
			echo '<h2 class="title">More columns from The Revealer</h2>';
			$columns = get_terms( array(
				'taxonomy' => 'columns',
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );
			if( sizeof( $columns ) ) {
				echo '<div class="columns">';
					foreach( $columns as $col) {
						$col_title = $col->name;
						$col_slug = $col->slug;
						$col_id = $col->cat_ID;
						$col_writer = get_field( 'writer', $col );
						$col_url = add_query_arg( 'column', $col_slug, $page_url );
						echo '<div class="column">';
							echo '<a href="' . $col_url . '" class="column">';
								echo '<h1>' . $col_title . '<span>&mdash;' . $col_writer . '</span></h1>';
							echo '</a>';
						echo '</div>';
					}
				echo '</div>';
			}
		echo '</section>';

		echo '<section>';
			$featured_tag = get_field( 'featured_tag', $current_issue );
			echo '<h2 class="title">Some more articles about ';
				echo '<a href="' . $featured_tag->slug . '">';
					echo '<em>' . $featured_tag->name . '</em>.';
				echo '</a>';
			echo '</h2>';
			echo '<div class="loop articles grid" id="featured_tag">';
				$tagged_args = array(
					'posts_per_page' => $tagged_amount,
					'post_type' => 'post',
					'post__not_in' => $already_used,
				  'tag' => $featured_tag->slug
				);
				$tagged_query = new WP_Query( $tagged_args );
				if ( $tagged_query->have_posts() ) {
					while ( $tagged_query->have_posts() ) {
						$tagged_query->the_post();
						get_template_part( 'parts/article' );
						$already_used[] = get_the_ID();
					}
					wp_reset_query();
				}
			echo '</div>';
		echo '</section>';

	echo '</div>';

	get_template_part( 'parts/pagination' );

echo '</div>';
get_footer();
?>