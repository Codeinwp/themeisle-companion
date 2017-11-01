
    <div id='obfx_mystock' class='obfx_mystock' data-pagenb="<?php echo esc_attr( $urls['lastpage'] ); ?>">
        <ul class='attachments obfx_mystock_photos'>
			<?php
			if ( !empty( $urls ) ) {
				foreach ( $urls as $photo ) {
					$pid = $photo['id'];
					if( !empty( $pid ) ) {
						$thumb = $photo['url_m'];
						?>
                        <li class='attachment obfx_mystock_photo' data-pid="<?php echo esc_attr( $pid ); ?>">
                            <div class="attachment-preview">
                                <div class="thumbnail">
                                    <div class="centered">
                                        <img src='<?php echo esc_url( $thumb ); ?>'>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="check obfx_check" tabindex="0">
                                <span class="media-modal-icon"></span>
                                <span class="screen-reader-text"><?php esc_html_e( 'Deselect', 'themeisle-companion' ) ?></span>
                            </button>
                        </li>
						<?php
					}
				}
			} ?>
        </ul>
        <div class="media-sidebar"></div>
    </div>
