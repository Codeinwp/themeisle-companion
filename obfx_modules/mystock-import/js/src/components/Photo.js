/* global mystock_import */

const { Component, createRef } = wp.element;
const { __ } = wp.i18n;
const { Snackbar } = wp.components;
const { createNotice } = wp.data.dispatch( 'core/notices' );
const dispatchNotice = value => {
	if ( ! Snackbar ) {
		return;
	}

	createNotice(
		'info',
		value,
		{
			isDismissible: true,
			type: 'snackbar'
		}
	);
};

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

		this.noticeRef = createRef();
		this.imageRef  = createRef();

		this.state = { attachmentId: '' };
	}

	/**
	 * uploadPhoto
	 * Function to trigger image upload
	 *
	 * @param e  element    clicked item
	 * @returns {boolean}
	 */
	uploadPhoto(e){
		e.preventDefault();

		let self = this;
		let target = e.currentTarget;
		let photo = target.parentElement.parentElement.parentElement.parentElement.parentElement;
		let notice = this.noticeRef.current;
		let photoContainer = this.imageRef.current;
		/**
		 * Bail if image was imported and the user clicks on Add to Media Library
		 */
		if ( target.classList.contains( 'download' ) && this.state.attachmentId !== '' ) {
			return false;
		}


		photoContainer.classList.add( 'uploading' );
		photo.classList.add( 'in-progress' );
		notice.innerHTML = __( 'Downloading Image...', 'themeisle-companion' );
		this.inProgress = true;

		/**
		 * Skip the uploading image part if image was already uploaded
		 */
		if ( this.state.attachmentId !== '' ) {
			this.doPhotoAction( target, photo, this.state.attachmentId );
			return true;
		}

		let formData = new FormData;
		formData.append( 'action', 'handle-request-' + mystock_import.slug );
		formData.append( 'url', this.fullSize );
		formData.append( 'security',  mystock_import.nonce );

		wp.apiFetch({
			url: mystock_import.ajaxurl,
			method: 'POST',
			body: formData
		})
		.then( function ( res ) {
			if ( res && res.success === true && res.data.id ) {
				self.doPhotoAction( target, photo, res.data.id );
				self.setState( { attachmentId: res.data.id } );
			} else {
				self.uploadError( target, photo, __( 'Unable to download image to server, please check your server permissions.', 'themeisle-companion' ) );
			}
		})
		.catch( function ( error ) {
			console.log( error );
		});
	}

	/**
	 * Insert image into post or set image as thumbnail
	 *
	 * @param target        element clicked item
	 * @param photo         element current photo element
	 * @param attachmentId  attachement id
	 */
	doPhotoAction( target, photo, attachmentId ) {
		this.uploadComplete( target, photo, attachmentId );

		if ( this.setAsFeaturedImage ) {
			this.SetFeaturedImage( attachmentId );
			this.setAsFeaturedImage = false;
		}

		if ( this.insertIntoPost ){
			this.InsertImage( this.fullSize, this.imgTitle );
			this.insertIntoPost = false;
		}
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
		let photoContainer = this.imageRef.current;
		photoContainer.classList.remove( 'uploading' );

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

		this.setState( { attachmentId: attachment } );

		let photoContainer = this.imageRef.current;
		photoContainer.classList.remove( 'uploading' );
		photoContainer.classList.add( 'success' );

		photo.classList.remove( 'in-progress' );
		photo.classList.add( 'uploaded', 'done' );
		target.parentNode.parentNode.classList.add( 'disabled' );
		setTimeout(
	function(){
				photoContainer.classList.remove( 'success' );
				photo.classList.remove( 'uploaded' );
				target.parentNode.parentNode.classList.remove( 'disabled') ;
			},
	3000,
			target,
			photo
		);
		this.inProgress = false;
		dispatchNotice( __( 'Image was added to Media Library.', 'themeisle-companion' ) );
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
							ref={this.imageRef}
							>
							<img src={this.img} alt={this.imgTitle} />
							<div className="status" />
						</a>

						<div ref={this.noticeRef} className="notice-msg"/>

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
