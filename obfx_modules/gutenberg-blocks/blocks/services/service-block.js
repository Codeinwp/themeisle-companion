/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	InnerBlocks,
	InspectorControls,
	PanelColorSettings,
} = wp.editor;

registerBlockType( 'orbitfox/service-block', {
	title: __( 'Service Block' ),
	parent: [ 'orbitfox/services' ],
	icon: 'slides',
	category: 'layout',
	keywords: [
		'pricing',
		'table',
		'orbitfox'
	],
	attributes: {
		backgroundColor: {
			type: 'string',
			default: '#ffffff',
		},
	},

	edit: props => {
		const setBackgroundColor = value => {
			props.setAttributes( { backgroundColor: value } );
		}
		const TEMPLATE =  [
			['orbitfox/font-awesome-icons', {
				size: '62',
				icon: 'angellist'
			}],
			['core/heading', {
				content: __( 'Panel' ),
				align: 'center',
				level: 4,
			}],
			['core/paragraph', {
				content: __( 'Small description, but a pretty long one.' ),
				align: 'center',
			}],
			['core/button', {
				text: __( 'Learn More' ),
				align: 'center',
			}],
		];

		return [
			<InspectorControls>
				<PanelColorSettings
					title={ __( 'Color Settings' ) }
					initialOpen={ true }
					colorSettings={ [
						{
							value: props.attributes.backgroundColor,
							onChange: setBackgroundColor,
							label: __( 'Background Color' ),
						},
					] }
				>
				</PanelColorSettings>
			</InspectorControls>,

			<div
				className="wp-block-column"	
				style={ {
					backgroundColor: props.attributes.backgroundColor,
				}}
			>
				<InnerBlocks
					template={ TEMPLATE }
				/>
			</div>
		];
	},

	save: props => {
		return (
			<div
				className="wp-block-column"	
				style={ {
					backgroundColor: props.attributes.backgroundColor,
				} }
			>
				<InnerBlocks.Content/>
			</div>
		);
	},
});