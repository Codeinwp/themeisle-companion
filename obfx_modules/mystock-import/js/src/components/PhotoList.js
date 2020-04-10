/* global mystock_import */
import Flickr from "flickr-sdk";
import Photo from './Photo';
const { Component } = wp.element;

class PhotoList extends Component {

	constructor(props) {
		super(props);

		this.apiKey = mystock_import.api_key;
		this.userId = mystock_import.user_id;
		this.perPage = mystock_import.per_page;

		this.flickr = new Flickr( this.apiKey );
		this.results = (this.props.results) ? this.props.results : [];
		this.state = { results: this.results };

		this.isSearch = false;
		this.search_term = '';

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

		if(this.isSearch){
			this.doSearch(this.search_term, true)
		} else {
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

	}

	/**
	 * checkTotalResults
	 * A checker to determine is there are remaining search results.
	 *
	 * @param num   int    Total search results
	 * @since 3.0
	 */
	checkTotalResults(num){
		this.isDone = ( num < this.perPage );
	}

	/**
	 * search()
	 * Trigger Unsplash Search
	 *
	 * @param e   element    the search form
	 * @since 3.0
	 */
	search(e){

		e.preventDefault();
		let input = document.querySelector('#photo-search');
		let term = input.value;
		if(term.length > 2){
			input.classList.add('searching');
			this.container.classList.add('loading');
			this.search_term = term;
			this.isSearch = true;
			this.page = 0;
			this.doSearch(this.search_term);
		} else {
			input.focus();
		}

	}

	/**
	 * doSearch
	 * Run the search
	 *
	 * @param term   string    the search term
	 * @param type   string    the type of search, standard or by ID
	 * @since 3.0
	 * @updated 3.1
	 */
	doSearch(term, append = false){

		let self = this;
		this.page = parseInt(this.page) + 1;
		let input = document.querySelector('#photo-search');

		let args = {
			'api_key': this.apiKey,
			'user_id': this.userId,
			'text': this.search_term,
			'per_page' : this.perPage,
			'extras': 'url_m, url_o',
			'page': this.page,
		};
		this.flickr.photos.search( args )
			.then( function( res ) {
				let photos =  res.body.photos.photo;

				let photoArray = [];
				photos.map( data => {
					if( append !== true ){
						photoArray.push(data);
					} else {
						self.results.push(data);
					}
				});

				if( append !== true ) {
					self.results = photoArray;
				}

				// Check for returned data
				self.checkTotalResults(photos.length);

				// Update Props
				self.setState({ results: self.results });

				input.classList.remove('searching');
			})
			.catch(function ( err ) {
				console.log(err);
				self.isLoading = false;
			});
	}

	/**
	 * Reset search
	 */
	resetSearch(){
		let input = document.querySelector('#photo-search');
		this.isSearch = false;
		this.page = 0;
		this.results = [];
		input.value = '';
		this.getPhotos();
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
		let button = '';
		if ( ! this.isDone) {
			button = <div className="load-more-wrap">
				<button type="button" className="button" onClick={() => this.getPhotos()}>{ mystock_import.load_more }</button>
			</div>;
		}

		return (
			<div id="photo-listing">
				<div className="search-field" id="search-bar">
					<form onSubmit={(e) => this.search(e)} autoComplete="off">
						<input type="text" id="photo-search" placeholder={ mystock_import.search } />
						<button type="submit" id="photo-search-submit"><span className="dashicons dashicons-search"></span></button>
						<button id="clear-search" onClick={(e) =>this.resetSearch()}><span className="dashicons dashicons-no"></span></button>
					</form>
				</div>

				<div className="error-messaging"></div>

				<div id="msp-photos">
				{ this.state.results.map((result, iterator) =>
						<Photo result={result} key={result.id+iterator} SetFeaturedImage={this.SetFeaturedImage} InsertImage={this.InsertImage} />
				)}
				</div>

				<div className={ this.results.length === 0 && this.isSearch === true ? 'no-results show' : 'no-results'} title={ this.props.title }>
					<h3>{ mystock_import.no_results } </h3>
					<p>{ mystock_import.no_results_desc } </p>
				</div>

				{button}
			</div>
		);
	}

}

export default PhotoList;
