/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { InnerBlocks } = wp.editor;

registerBlockType( 'orbitfox/services', {
	title: __( 'Our Services' ),
	description: __( 'Use this Services table to showcase services your website offers.' ),
	icon: 'columns',
	category: 'orbitfox',
	keywords: [
		'services',
		'features',
		'orbitfox'
	],

	edit: props => {
		const ALLOWED_BLOCKS = [ 'orbitfox/service-block' ];
		const TEMPLATE = [ [ 'orbitfox/service-block' ], [ 'orbitfox/service-block' ], [ 'orbitfox/service-block' ] ];
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
			<div className={ `obfx-services` }>
				<InnerBlocks.Content/>
			</div>
		);
	},
});