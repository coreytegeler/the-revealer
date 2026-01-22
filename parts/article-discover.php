<?php
global $post;
$cell = $post;
$type = $cell->post_type;
if( !$type ) {
	$type = $cell->taxonomy;
}
if( $type == 'post' ) {
	$title = $cell->post_title;
} else if( $type == 'category' ) {
	$title = $cell->name;
}
$id = ( $type == 'post' ? $cell->ID : $cell->cat_ID );
$thumb_id = get_post_thumbnail_id( $id );
$thumb = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
$thumb_url = is_array( $thumb ) ? $thumb[0] : false;
$permalink = ( $type == 'post' ? get_permalink( $id ) : get_category_link( $id ) );

echo '<div class="cell discover ' . $type . ( $thumb_url ? ' thumb' : ' show' ) . '" data-id="' . $id . '">';
	echo '<div class="wrap">';
		echo '<a href="' . $permalink . '">';
			echo '<div class="image bg load" ' . ( $thumb_url ? 'style="background-image: url(' . $thumb_url . ')"' : '' ) . '></div>';
		echo '</a>';
	echo '</div>';
echo '</div>';
?>