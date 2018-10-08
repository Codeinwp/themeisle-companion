/**
 * WordPress dependencies...
 */

const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	Placeholder,
	Dashicon,
	TextControl,
	Spinner,
	Button,
	Toolbar,
	Tooltip,
} = wp.components;

const {
	compose,
	withState
} = wp.compose;

const { BlockControls } = wp.editor;

const { withSelect } = wp.data;

const { ENTER } = wp.keycodes;

const starRating = stars => {
	const rating = Math.floor( stars / 10 ) / 2;
	const fullStars = Math.floor( rating );
	const halfStars = Math.ceil( rating - fullStars );
	const emptyStars = 5 - fullStars - halfStars;
	const ratings = '<span class="star-full"></span>'.repeat( fullStars ) + '<span class="star-half"></span>'.repeat( halfStars ) + '<span class="star-empty"></span>'.repeat( emptyStars );
	return ratings;
}

const unescapeHTML = value => {
	const htmlNode = document.createElement( 'div' );
	htmlNode.innerHTML = value;
	if( htmlNode.innerText !== undefined ) {
		return htmlNode.innerText;
	}
	return htmlNode.textContent;
};

/**
 * Internal dependencies
 */
import './editor.scss';
import './style.scss';

registerBlockType( 'orbitfox/plugin-cards', {
	title: __( 'Plugin Card' ),
	description: __( 'Plugin Card block lets you display plugins data in your blog posts.' ),
	icon: 'admin-plugins',
	category: 'orbitfox',
	keywords: [
		'plugin',
		'card',
		'orbitfox'
	],
	attributes: {
		slug: {
			type: 'string',
		},
		plugin_icon: {
			type: 'string',
		},
		plugin_name: {
			type: 'string',
		},
		plugin_author: {
			type: 'string',
		},
		plugin_rating: {
			type: 'number',
		},
		plugin_description: {
			type: 'string',
		},
		plugin_installs: {
			type: 'number',
		},
		plugin_version: {
			type: 'string',
		},
		plugin_tested: {
			type: 'string',
		},
		plugin_link: {
			type: 'string',
		},
	},

	supports: {
		html: false,
		align: [ 'left', 'center', 'right' ],
	},

	edit: compose( [

		withSelect( ( select, props ) => {
			return {
				props,
			};
		} ),

		withState( {
			status: 0,
			results: {},
		} )

	] )( ( { props, className, status, results, setState } ) => {

		const changeSlug = ( value ) => {
			props.setAttributes( { slug: value } );
		};

		const searchPlugins = ( search ) => {
			setState( { status: 1 } );
			wp.apiFetch( { path: `obfx-plugin-card/v1/search?search='${ encodeURIComponent( search ) }` } ).then( payload => {
				const data = payload.data.plugins;
				setState( {
					status: 0,
					results: data
				} );
			} );
		};

		const selectPlugin = ( data ) => {
			let icon;
			if ( data.icons['svg'] ) {
				icon = data.icons['svg'];
			} if ( data.icons['2x'] ) {
				icon = data.icons['2x'];
			} if ( data.icons['1x'] ) {
				icon = data.icons['1x'];
			} if ( data.icons['default'] ) {
				icon = data.icons['default'];
			}
			props.setAttributes( {
				slug: data.slug,
				plugin_icon: icon,
				plugin_name: data.name,
				plugin_author: data.author,
				plugin_rating: data.rating,
				plugin_description: data.short_description,
				plugin_installs: data.active_installs,
				plugin_version: data.version,
				plugin_tested: data.tested,
				plugin_link: data.download_link,
			} );
			setState( {
				results: {},
			} );
		};

		return [
			( props.attributes.plugin_name ) && (
				<BlockControls key="toolbar-controls">
					<Toolbar
						className='components-toolbar'
					>
						<Tooltip text={ __( 'Edit Plugin Card' )	}>
							<Button
								className="components-icon-button components-toolbar__control edit-plugin-card"
								onClick={ () => {
									props.setAttributes( {
										plugin_icon: '',
										plugin_name: '',
										plugin_author: '',
										plugin_rating: '',
										plugin_description: '',
										plugin_installs: '',
										plugin_version: '',
										plugin_tested: '',
										plugin_link: '',
									} );
								} }
							>
								<Dashicon icon="edit" />
							</Button>
						</Tooltip>
					</Toolbar>
				</BlockControls>
			),
			<div className={ `${ className }` }>
				{ ( props.attributes.plugin_name ) ?
					<div class="obfx-plugin-card">
						<div class="card-header">
							<div class="card-main">
								<div class="card-logo">
									<img src={ props.attributes.plugin_icon } alt={ unescapeHTML( props.attributes.plugin_name ) } title={ unescapeHTML( props.attributes.plugin_name ) }/>
								</div>
								<div class="card-info">
									<h4>{ unescapeHTML( props.attributes.plugin_name ) }</h4>
									<h5 dangerouslySetInnerHTML={ { __html: _.unescape( props.attributes.plugin_author ) } }></h5>
								</div>
								<div class={ `card-ratings` } dangerouslySetInnerHTML={ { __html: _.unescape( starRating( props.attributes.plugin_rating ) ) } }></div>
							</div>
						</div>
						<div class="card-details">
							<div class="card-description">{ unescapeHTML( props.attributes.plugin_description ) }</div>
							<div class="card-stats">
							<h5>{__( 'Plugin Stats' ) }</h5>
								<div class="card-stats-list">
									<div class="card-stat">
										<span class="card-text-large">{ props.attributes.plugin_installs.toLocaleString() }+</span>
										{ __( 'active installs' ) }
									</div>
									<div class="card-stat">
										<span class="card-text-large">{ props.attributes.plugin_version }</span>
										{ __( 'version' ) }
									</div>
									<div class="card-stat">
										<span class="card-text-large">{ props.attributes.plugin_tested }</span>
										{ __( 'tested up to' ) }
									</div>
								</div>
							</div>
						</div>
						<div class="card-download">
							<a href={ props.attributes.plugin_link }>{ __( 'Download' ) }</a>
						</div>
					</div>
				:
					<Placeholder
						icon="admin-plugins"
						label={ __( 'Plugin Card' ) }
					>
						<div className="search-plugin-field">
							<Dashicon icon="search" />
							{ status === 1 && (
								<Spinner/>
							) }
							<TextControl
								type="text"
								placeholder={ __( 'Search for plugin…' ) }
								value={ props.attributes.slug }
								onChange={ changeSlug }
								onKeyDown={ ( event ) => {
									if ( event.keyCode === ENTER ) {
										searchPlugins( event.target.value )
									}
								}}
							/>
							{ results && (
								<div className="plugin-card-search-results">
									<div>
										{ Object.keys( results ).map( ( i, j ) => {
											const plugin_data = results[i];
											let icon;
											if ( plugin_data.icons['svg'] ) {
												icon = plugin_data.icons['svg'];
											} if ( plugin_data.icons['2x'] ) {
												icon = plugin_data.icons['2x'];
											} if ( plugin_data.icons['1x'] ) {
												icon = plugin_data.icons['1x'];
											} if ( plugin_data.icons['default'] ) {
												icon = plugin_data.icons['default'];
											}
											return (
												<div className="plugin-card-list-item" key={i} onClick={ (e) => {
													e.preventDefault();
													selectPlugin( plugin_data );
												} }>
													<img src={ icon } />
													<span dangerouslySetInnerHTML={ { __html: _.unescape( plugin_data.name ) } }></span>
												</div>
											)
										} ) }
									</div>
								</div>
							) }
						</div>
					</Placeholder>
				}
			</div>
		]
	} ),

	save: () => {
		return null;
	},
});