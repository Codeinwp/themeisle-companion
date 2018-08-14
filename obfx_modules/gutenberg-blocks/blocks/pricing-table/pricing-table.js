/**
 * WordPress dependencies...
 */

const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { InnerBlocks } = wp.editor;

registerBlockType( 'orbitfox/pricing-table', {
	title: __( 'Pricing Table' ),
	icon: 'slides',
	category: 'layout',
	keywords: [
		'pricing',
		'table',
		'orbitfox'
	],

	edit: ( { className } ) => {
		const ALLOWED_BLOCKS = [ 'orbitfox/pricing-block' ];
		const TEMPLATE = [ [ 'orbitfox/pricing-block' ], [ 'orbitfox/pricing-block' ], [ 'orbitfox/pricing-block' ] ];
		return (
			<div className={ className }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
				/>
			</div>
		);
	},

	save: () => {
		return (
			<div className="wp-block-orbitfox-pricing-table">
				<InnerBlocks.Content/>
			</div>
		);
	},
});