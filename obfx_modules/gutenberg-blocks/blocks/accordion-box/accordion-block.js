/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	RichText,
	InnerBlocks
} = wp.editor;

registerBlockType( 'orbitfox/accordion-block', {
	title: __( 'Accordion Item' ),
	parent: [ 'orbitfox/accordion-area' ],
	icon: 'menu',
	category: 'orbitfox',
	keywords: [
		'accordion',
		'collapsible',
		'orbitfox'
	],

	attributes: {
		heading: {
			type: 'array',
			source: 'children',
			selector: 'accordion-heading',
		},
	},

	edit: ( props, { className } ) => {

		const CONTENT =  [
			['core/paragraph', {
				content: __( 'What is the point of being alive if you don’t at least try to do something remarkable?' ),
				className: 'accordion-content',
			}],
		];

		return (
			<li className={ className }>
				<RichText
					tagName="h4"
					className="accordion-heading"
					value={ props.attributes.heading }
					placeholder="Section Title"
					onChange={ ( heading ) => props.setAttributes( { heading } ) }
				/>
				<div className="accordion-content">
					<InnerBlocks
						template={ CONTENT }
						id="accordion-content"
					/>
				</div>
			</li>
		)
	},

	save: props => {
		return (
			<li>
				<input type="checkbox" checked />
				<i></i>
				<RichText.Content
					tagName="h4"
					className="accordion-heading"
					value={ props.attributes.heading }
				/>
				<div className="accordion-content">
					<InnerBlocks.Content/>	
				</div>
			</li>
		);
	},
});