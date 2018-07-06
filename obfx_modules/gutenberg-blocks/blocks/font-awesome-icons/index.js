/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType,
} = wp.blocks;

const {
	FontSizePicker,
	PanelBody
} = wp.components;

const {
	AlignmentToolbar,
	BlockControls,
	InspectorControls,
} = wp.editor;

const {
	Component,
	Fragment,
} = wp.element;

import './style.scss';

registerBlockType('orbitfox/font-awesome-icons', {
	title: __('Font Awesome Icon'),
	icon: 'info',
	category: 'common',
	keywords: [
		'fontawesome',
		'icon',
		'orbitfox'
	],

	attributes: {
		icon: {
			type: 'string',
			default: 'twitter'
		},
		size: {
			type: 'number',
			default: 32
		},
		align: {
			type: 'string',
			default: 'center'
		},
	},

	edit( {attributes, setAttributes, className} ) {
		const {
			icon,
			size,
			align
		} = attributes;

		const styles = {
			fontSize: size + 'px',
			textAlign: align,
		};

		return (
			<Fragment>
				<BlockControls>
					<AlignmentToolbar
						value={ align }
						onChange={ ( nextAlign ) => {
							setAttributes( { align: nextAlign } );
						} }
					/>
				</BlockControls>
				<InspectorControls>
					<PanelBody title={ __( 'Settings' ) } className="blocks-font-size">
						<FontSizePicker
							value={ size }
							onChange={ ( next ) => {
								setAttributes( { size: next } );
							} }
						/>
					</PanelBody>
				</InspectorControls>
				<div style={ styles }>
					<i className={'fa fa-' + icon }></i>
				</div>
			</Fragment>
		)
	},

	save( { attributes } ) {
		const {
			icon,
			size,
			align
		} = attributes;

		const styles = {
			fontSize: size + 'px',
			textAlign: align,
		};

		return <i
			style={ styles }
			className={'fa fa-' + icon }
		></i>;
	},
});