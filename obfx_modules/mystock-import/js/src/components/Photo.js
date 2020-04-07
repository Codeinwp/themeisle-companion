/* global mystock_import */
import React from 'react';
import axios from 'axios';

class Photo extends React.Component {

	constructor(props) {
		super(props);
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
		let photo = target.parentElement.parentElement.parentElement; // Get parent .photo el
		let notice = photo.querySelector('.notice-msg'); // Locate .notice-msg div

		if(!target.classList.contains('upload')){ // If target is .download-photo, switch target definition
			target = photo.querySelector('a.upload');
		}

		if(target.classList.contains('success') || this.inProgress)
			return false; // Exit if already uploaded or in progress.

		target.classList.add('uploading');
		photo.classList.add('in-progress');
		notice.innerHTML = mystock_import.saving;
		this.inProgress = true;


		let formData = new FormData;
		formData.append('action', 'handle-request-' + mystock_import.slug );
		formData.append('url', target.getAttribute('data-url') );
		formData.append('security',  mystock_import.nonce );

		axios.post(mystock_import.ajaxurl, formData )
			.then(function (res) {
				let response = res.data;
				console.log( response );
				if( response && res.status === 200 ) {
					self.uploadComplete( target, photo );


					// Set as featured Image in Gutenberg
					if(self.setAsFeaturedImage){
						if( response.data.id ){
							self.SetFeaturedImage(response.data.id);
						}
						self.setAsFeaturedImage = false;
					}

					if(self.insertIntoPost){
						if(response.data.id){
							self.InsertImage( target.getAttribute('data-url'), self.imgTitle );
						}
						self.insertIntoPost = false;
					}

				} else {
					self.uploadError(target, photo, mystock_import.error_upload);
				}

			})
			.catch(function (error) {
				console.log(error);
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
	uploadError(target, photo, msg){
		target.classList.remove('uploading');
		target.classList.remove('resizing');
		target.classList.add('errors');
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
	uploadComplete( target, photo ){

		photo.classList.remove('in-progress');
		photo.classList.add('uploaded');

		target.classList.remove('uploading');
		target.classList.add('success');

		this.inProgress = false;
	}

	/*
	 * setFeaturedImageClick
	 * Function used to trigger a download and then set as featured image
	 */
	setFeaturedImageClick(e) {
		let target = e.currentTarget;
		if(!target){
			return false;
		}

		let parent = target.parentNode.parentNode.parentNode;
		let photo = parent.querySelector('a.upload');
		if(photo){
			this.setAsFeaturedImage = true;
			photo.click();
		}
	}

	/*
	 * insertImageIntoPost
	 * Function used to insert an image directly into the block (Gutenberg) editor.
	 *
	 * @since 4.0
	 */
	insertImageIntoPost(e){
		let target = e.currentTarget;
		if(!target){
			return false;
		}

		let parent = target.parentNode.parentNode.parentNode;
		let photo = parent.querySelector('a.upload');
		if(photo){
			this.insertIntoPost = true;
			photo.click();
		}
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
							href={this.fullSize}
							// data-id={this.id}
							data-url={this.fullSize}
							// data-filename={this.state.filename}
							// data-title={this.state.title}
							// data-alt={this.state.alt}
							// data-caption={this.state.caption}
							// title={instant_img_localize.upload}
							onClick={(e) => this.uploadPhoto(e)}>
							<img src={this.img} alt={this.imgTitle} />
							<div className="status" />
						</a>

						<div className="notice-msg"/>

						<div className="user-controls">
							<div className="photo-options">
								<a className="set-featured fade"
								   href='#'
								   onClick={(e) => this.setFeaturedImageClick(e)}
								   title={mystock_import.set_as_featured}
								>
									<i className="fa fa-picture-o" aria-hidden="true"/>
									<span className="offscreen">{mystock_import.set_as_featured}</span>
								</a>

								<a className="insert fade"
								   href='#'
								   onClick={(e) => this.insertImageIntoPost(e)}
								   title={mystock_import.insert_into_post}
								>
									<i className="fa fa-plus" aria-hidden="true"/>
									<span className="offscreen">{mystock_import.insert_into_post}</span>
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
