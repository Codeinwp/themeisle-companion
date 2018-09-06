/**
 * WordPress dependencies...
 */

const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { InnerBlocks } = wp.editor;

registerBlockType( 'orbitfox/testimonials-area', {
	title: __( 'Testimonials Area' ),
	description: __( 'Display kudos from customers and clients and display them on your website.' ),
	icon: 'testimonial',
	category: 'orbitfox',
	keywords: [
		'testimonials',
		'clients',
		'quotes'
	],

	edit: props => {
		const ALLOWED_BLOCKS = [ 'orbitfox/testimonials-block' ];
		const TEMPLATE = [ [ 'orbitfox/testimonials-block' ], [ 'orbitfox/testimonials-block' ], [ 'orbitfox/testimonials-block' ] ];
		return (
			<div className={ props.className }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
				/>
			</div>
		);
	},

	save: () => {
		return (
			<div className="wp-block-orbitfox-testimonials-area">
				<InnerBlocks.Content/>
			</div>
		);
	},
});