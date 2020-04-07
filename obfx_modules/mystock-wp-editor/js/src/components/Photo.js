/* global mystock_localize */
import React from 'react';
import axios from 'axios';

class Photo extends React.Component {

	constructor(props) {
		super(props);
		this.img = this.props.result.url_m;
		this.fullSize = this.props.result.url_o;
		this.imgTitle = this.props.result.title;
		this.setAsFeaturedImage = false;
		this.inProgress = false;

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
		notice.innerHTML = mystock_localize.saving;
		this.inProgress = true;

		axios({
			method: 'POST',
			url : mystock_localize.ajaxurl,
			headers: {
				'X-WP-Nonce': mystock_localize.nonce,
				'Content-Type': 'application/json'
			},
			action: 'handle-request-' + mystock_localize.slug,
			data : {
				'action': 'handle-request-' + mystock_localize.slug,
				'url' : target.getAttribute('data-url'),
				'security' : mystock_localize.nonce
			},
		}).then(function (res) {
			console.log(res);
		}).catch(function (error) {
			console.log(error);
		});


		//
		// // Create Data Array
		// let data = {
		// 	id : target.getAttribute('data-id'),
		// 	image : target.getAttribute('data-url')
		// };
		//
		// // REST API URL
		// let url = instant_img_localize.root + 'instant-images/upload/';
		//
		// axios({
		// 	method: 'POST',
		// 	url: url,
		// 	headers: {
		// 		'X-WP-Nonce': instant_img_localize.nonce,
		// 		'Content-Type': 'application/json'
		// 	},
		// 	data: {
		// 		'data': JSON.stringify(data)
		// 	}
		// })
		// 	.then(function (res) {
		//
		// 		let response = res.data;
		//
		// 		if(response && res.status == 200){
		//
		// 			// Successful response from server
		// 			let hasError = response.error;
		// 			let path = response.path;
		// 			let filename = response.filename;
		//
		// 			if(hasError){ // Upload Error
		// 				self.uploadError(target, photo, response.msg);
		//
		// 			} else { // Upload Success
		// 				self.resizeImage(path, filename, target, photo, notice);
		// 				self.triggerUnsplashDownload(data.id);
		//
		// 			}
		//
		// 		} else {
		//
		// 			// Error
		// 			self.uploadError(target, photo, instant_img_localize.error_upload);
		// 		}
		//
		// 	})
		// 	.catch(function (error) {
		// 		console.log(error);
		// 	});

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

	insertImageIntoPost(e) {

	}

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
						</a>

						<div className="notice-msg"/>

						<div className="user-controls">
							<div className="photo-options">
								<a className="set-featured fade"
								   href='#'
								   onClick={(e) => this.setFeaturedImageClick(e)}
								   title={mystock_localize.set_as_featured}
								>
									<i className="fa fa-picture-o" aria-hidden="true"/>
									<span className="offscreen">{mystock_localize.set_as_featured}</span>
								</a>

								<a className="insert fade"
								   href='#'
								   onClick={(e) => this.insertImageIntoPost(e)}
								   title={mystock_localize.insert_into_post}
								>
									<i className="fa fa-plus" aria-hidden="true"/>
									<span className="offscreen">{mystock_localize.insert_into_post}</span>
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
