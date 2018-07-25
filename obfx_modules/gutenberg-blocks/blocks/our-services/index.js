/**
 * External dependencies
 */

import classnames from 'classnames';
import memoize from 'memize';

/**
 * WordPress dependencies...
 */
const {__, sprintf} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	PanelBody,
	RangeControl
} = wp.components;

const {
	InspectorControls,
	InnerBlocks,
	RichText
} = wp.editor;

const {
	Component,
	Fragment,
} = wp.element;

/**
 * Internal dependencies
 */
import './style.scss';
import './editor.scss';

registerBlockType('orbitfox/our-services', {
	title: sprintf(
		/* translators: Block title modifier */
		__( '%1$s' ),
		__( 'Our Services' )
	),

	icon: 'columns',

	category: 'layout',

	attributes: {
		columns: {
			type: 'number',
			default: 3,
		},
	},

	keywords: [
		'services',
		'orbitfox'
	],

	supports: {
		align: [ 'wide', 'full' ],
	},

	edit( { attributes, setAttributes, className } ) {
		const { columns } = attributes;
		const classes = classnames( className, `obfx-our-services has-${ columns }-columns` );

		const memlayout = memoize( ( columns ) => {
			return Array.apply(null, Array(columns)).map((i, n) => ({
				name: `column-${ n + 1 }`,
				label: sprintf( __( 'Column %d' ), n + 1 ),
				icon: 'columns',
			} ) );
		} )(columns)

		console.log( memlayout  )
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<RangeControl
							label={ __( 'Columns' ) }
							value={ columns }
							onChange={ ( nextColumns ) => {
								setAttributes( {
									columns: nextColumns,
								} );
							} }
							min={ 2 }
							max={ 4 }
						/>
					</PanelBody>
				</InspectorControls>
				<div className={ classes }>
					<InnerBlocks
						templateLock={ 'all' }
						allowedBlocks={ [ 'core/column' ] }
						template={memoize( ( columns ) => {
							return _.times( columns, () => [ 'core/column', {}, [
								['orbitfox/font-awesome-icons', {
									size: '62',
									icon: 'angellist'
								}],
								['core/heading', {
									content: 'Happiness',
									align: "center",
									nodeName: "H3"
								}],
								['core/paragraph', {
									content: 'Lorem ipsum dolor sit amet elit do, consectetur adipiscing, sed eiusmod tempor incididunt ut labore et dolore magna aliqua.',
									align: "center"
								}],
							] ] );
						} )( columns )}
						// template={[
						// 	['core/column', {}, [
						// 		['orbitfox/font-awesome-icons', {
						// 			size: '62',
						// 			icon: 'american-sign-language-interpreting'
						// 		}],
						// 		['core/heading', {
						// 			content: 'Happiness',
						// 			align: "center",
						// 			nodeName: "H3"
						// 		}],
						// 		['core/paragraph', {
						// 			content: 'Lorem ipsum dolor sit amet elit do, consectetur adipiscing, sed eiusmod tempor incididunt ut labore et dolore magna aliqua.',
						// 			align: "center",
						// 		}],
						// 	]],
						//
						// 	['core/column', {}, [
						// 		['orbitfox/font-awesome-icons', {
						// 			size: '62',
						// 			icon: 'android'
						// 		}],
						// 		['core/heading', {
						// 			content: 'Flexibility',
						// 			align: "center",
						// 			nodeName: "H3"
						// 		}],
						// 		['core/paragraph', {
						// 			content: 'Lorem ipsum dolor sit amet elit do, consectetur adipiscing, sed eiusmod tempor incididunt ut labore et dolore magna aliqua.',
						// 			align: "center",
						// 		}],
						// 	]],
						//
						// 	['core/column', {}, [
						// 		['orbitfox/font-awesome-icons', {
						// 			size: '62',
						// 			icon: 'angellist'
						// 		}],
						// 		['core/heading', {
						// 			content: 'Support',
						// 			align: "center",
						// 			nodeName: "H3"
						// 		}],
						// 		['core/paragraph', {
						// 			content: 'Lorem ipsum dolor sit amet elit do, consectetur adipiscing, sed eiusmod tempor incididunt ut labore et dolore magna aliqua.',
						// 			align: "center",
						// 		}],
						// 	]],
						// ]}
					/>
				</div>
			</Fragment>
		);
	},
	save( { attributes } ) {
		const { columns } = attributes;

		return (
			<div className={ `obfx-our-services has-${ columns }-columns` }>
				<InnerBlocks.Content />
			</div>
		);
	},
});