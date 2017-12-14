<?php

$post_width = $settings->display_type === 'list' ? 100 : ( ! empty( $settings->columns ) ? 100 / (int) $settings->columns : 33.3333 );
$card_vertical_align = ! empty( $settings->card_vertical_align ) ? $settings->card_vertical_align : 'top';
echo '.fl-node-' . $id . ' .obfx-post-grid-wrapper{
	width: ' . $post_width . '%;
	vertical-align: ' . $card_vertical_align . ';
}';

$post_bg_color = ! empty( $settings->post_bg_color ) ? $settings->post_bg_color : '';
$post_link_color = ! empty( $settings->post_link_color ) ? $settings->post_link_color : '';
$post_text_color = ! empty( $settings->post_text_color ) ? $settings->post_text_color : '';
if ( ! empty( $post_bg_color ) ) {
	$before = strpos( $post_bg_color, 'rgba' ) !== false ? '' : '#';
	echo '.fl-node-' . $id . ' .obfx-post-grid{
	background-color: ' . $before . $post_bg_color . ';
	}';
}
if ( ! empty( $post_link_color ) ) {
	$before = strpos( $post_link_color, 'rgba' ) !== false ? '' : '#';
	echo '.fl-node-' . $id . ' .obfx-post-grid a, .fl-node-' . $id . ' .obfx-post-grid-pagination a {
	color: ' . $before . $post_link_color . ';
	}';
}
if ( ! empty( $post_text_color ) ) {
	$before = strpos( $post_text_color, 'rgba' ) !== false ? '' : '#';
	echo '.fl-node-' . $id . ' .obfx-post-grid, .fl-node-' . $id . ' .obfx-post-grid-pagination{
	color: ' . $before . $post_text_color . ';
	}';
}


$card_margin_top = ! empty( $settings->card_margin_top ) ? $settings->card_margin_top : '0';
$card_margin_bottom = ! empty( $settings->card_margin_bottom ) ? $settings->card_margin_bottom : '0';
echo '.fl-node-' . $id . ' .obfx-post-grid{
	margin-top: ' . $card_margin_top . 'px;
	margin-bottom: ' . $card_margin_bottom . 'px;
}
';

$thumbnail_margin_top = ! empty( $settings->thumbnail_margin_top ) ? $settings->thumbnail_margin_top : '0';
$thumbnail_margin_bottom = ! empty( $settings->thumbnail_margin_bottom ) ? $settings->thumbnail_margin_bottom : '0';
$thumbnail_margin_left = ! empty( $settings->thumbnail_margin_left ) ? $settings->thumbnail_margin_left : '0';
$thumbnail_margin_right = ! empty( $settings->thumbnail_margin_right ) ? $settings->thumbnail_margin_right : '0';
$image_alignment = ! empty( $settings->image_alignment ) ? $settings->image_alignment : 'center';

echo '.fl-node-' . $id . ' .obfx-post-grid-thumbnail{
	margin-top: ' . $thumbnail_margin_top . 'px;
	margin-bottom: ' . $thumbnail_margin_bottom . 'px;
	margin-left: ' . $thumbnail_margin_left . 'px;
	margin-right: ' . $thumbnail_margin_right . 'px;';

switch ( $image_alignment ) {
	case 'center':
		echo 'text-align:center; width:100%;';
		break;
	case 'left':
		echo 'float:left';
		break;
	case 'right':
		echo 'float:right';
		break;
}
echo '}';


$title_padding_top = ! empty( $settings->title_padding_top ) ? $settings->title_padding_top : '0';
$title_padding_bottom = ! empty( $settings->title_padding_bottom ) ? $settings->title_padding_bottom : '0';
$title_padding_left = ! empty( $settings->title_padding_left ) ? $settings->title_padding_left : '0';
$title_padding_right = ! empty( $settings->title_padding_right ) ? $settings->title_padding_right : '0';
$title_alignment = ! empty( $settings->title_alignment ) ? $settings->title_alignment : 'center';

