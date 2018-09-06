/**
 * WordPress dependencies...
 */

const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { InnerBlocks } = wp.editor;

registerBlockType( 'orbitfox/pricing-table', {
	title: __( 'Pricing Table' ),
	description: __( 'Pricing tables are a critical part in showcasing your services, prices and overall offerings.' ),
	icon: 'slides',
	category: 'orbitfox',
	keywords: [
		'pricing',
		'table',
		'orbitfox'
	],

	edit: props => {
		const ALLOWED_BLOCKS = [ 'orbitfox/pricing-block' ];
		const TEMPLATE = [ [ 'orbitfox/pricing-block' ], [ 'orbitfox/pricing-block' ], [ 'orbitfox/pricing-block' ] ];
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
			<div className="wp-block-orbitfox-pricing-table">
				<InnerBlocks.Content/>
			</div>
		);
	},
});