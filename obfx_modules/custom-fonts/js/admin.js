(function($){

	/**
	 * Obfx Custom Fonts
	 *
	 * @class ObfxCustomFonts
	 * @since 1.0.0
	 */

	ObfxCustomFonts = {

		/**
		 * Initializes a Obfx Custom Fonts.
		 *
		 * @since 1.0
		 * @method init
		 */
		init: function()
		{
			// Init.
			this._fileUploads();
		},
		/**
		 * Font File Uploads
		 * parallax.
		 *
		 * @since 1.0.0
		 * @access private
		 * @method _fileUploads
		 */
		_fileUploads: function()
		{
			var file_frame;
			window.inputWrapper = '';
			$( document.body ).on('click', '.obfx-custom-fonts-upload', function(event) {
				event.preventDefault();

				window.inputWrapper = $(this).closest('.obfx-custom-fonts-file-wrap');

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					file_frame.open();
					return;
				}

				// Create a new media frame
				file_frame = wp.media.frames.file_frame = wp.media({
					multiple: false  // Set to true to allow multiple files to be selected
				});

				// When an image is selected in the media frame...
				file_frame.on( 'select', function() {

					// Get media attachment details from the frame state
					var attachment = file_frame.state().get('selection').first().toJSON();
					window.inputWrapper.find( '.obfx-custom-fonts-link' ).val(attachment.url);
				});
				// Finally, open the modal
				file_frame.open();
			});

			window.inputWrapper = '';
		},
	}

	/* Initializes the Obfx Custom Fonts. */
	$(function(){
		ObfxCustomFonts.init();
	});

})(jQuery);
