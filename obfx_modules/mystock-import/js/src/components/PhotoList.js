/* global mystock_import */
import React from 'react';
import Flickr from "flickr-sdk";
import Photo from './Photo';

class PhotoList extends React.Component {

	constructor(props) {
		super(props);

		this.apiKey = mystock_import.api_key;
		this.userId = mystock_import.user_id;
		this.perPage = mystock_import.per_page;

		this.flickr = new Flickr( this.apiKey );
		this.results = (this.props.results) ? this.props.results : [];
		this.state = { results: this.results };



		this.isLoading = false; // loading flag
		this.isDone = false; // Done flag - no photos remain

		this.page = this.props.page;

		this.container = document.querySelector('body');
		this.container.classList.add('loading');
		this.wrapper = document.querySelector('body');

		this.SetFeaturedImage = (this.props.SetFeaturedImage) ? this.props.SetFeaturedImage.bind(this) : '';
		this.InsertImage = (this.props.InsertImage) ? this.props.InsertImage.bind(this) : '';

	}

	/**
	 * test()
	 * Test access to the Flickr API
	 *
	 * @since 3.2
	 */
	test() {
		let self   = this;
		let target = document.querySelector('.error-messaging');
		this.flickr.test.echo( this.apiKey ).then(function (res) {
			if( res.statusCode < 200 || res.statusCode >= 400 ){
				self.renderTestError(target);
			}
		})
	}

	/**
	 * Render test error
	 *
	 * @param target
	 */
	renderTestError(target){
		target.classList.add('active');
		target.innerHTML = mystock_import.error_restapi;
	}

	/**
	 * getPhotos
	 * Load next set of photos, infinite scroll style
	 *
	 * @since 3.0
	 */
	getPhotos() {
		let self = this;
		this.page = parseInt(this.page) + 1;
		this.container.classList.add('loading');
		this.isLoading = true;

		let args = {
			'api_key': this.apiKey,
			'user_id': this.userId,
			'per_page': this.perPage,
			'extras': 'url_m, url_o',
			'page': this.page,
		};
		this.flickr.people.getPublicPhotos( args )
			.then( function( res ) {
				let photos =  res.body.photos.photo;

				photos.map( data => {
					self.results.push(data);
				});

				// Check for returned data
				self.checkTotalResults(photos.length);

				// Update Props
				self.setState({ results: self.results });
			})
			.catch(function ( err ) {
				console.log(err);
				self.isLoading = false;
			});
	}

	/**
	 * checkTotalResults
	 * A checker to determine is there are remaining search results.
	 *
	 * @param num   int    Total search results
	 * @since 3.0
	 */
	checkTotalResults(num){
		this.isDone = ( num === 0 );
	}

		/**
	 * Component Init
 	 */
	componentDidMount() {
		this.test();

		this.page = 0;
		this.getPhotos();

	}

	/**
	 * render()
	 * Render function for this component
	 *
	 * @returns {*}
	 */
	render(){
		return (
			<div id="photo-listing">
			<div className="error-messaging"></div>

			<div id="photos">
			{ this.state.results.map((result, iterator) =>
					<Photo result={result} key={result.id+iterator} SetFeaturedImage={this.SetFeaturedImage} InsertImage={this.InsertImage} />
			)}
			</div>
			</div>
		);
	}

}

export default PhotoList;
