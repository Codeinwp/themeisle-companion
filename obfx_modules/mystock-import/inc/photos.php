<?php
$stored_photos = get_transient( 'mystock_photos' );
//var_dump( $stored_photos );
?>
<div class="attachments-browser obfx-mystock-wrapper">
    <div id='obfx_mystock' class='obfx_mystock' data-pagenb="<?php echo esc_attr( $stored_photos['lastpage'] ); ?>">
        <ul class='attachments obfx_mystock_photos'>
            <?php
            if ( !empty( $stored_photos ) ) {
                foreach ( $stored_photos as $photo_id => $url ) {
                    $thumb = $url[1]['source'];
                    ?>
                    <li class='attachment obfx_mystock_photo' data-pid="<?php echo esc_attr( $photo_id ); ?>">
                        <img src='<?php echo esc_url( $thumb ); ?>'>
                        <button type="button" class="check obfx_check" tabindex="0">
                            <span class="media-modal-icon"></span>
                            <span class="screen-reader-text"><?php esc_html_e('Deselect', 'themeisle-companion' ) ?></span>
                        </button>
                    </li>
                    <?php
                }
            } ?>
        </ul>
        <div class="media-sidebar"></div>
    </div>
</div>