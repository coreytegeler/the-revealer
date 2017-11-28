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
$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
$permalink = ( $type == 'post' ? get_permalink( $id ) : get_category_link( $id ) );

echo '<div class="cell discover ' . $type . ( $thumb[0] ? ' thumb' : '' ) . '" data-id="' . $id . '">';
	echo '<div class="wrap">';
		echo '<a href="' . $permalink . '">';
			if( $thumb[0] ) {
				echo '<div class="image bg load" data-src="'.$thumb[0].'"></div>';
			} else {
				echo '<div class="circle"></div>';
			}
		echo '</a>';
	echo '</div>';
echo '</div>';
?>