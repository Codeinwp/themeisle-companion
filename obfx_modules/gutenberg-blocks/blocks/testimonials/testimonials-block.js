/**
 * WordPress dependencies...
 */

const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InnerBlocks,
} = wp.editor;

const { 
	Dashicon,
	Toolbar,
	Button,
	Tooltip,
} = wp.components;

/**
 * Internal dependencies
 */
registerBlockType( 'orbitfox/testimonials-block', {
	title: __( 'Testimonials Block' ),
	description: __( 'Display kudos from customers and clients and display them on your website.' ),
	parent: [ 'orbitfox/testimonials-area' ],
	icon: 'testimonial',
	category: 'orbitfox',
	keywords: [
		'testimonials',
		'clients',
		'quotes'
	],

	edit: () => {
		const TEMPLATE =  [
			['core/image', {
				align: 'center',
			}],
			['core/heading', {
				content: __( 'John Doe' ),
				className: 'testimonials-title',
				align: 'center',
				level: 3,
			}],
			['core/heading', {
				content: __( 'Jedi Master' ),
				className: 'testimonials-subtitle',
				align: 'center',
				level: 6,
			}],
			['core/paragraph', {
				content: __( 'What is the point of being alive if you don’t at least try to do something remarkable?' ),
				className: 'testimonials-content',
				align: 'center',
			}],
		];

		return (
			<div className="wp-block-column" >
				<InnerBlocks
					template={ TEMPLATE }
				/>
			</div>
		);
	},

	save: () => {
		return (
			<div className="wp-block-column" >
				<InnerBlocks.Content/>
			</div>
		);
	},
});