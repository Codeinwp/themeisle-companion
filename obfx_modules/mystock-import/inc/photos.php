<div id='obfx_mystock' class='obfx_mystock'>
	<ul class='attachments obfx_mystock_photos'>
<?php
	if ( $urls ) {
		foreach ( $urls as $url ) {
?>
		<li class='attachment obfx_mystock_photo'>
			<img src='<?php echo $url; ?>'>
			<div class='obfx_mystock_photo_size'>
				<select name='size'>
			<?php
				foreach ( $sizes as $label => $value ) {
			?>
					<option value='<?php echo $value;?>'><?php echo $label; ?></option>
			<?php
				}
			?>
				</select>
			</div>
		</li>
<?php
		}
	}
?>
	</ul>
</div>