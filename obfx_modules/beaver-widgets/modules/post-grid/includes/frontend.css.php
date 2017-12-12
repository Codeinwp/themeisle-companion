<?php

$post_width = $settings->display_type === 'list' ? 100 : ( !empty( $settings->columns ) ? 100 / (int) $settings->columns : 33.3333 );

echo '.fl-node-'.$id.' .obfx-post-grid-wrapper{
	width: '.$post_width.'%;
}';

$thumbnail_margin_top = !empty($settings->thumbnail_margin_top) ? $settings->thumbnail_margin_top : '0';
$thumbnail_margin_bottom = !empty($settings->thumbnail_margin_bottom) ? $settings->thumbnail_margin_bottom : '0';
$thumbnail_margin_left = !empty($settings->thumbnail_margin_left) ? $settings->thumbnail_margin_left : '0';
$thumbnail_margin_right = !empty($settings->thumbnail_margin_right) ? $settings->thumbnail_margin_right : '0';
$image_alignment = !empty($settings->image_alignment) ? $settings->image_alignment : 'center';

echo '.fl-node-'.$id.' .obfx-post-grid-thumbnail{
	margin-top: '.$thumbnail_margin_top.'px;
	margin-bottom: '.$thumbnail_margin_bottom.'px;
	margin-left: '.$thumbnail_margin_left.'px;
	margin-right: '.$thumbnail_margin_right.'px;';

	switch ($image_alignment){
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


$title_margin_top = !empty($settings->title_margin_top) ? $settings->title_margin_top : '0';
$title_margin_bottom = !empty($settings->title_margin_bottom) ? $settings->title_margin_bottom : '0';
$title_margin_left = !empty($settings->title_margin_left) ? $settings->title_margin_left : '0';
$title_margin_right = !empty($settings->title_margin_right) ? $settings->title_margin_right : '0';
$title_alignment = !empty($settings->title_alignment) ? $settings->title_alignment : 'center';

echo '.fl-node-'.$id.' .obfx-post-grid-title{
	margin-top: '.$title_margin_top.'px;
	margin-bottom: '.$title_margin_bottom.'px;
	margin-left: '.$title_margin_left.'px;
	margin-right: '.$image_alignment.'px;
	text-align: '.$title_alignment.';
} ';
