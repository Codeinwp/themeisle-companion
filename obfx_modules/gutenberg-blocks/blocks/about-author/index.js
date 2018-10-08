/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	compose,
	withState
} = wp.compose;

const { Spinner } = wp.components;

const { withSelect } = wp.data;

/**
 * Internal dependencies
 */
import './editor.scss';
import './style.scss';

registerBlockType('orbitfox/about-author', {
	title: __( 'About Author' ),
	description: __( 'About Author block is the easiest way to add a author bio below your posts.' ),
	icon: 'admin-users',
	category: 'orbitfox',
	keywords: [
		'about',
		'author',
		'profile'
	],
	attributes: {
		id: {
			type: 'number',
		},
	},

	supports: {
		html: false,
	},

	edit: compose( [

		withSelect( ( select, props ) => {
			return {
				postAuthor: select('core/editor').getEditedPostAttribute( 'author' ),
				authors: select('core').getAuthors(),
				props,
			};
		} ),

		withState( {
			status: 0,
			author_details: {},
		} )

	] )( ( { postAuthor, authors, status, author_details, setState, props, className } ) => {

		if ( status === 0 && postAuthor && authors && postAuthor !== props.attributes.id ) {
			authors.find( ( o ) => {
				if ( o.id === postAuthor ) {
					props.setAttributes( { id: o.id } );
					setState( {
						author_details: o,
						status: 1,
					} );
					return o.id === postAuthor;
				}
			} );
		}

		return (
			( status === 1 && postAuthor && authors ) ? (
				<section className={ className }>
					<div className="obfx-author-image">
						<img className="author-image" src={ author_details.avatar_urls[ '96' ] } alt={ author_details.name }/>
					</div>
					<div className="obfx-author-data">
						<h4>{ author_details.name }</h4>
						<p>{ author_details.description }</p>
					</div>
				</section>
			) : (
				<div key="loading" className="wp-block-embed is-loading">
					<Spinner />
					<p>{ __( 'Loading…' ) }</p>
				</div>
			)
		);
	} ),

	save: () => {
		return null;
	},
});