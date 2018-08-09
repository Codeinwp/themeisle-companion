/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { InnerBlocks } = wp.editor;

registerBlockType( 'orbitfox/services', {
	title: __( 'Our Services' ),
	icon: 'columns',
	category: 'layout',
	keywords: [
		'services',
		'features',
		'orbitfox'
	],

	edit( { className } ) {
		const ALLOWED_BLOCKS = [ 'orbitfox/service-block' ];
		const TEMPLATE = [ [ 'orbitfox/service-block' ], [ 'orbitfox/service-block' ], [ 'orbitfox/service-block' ] ];
		return (
			<div className={ className }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
				/>
			</div>
		);
	},

	save() {
		return (
			<div className={ `obfx-services` }>
				<InnerBlocks.Content/>
			</div>
		);
	},
});