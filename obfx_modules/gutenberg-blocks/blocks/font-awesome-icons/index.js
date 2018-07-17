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
	RichText
} = wp.editor;

const {
	Component,
	Fragment,
} = wp.element;

import './style.scss';
// @TODO this block should get some options for colors and background color

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

		// @TODO The icon option should be set via some sort of select with autocomplete not a RichText
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
					<PanelBody title={ __( 'Icon' ) } className="blocks-font-size">
						<p>{ __( 'Pick up an icon from the awesome ' ) }
							<a href={'https://fontawesome.com/icons?d=gallery'} target="_blank">{__('list')}</a>
						</p>
						<RichText
							tagName={ 'p' }
							value={ icon }
							placeholder={ __('The icon key ... ') }
							onChange={ ( icon ) => setAttributes( { icon: icon } ) }
							keepPlaceholderOnFocus
						/>
					</PanelBody>
					<PanelBody title={ __( 'Size' ) } className="blocks-font-size">
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

		return <p style={ styles }>
			<i className={'fa fa-' + icon }></i>
		</p> ;
	},
});