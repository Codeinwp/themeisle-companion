/* global mystock_import */

const { Component } = wp.element;
const { __ } = wp.i18n;

class Photo extends Component {

	constructor( props ) {
		super( props );
		this.img = this.props.result.url_m;
		this.fullSize = this.props.result.url_o;
		this.imgTitle = this.props.result.title;
		this.setAsFeaturedImage = false;
		this.insertIntoPost = false;
		this.inProgress = false;

		this.SetFeaturedImage = this.props.SetFeaturedImage;
		this.InsertImage = this.props.InsertImage;

	}

	/*
   * uploadPhoto
   * Function to trigger image upload
   *
   * @param target   element    clicked item
   * @since 3.0
   */
	uploadPhoto(e){
		e.preventDefault();

		let self = this;
		let target = e.currentTarget; // get current <a/>
		let photo = target.parentElement.parentElement.parentElement.parentElement.parentElement; // Get parent .photo el
		let notice = photo.querySelector( '.notice-msg' ); // Locate .notice-msg div
		let attachmentId = photo.getAttribute( 'data-attachment' );
		
		if ( target.classList.contains( 'download' ) && attachmentId ) {
			return false;
		}

		if (!target.classList.contains( 'upload' )){ // If target is .download-photo, switch target definition
			target = photo.querySelector( 'a.upload' );
		}

		target.classList.add( 'uploading' );
		photo.classList.add( 'in-progress' );
		notice.innerHTML = __( 'Downloading Image...', 'themeisle-companion' );
		this.inProgress = true;


		if ( attachmentId ) {
			self.uploadComplete( target, photo, attachmentId );

			if ( self.setAsFeaturedImage ) {
				self.SetFeaturedImage( attachmentId );
				self.setAsFeaturedImage = false;
			}

			if ( self.insertIntoPost ){
				self.InsertImage( target.getAttribute( 'data-url' ), self.imgTitle );
				self.insertIntoPost = false;
			}

			return true;
		}

		let formData = new FormData;
		formData.append( 'action', 'handle-request-' + mystock_import.slug );
		formData.append( 'url', target.getAttribute( 'data-url' ) );
		formData.append( 'security',  mystock_import.nonce );

		wp.apiFetch({
			url: mystock_import.ajaxurl,
			method: 'POST',
			body: formData
		})
		.then( function ( res ) {
			if ( res && res.success === true && res.data.id ) {
				self.uploadComplete( target, photo, res.data.id );

				if ( self.setAsFeaturedImage ) {
					self.SetFeaturedImage( res.data.id );
					self.setAsFeaturedImage = false;
				}

				if ( self.insertIntoPost ){
					self.InsertImage( target.getAttribute( 'data-url' ), self.imgTitle );
					self.insertIntoPost = false;
				}

			} else {
				self.uploadError( target, photo, __( 'Unable to download image to server, please check your server permissions.', 'themeisle-companion' ) );
			}

		})
		.catch( function ( error ) {
			console.log( error );
		});
	}

	/*
	* uploadError
	* Function runs when error occurs on upload or resize
	*
	* @param target   element    Current clicked item
	* @param photo    element    Nearest parent .photo
	* @param msg      string     Error Msg
	* @since 3.0
	*/
	uploadError( target, photo, msg ){
		target.classList.remove( 'uploading' );
		target.classList.add( 'errors' );
		this.inProgress = false;
		console.warn(msg);
	}

	/*
	 * uploadComplete
	 * Function runs when upload has completed
	 *
	 * @param target   element    clicked item
	 * @param photo    element    Nearest parent .photo
	 * @param msg      string     Success Msg
	 * @param url      string     The attachment edit link
	 * @since 3.0
	 */
	uploadComplete( target, photo, attachment ){

		photo.classList.remove( 'in-progress' );
		photo.classList.add( 'uploaded' );
		photo.setAttribute( 'data-attachment', attachment );

		target.classList.remove( 'uploading' );

		target.classList.add( 'success' );
		target.parentNode.querySelector( '.user-controls' ).classList.add( 'disabled' );
		setTimeout(
	function(){
				target.classList.remove( 'success' );
				photo.classList.remove( 'uploaded' );
				target.parentNode.querySelector( '.user-controls' ).classList.remove( 'disabled') ;
			},
	3000,
			target,
			photo
		);
		this.inProgress = false;
	}

	/*
	 * setFeaturedImageClick
	 * Function used to trigger a download and then set as featured image
	 */
	setFeaturedImageClick( e ) {
		let target = e.currentTarget;
		if ( ! target ){
			return false;
		}

		this.setAsFeaturedImage = true;
		this.uploadPhoto( e );
	}

	/*
	 * insertImageIntoPost
	 * Function used to insert an image directly into the block (Gutenberg) editor.
	 *
	 * @since 4.0
	 */
	insertImageIntoPost(e){
		let target = e.currentTarget;
		if ( ! target ){
			return false;
		}

		this.insertIntoPost = true;
		this.uploadPhoto( e );
	}

	/**
	 * Render photo image.
	 *
	 * @returns {*}
	 */
	render(){

		return (
			<article className="photo">
				<div className="photo--wrap">
					<div className='img-wrap'>
						<a
							className='upload loaded'
							href="#"
							data-url={this.fullSize}
							>
							<img src={this.img} alt={this.imgTitle} />
							<div className="status" />
						</a>

						<div className="notice-msg"/>

						<div className="user-controls">
							<div className="photo-options">
								<a className="download fade"
								   href='#'
								   onClick={ ( e ) => this.uploadPhoto( e )}
								   title={__( 'Add to Media Library', 'themeisle-companion' )}
								>
									<span className="dashicons dashicons-download"></span>
								</a>

								<a className="set-featured fade"
								   href='#'
								   onClick={ ( e ) => this.setFeaturedImageClick( e ) }
								   title={__( 'Set as featured image', 'themeisle-companion' )}
								>
									<span className="dashicons dashicons-format-image"></span>
								</a>

								<a className="insert fade"
								   href='#'
								   onClick={ ( e ) => this.insertImageIntoPost( e ) }
								   title={ __( 'Insert into post', 'themeisle-companion' ) }
								>
									<span className="dashicons dashicons-plus"></span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</article>
		)
	}
}

export default Photo;
