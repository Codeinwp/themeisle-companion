/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	InnerBlocks,
} = wp.editor;

registerBlockType( 'orbitfox/accordion-area', {
	title: __( 'Accordion' ),
	description: __( 'Accordion block allows you to add beautiful accordions in your posts.' ),
	icon: 'menu',
	category: 'orbitfox',
	keywords: [
		'accordion',
		'collapsible',
		'orbitfox'
	],

	edit: props => {
		const ALLOWED_BLOCKS = [ 'orbitfox/accordion-block' ];
		const TEMPLATE = [ [ 'orbitfox/accordion-block' ], [ 'orbitfox/accordion-block' ], [ 'orbitfox/accordion-block' ] ];
		return (
			<div className={ props.className }>
				<ul>
					<InnerBlocks
						allowedBlocks={ ALLOWED_BLOCKS }
						template={ TEMPLATE }
					/>
				</ul>
			</div>
		);
	},

	save: () => {
		return (
			<div className="wp-block-orbitfox-accordion-box">
				<ul>
					<InnerBlocks.Content/>
				</ul>
			</div>
		);
	},
});