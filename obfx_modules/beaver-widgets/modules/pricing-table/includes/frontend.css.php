.fl-node-<?php echo $id; ?> .obfx-pricing-header{
	padding-top: <?php echo $settings->top; ?>px;
	padding-bottom: <?php echo $settings->bottom; ?>px;
	padding-left: <?php echo $settings->left; ?>px;
	padding-right: <?php echo $settings->right; ?>px;
	<?php
	$type = $settings->bg_type;
	switch ($type){
		case 'color':
			$bg_color = !empty( $settings->header_bg_color ) ? $settings->header_bg_color : '';
			if( !empty( $bg_color ) ){
				echo 'background-color:#'.$bg_color.';';
			}
			break;
		case 'image':
			$bg_image = !empty( $settings->header_bg_image ) ? $settings->header_bg_image : '';
			if( !empty( $bg_image ) ){
				echo 'background-image:url('. wp_get_attachment_url( $bg_image ) .');';
			}
			break;
		case 'gradient':
			$gradient_color1 = !empty( $settings->gradient_color1 )? $settings->gradient_color1 : '';
			$gradient_color2 = !empty( $settings->gradient_color2 )? $settings->gradient_color2 : '';
			$gradient_orientation = !empty( $settings->gradient_orientation )? $settings->gradient_orientation : '';
			$pos1 = 'left';
			$pos2 = 'left';
			$pos3 = 'to right';
			switch($gradient_orientation){
				case 'vertical':
					$pos1 = 'top';
					$pos2 = 'to bottom';
					$type = 'linear-gradient';
					break;
				case 'diagonal_bottom':
					$pos1 = '-45deg';
					$pos2 = '135deg';
					$type = 'linear-gradient';
					break;
				case 'diagonal_top':
					$pos1 = '45deg';
					$pos2 = '45deg';
					$type = 'linear-gradient';
					break;
				case 'radial':
					$pos1 = 'center, ellipse cover';
					$pos2 = 'ellipse at center';
					$type = 'radial-gradient';
					break;
			}
			if( !empty( $gradient_color1 ) ){
				if( !empty( $gradient_color2 ) ){
					echo '
					background: -moz-'.esc_attr($type).'('.esc_attr($pos1).', #'. esc_attr( $gradient_color1 ) .' 0%, #'. esc_attr( $gradient_color2 ).' 100%); 
					background: -webkit-'.esc_attr($type).'('.esc_attr($pos1).', #'. esc_attr( $gradient_color1 ) .' 0%, #'. esc_attr( $gradient_color2 ).' 100%); 
					background: '.esc_attr($type).'('.esc_attr($pos2).', #'. esc_attr( $gradient_color1 ) .' 0%, #'. esc_attr( $gradient_color2 ).' 100%);';
				} else {
					echo 'background-color:#'.esc_attr($gradient_color1).';';
				}
			}
			break;
	}
	?>
}

.fl-node-<?php echo $id; ?> .obfx-pricing-header *:first-child{
	color: #<?php echo $settings->title_color; ?>;
	font-size: <?php echo $settings->title_font_size; ?>px;
	font-family: <?php echo $settings->title_font_family['family']; ?>;
	font-weight: <?php echo $settings->title_font_family['weight']; ?>;
	text-transform: <?php echo $settings->title_transform; ?>;
	font-style: <?php echo $settings->title_font_style; ?>;
	line-height: <?php echo $settings->title_line_height; ?>px;
	letter-spacing: <?php echo $settings->title_letter_spacing; ?>px;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-header *:last-child{
	color: #<?php echo $settings->subtitle_color; ?>;
	font-size: <?php echo $settings->subtitle_font_size; ?>px;
	font-family: <?php echo $settings->subtitle_font_family['family']; ?>;
	font-weight: <?php echo $settings->subtitle_font_family['weight']; ?>;
	text-transform: <?php echo $settings->subtitle_transform; ?>;
	font-style: <?php echo $settings->subtitle_font_style; ?>;
	line-height: <?php echo $settings->subtitle_line_height; ?>px;
	letter-spacing: <?php echo $settings->subtitle_letter_spacing; ?>px;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-price{
    padding-top: <?php echo esc_attr( $settings->price_top ); ?>px;
    padding-bottom: <?php echo esc_attr( $settings->price_bottom ); ?>px;
    padding-left: <?php echo esc_attr( $settings->price_left ); ?>px;
    padding-right: <?php echo esc_attr( $settings->price_right ); ?>px;
    font-size: <?php echo esc_attr( $settings->price_font_size ); ?>px;
    font-family: <?php echo esc_attr( $settings->price_font_family['family'] ); ?>;
    font-weight: <?php echo esc_attr( $settings->price_font_family['weight'] ); ?>;
    text-transform: <?php echo esc_attr( $settings->price_transform ); ?>;
    font-style: <?php echo esc_attr( $settings->price_font_style ); ?>;
    line-height: <?php echo esc_attr( $settings->price_line_height ); ?>px;
    letter-spacing: <?php echo esc_attr( $settings->price_letter_spacing ); ?>px;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-price{
    color: #<?php echo esc_attr( $settings->price_color ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-price sup{
    color: #<?php echo esc_attr( $settings->currency_color ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-price .obfx-period{
    color: #<?php echo esc_attr( $settings->period_color ); ?>;
}

.obfx-pricing-features .obfx-pricing-feature-content{
    padding-top: <?php echo esc_attr( $settings->features_top ); ?>px;
    padding-bottom: <?php echo esc_attr( $settings->features_bottom ); ?>px;
    padding-left: <?php echo esc_attr( $settings->features_left ); ?>px;
    padding-right: <?php echo esc_attr( $settings->features_right ); ?>px;
}

.obfx-pricing-features .obfx-pricing-feature-content * {
    font-size: <?php echo esc_attr( $settings->feature_font_size ); ?>px;
    text-transform: <?php echo esc_attr( $settings->feature_transform ); ?>;
    font-style: <?php echo esc_attr( $settings->feature_font_style ); ?>;
    line-height: <?php echo esc_attr( $settings->feature_line_height ); ?>px;
    letter-spacing: <?php echo esc_attr( $settings->feature_letter_spacing ); ?>px;
}

.obfx-pricing-features .obfx-pricing-feature-content:not(i){
    font-family: <?php echo esc_attr( $settings->feature_font_family['family'] ); ?>;
}

.obfx-pricing-features .obfx-pricing-feature-content:not(strong){
    font-weight: <?php echo esc_attr( $settings->feature_font_family['weight'] ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-feature-content i{
    color: #<?php echo esc_attr( $settings->icon_color ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-feature-content strong{
    color: #<?php echo esc_attr( $settings->bold_color ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-pricing-feature-content:not(i):not(strong){
    color: #<?php echo esc_attr( $settings->feature_color ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-plan-bottom{
    margin-top: <?php echo esc_attr( $settings->button_margin_top ); ?>px;
    margin-bottom: <?php echo esc_attr( $settings->button_margin_bottom ); ?>px;
    margin-left: <?php echo esc_attr( $settings->button_margin_left ); ?>px;
    margin-right: <?php echo esc_attr( $settings->button_margin_right ); ?>px;
}

.fl-node-<?php echo $id; ?> .obfx-plan-button{
    padding-top: <?php echo esc_attr( $settings->button_padding_top ); ?>px;
    padding-bottom: <?php echo esc_attr( $settings->button_padding_bottom ); ?>px;
    padding-left: <?php echo esc_attr( $settings->button_padding_left ); ?>px;
    padding-right: <?php echo esc_attr( $settings->button_padding_right ); ?>px;
    color: #<?php echo esc_attr( $settings->button_text_color ); ?>;
    background-color: #<?php echo esc_attr( $settings->button_bg_color ); ?>;
    font-size: <?php echo esc_attr( $settings->button_font_size ); ?>px;
    text-transform: <?php echo esc_attr( $settings->button_transform ); ?>;
    font-style: <?php echo esc_attr( $settings->button_font_style ); ?>;
    line-height: <?php echo esc_attr( $settings->button_line_height ); ?>px;
    letter-spacing: <?php echo esc_attr( $settings->button_letter_spacing ); ?>px;
    font-family: <?php echo esc_attr( $settings->button_font_family['family'] ); ?>;
    font-weight: <?php echo esc_attr( $settings->button_font_family['weight'] ); ?>;
}

.fl-node-<?php echo $id; ?> .obfx-plan-button:hover{
    color: #<?php echo esc_attr( $settings->button_text_color_hover ); ?>;
    background-color: #<?php echo esc_attr( $settings->button_bg_color_hover ); ?>;
}