echo '.fl-node-' . $id . ' .obfx-post-grid-title{
	padding-top: ' . $title_padding_top . 'px;
	padding-bottom: ' . $title_padding_bottom . 'px;
	padding-left: ' . $title_padding_left . 'px;
	padding-right: ' . $title_padding_right . 'px;
	text-align: ' . $title_alignment . ';
	font-size:' . $settings->title_font_size . 'px;
	font-family:' . $settings->title_font_family['family'] . ';
	font-weight:' . $settings->title_font_family['weight'] . ';
	text-transform:' . $settings->title_transform . ';
	font-style:' . $settings->title_font_style . ';
	line-height:' . $settings->title_line_height . 'px;
	letter-spacing:' . $settings->title_letter_spacing . 'px;
} ';

$meta_padding_top = ! empty( $settings->meta_padding_top ) ? $settings->meta_padding_top : '0';
$meta_padding_bottom = ! empty( $settings->meta_padding_bottom ) ? $settings->meta_padding_bottom : '0';
$meta_padding_left = ! empty( $settings->meta_padding_left ) ? $settings->meta_padding_left : '0';
$meta_padding_right = ! empty( $settings->meta_padding_right ) ? $settings->meta_padding_right : '0';
$meta_alignment = ! empty( $settings->meta_alignment ) ? $settings->meta_alignment : 'center';
echo '.fl-node-' . $id . ' .obfx-post-grid-meta{
	padding-top: ' . $meta_padding_top . 'px;
	padding-bottom: ' . $meta_padding_bottom . 'px;
	padding-left: ' . $meta_padding_left . 'px;
	padding-right: ' . $meta_padding_right . 'px;
	font-size:' . $settings->meta_font_size . 'px;
	font-family:' . $settings->meta_font_family['family'] . ';
	font-weight:' . $settings->meta_font_family['weight'] . ';
	text-transform:' . $settings->meta_transform . ';
	font-style:' . $settings->meta_font_style . ';
	line-height:' . $settings->meta_line_height . 'px;
	letter-spacing:' . $settings->meta_letter_spacing . 'px;
	text-align: ' . $meta_alignment . ';
} ';


$content_padding_top = ! empty( $settings->content_padding_top ) ? $settings->content_padding_top : '0';
$content_padding_bottom = ! empty( $settings->content_padding_bottom ) ? $settings->content_padding_bottom : '0';
$content_padding_left = ! empty( $settings->content_padding_left ) ? $settings->content_padding_left : '0';
$content_padding_right = ! empty( $settings->content_padding_right ) ? $settings->content_padding_right : '0';
$content_alignment = ! empty( $settings->content_alignment ) ? $settings->content_alignment : 'center';
echo '.fl-node-' . $id . ' .obfx-post-content{
	padding-top: ' . $content_padding_top . 'px;
	padding-bottom: ' . $content_padding_bottom . 'px;
	padding-left: ' . $content_padding_left . 'px;
	padding-right: ' . $content_padding_right . 'px;
	text-align: ' . $content_alignment . ';
	font-size:' . $settings->content_font_size . 'px;
	font-family:' . $settings->content_font_family['family'] . ';
	font-weight:' . $settings->content_font_family['weight'] . ';
	text-transform:' . $settings->content_transform . ';
	font-style:' . $settings->content_font_style . ';
	line-height:' . $settings->content_line_height . 'px;
	letter-spacing:' . $settings->content_letter_spacing . 'px;
} ';

$pagination_alignment = ! empty( $settings->pagination_alignment ) ? $settings->pagination_alignment : 'center';
echo '.fl-node-' . $id . ' .obfx-post-grid-pagination li a, .fl-node-' . $id . ' .obfx-post-grid-pagination li {
	font-size:' . $settings->pagination_font_size . 'px;
	font-family:' . $settings->pagination_font_family['family'] . ';
	font-weight:' . $settings->pagination_font_family['weight'] . ';
	text-transform:' . $settings->pagination_transform . ';
	font-style:' . $settings->pagination_font_style . ';
	line-height:' . $settings->pagination_line_height . 'px;
	letter-spacing:' . $settings->pagination_letter_spacing . 'px;
}';

echo '.fl-node-' . $id . ' .obfx-post-grid-pagination{
	text-align: ' . $pagination_alignment . ';
}';
