/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

import moment from 'moment';
import classnames from 'classnames';
import { stringify } from 'querystringify';

import Thumbnail from './Thumbnail';

const get = (obj, path, def) => (() => typeof path === 'string' ? path.replace(/\[(\d+)]/g,'.$1') : path.join('.'))()
	.split('.')
	.filter(Boolean)
	.every(step => ((obj = obj[step]) !== undefined)) ? obj : def

const {
	Component,
	Fragment
} = wp.element;

const {
	PanelBody,
	Placeholder,
	QueryControls,
	RangeControl,
	Spinner,
	ToggleControl,
	Toolbar,
	withAPIData,
	TextControl
} = wp.components;

const { decodeEntities } = wp.utils;

const {
	InspectorControls,
	BlockAlignmentToolbar,
	BlockControls
} = wp.editor;

const MAX_POSTS_COLUMNS = 6;

class PostsGridEdit extends Component {
	constructor() {
		super( ...arguments );

		this.toggleDisplayPostDate = this.toggleDisplayPostDate.bind( this );
		this.toggleDisplayFeaturedImage = this.toggleDisplayFeaturedImage.bind( this );
	}

	toggleDisplayPostDate( newValue ) {
		const { setAttributes } = this.props;
		setAttributes( { displayPostDate: newValue } );
	}

	toggleDisplayFeaturedImage( newValue ) {
		const { setAttributes } = this.props;
		setAttributes( { displayFeaturedImage: newValue } );
	}

	render() {
		const latestPosts = this.props.latestPosts.data;
		const { attributes, categoriesList, setAttributes } = this.props;
		const {
			displayFeaturedImage,
			displayPostDate,
			displayExcerpt,
			displayReadMore,
			readMoreLabel,
			align,
			postLayout,
			columns,
			order,
			orderBy,
			categories,
			postsToShow,
			postType,
			taxonomy
		} = attributes;

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={ __( 'Posts Grid Settings' ) }>
					<QueryControls
						{ ...{ order, orderBy } }
						numberOfItems={ postsToShow }
						categoriesList={ get( categoriesList, [ 'data' ], {} ) }
						selectedCategoryId={ categories }
						onOrderChange={ ( value ) => setAttributes( { order: value } ) }
						onOrderByChange={ ( value ) => setAttributes( { orderBy: value } ) }
						onCategoryChange={ ( value ) => setAttributes( { categories: '' !== value ? value : undefined } ) }
						onNumberOfItemsChange={ ( value ) => setAttributes( { postsToShow: value } ) }
					/>

					<ToggleControl
						label={ __( 'Display featured image' ) }
						checked={ displayFeaturedImage }
						onChange={this.toggleDisplayFeaturedImage}
					/>

					<ToggleControl
						label={ __( 'Display post date' ) }
						checked={ displayPostDate }
						onChange={ this.toggleDisplayPostDate }
					/>
					<ToggleControl
						label={ __( 'Display Read More link' ) }
						checked={ displayReadMore }
						onChange={ (newValue) => { setAttributes({displayReadMore:newValue })} }
					/>
					{ displayReadMore &&
					<TextControl
						value={ readMoreLabel }
						label={ __('Read More Label') }
						onChange={ ( newValue ) => setAttributes( { readMoreLabel: newValue } ) }
					/>
					}
					<ToggleControl
						label={ __( 'Display excerpt' ) }
						checked={ displayExcerpt }
						onChange={ (newValue) => { setAttributes({displayExcerpt:newValue })} }
					/>
					{ postLayout === 'grid' &&
					<RangeControl
						label={ __( 'Columns' ) }
						value={ columns }
						onChange={ ( value ) => setAttributes( { columns: value } ) }
						min={ 2 }
						max={ ! hasPosts ? MAX_POSTS_COLUMNS : Math.min( MAX_POSTS_COLUMNS, latestPosts.length ) }
					/>
					}
				</PanelBody>
			</InspectorControls>
		);

		const hasPosts = Array.isArray( latestPosts ) && latestPosts.length;
		if ( ! hasPosts ) {
			return (
				<Fragment>
					{ inspectorControls }
					<Placeholder
						icon="admin-post"
						label={ __( 'Posts Grid' ) }
					>
						{ ! Array.isArray( latestPosts ) ?
							<Spinner /> :
							__( 'No posts found.' )
						}
					</Placeholder>
				</Fragment>
			);
		}

		// Removing posts from display should be instant.
		const displayPosts = latestPosts.length > postsToShow ?
			latestPosts.slice( 0, postsToShow ) :
			latestPosts;

		const layoutControls = [
			{
				icon: 'list-view',
				title: __( 'List View' ),
				onClick: () => setAttributes( { postLayout: 'list' } ),
				isActive: postLayout === 'list',
			},
			{
				icon: 'grid-view',
				title: __( 'Grid View' ),
				onClick: () => setAttributes( { postLayout: 'grid' } ),
				isActive: postLayout === 'grid',
			},
		];

		return (
			<Fragment>
				{ inspectorControls }
				<BlockControls>
					<BlockAlignmentToolbar
						value={ align }
						onChange={ ( nextAlign ) => {
							setAttributes( { align: nextAlign } );
						} }
						controls={ [ 'center', 'wide', 'full' ] }
					/>
					<Toolbar controls={ layoutControls } />
				</BlockControls>
				<ul
					className={ classnames( this.props.className, {
						'wp-block-posts-grid': true,
						'is-grid': postLayout === 'grid',
						[ `columns-${ columns }` ]: postLayout === 'grid',
						[`align${align}`]: typeof align !== "undefined"
					} ) }
				>
					{ displayPosts.map( ( post, i ) => {
						return (<li key={ i }>
							{( displayFeaturedImage && post.featured_media ) ? <Thumbnail id={post.featured_media} /> : null }
							<a href="#">{ decodeEntities( post.title.rendered.trim() ) || __( '(Untitled)' ) }</a>
							{ displayPostDate && post.date_gmt &&
							<time dateTime={ moment( post.date_gmt ).utc().format() } className={ `${ this.props.className }__post-date` }>
								{ moment( post.date_gmt ).local().format( 'MMMM DD, Y' ) }
							</time>}
							{( displayExcerpt ) ? <p dangerouslySetInnerHTML={{__html: post.excerpt.rendered}}></p> : null }
							{ displayReadMore ? <a href={'#'}>{readMoreLabel}</a> : null }
						</li>)}
					) }
				</ul>
			</Fragment>
		);
	}
}

export default withAPIData( ( props ) => {
	const { postsToShow, order, orderBy, categories, postType } = props.attributes;
	const latestPostsQuery = stringify( _.pick( {
		categories,
		order,
		orderby: orderBy,
		per_page: postsToShow,
		_fields: [ 'date_gmt', 'link', 'title', 'featured_media', 'excerpt' ],
	}, ( value ) => ! _.isUndefined( value ) ) );

	const categoriesListQuery = stringify( {
		per_page: 100,
		_fields: [ 'id', 'name', 'parent' ],
	} );

	return {
		//types: "/wp/v2/types",
		latestPosts: "/wp/v2/posts?" + latestPostsQuery ,
		categoriesList: "/wp/v2/categories?" + categoriesListQuery,
	};
} )( PostsGridEdit );