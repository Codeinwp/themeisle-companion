import { stringify } from 'querystringify'
/**
 * WordPress dependencies
 */
const {Component, Fragment} = wp.element;

const {__, sprintf} = wp.i18n;

const { compose } = wp.compose;
const { withSelect } = wp.data;

const {
	FontSizePicker,
	PanelBody,
	RangeControl
} = wp.components;

const {
	AlignmentToolbar,
	BlockControls,
	InspectorControls,
	RichText,
	PanelColorSettings,
	ContrastChecker,
	withColors,
	getColorName,
	withColorContext
} = wp.editor;

import Autocomplete from 'accessible-autocomplete/react';

import './editor.scss';

export class FontAwesomeEditor extends Component {
	constructor() {
		super(...arguments);

		this.suggestIcon = this.suggestIcon.bind(this)
	}

	render() {
		const {
			attributes,
			setAttributes,
			faIconsList,
			setBackgroundColor,
			setBorderColor,
			setTextColor,
			setShadowColor,
			textColor,
			backgroundColor,
			borderColor,
			shadowColor,
			colors
		} = this.props;

		const {
			align,
			icon,
			icon_prefix,
			size,
			borderRadius,
			borderSize,
			borderStyle,
			innerSpaceSize,
			outerSpaceSize,
			shadowSize,
			shadowBlurSize,
			shadowHorizontalSize,
			shadowVerticalSize,
			customShadowColor,
		} = attributes;

		const pStyle = {
			textAlign: align,
		}

		const iconStyle ={
			padding: innerSpaceSize + 'px',
			borderRadius: borderRadius + '%',
			fontSize: size + 'px',
			lineHeight: size + 'px',
		}

		const shb =  typeof shadowColor !== "undefined"
			? shadowHorizontalSize + 'px ' + shadowVerticalSize + 'px ' + shadowBlurSize + 'px ' + shadowSize + 'px ' + ( typeof shadowColor.value ? shadowColor.value : 'inherited' )
			: shadowHorizontalSize + 'px ' + shadowVerticalSize + 'px ' + shadowBlurSize + 'px ' + shadowSize + 'px ' + customShadowColor;

		const wrapperStyle = {
			display: 'inline-block',
			color: typeof textColor !== "undefined" ? textColor.value : null,
			backgroundColor: typeof backgroundColor !== "undefined" ? backgroundColor.value : transparent,
			borderColor: typeof borderColor !== "undefined" ? borderColor.value : 'transparent' ,
			borderRadius: borderRadius + '%',
			borderStyle: 'solid',
			borderWidth: borderSize + 'px',
			margin: outerSpaceSize + 'px',
			// boxShadow: shb
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

					<PanelBody className="icon-autocomplete-selector">

						<p>{__( 'Click and type your icon name')}</p>

						<Autocomplete
							minLength={ 0 }
							showAllValues={ true }
							defaultValue={ icon }
							autoselect={ true }
							displayMenu="overlay"
							onConfirm={ ( newIcon ) => {
								if ( typeof this.props.faIconsList[newIcon] === "undefined" ) {
									return;
								}
								// get the hole config
								const conf = this.props.faIconsList[newIcon];

								setAttributes({
									icon: newIcon,
									icon_prefix: conf.prefix
								})
							}}
							source={ _.debounce( this.suggestIcon, 300 ) }
							showNoResultsFound={ true }
							tStatusNoResults={ () => __( 'No search results' ) }
							tStatusSelectedOption={ ( selectedOption, length ) => __( `${ selectedOption } (1 of ${ length }) is selected` ) }
							tStatusResults={ ( length, contentSelectedOption ) => {
								const words = {
									result: ( length === 1 ) ? __( 'result' ) : __( 'results' ),
									is: ( length === 1 ) ? __( 'is' ) : __( 'are' ),
								};
								return <span>{ length } { words.result } { words.is } available. { contentSelectedOption }</span>;
							} }
						/>

					</PanelBody>

					<PanelBody title={ __( 'Icon Sizes' ) } className="blocks-font-size" initialOpen={ false }>
						<RangeControl
							className="components-font-size-picker__custom-input"
							label={ __( 'Text Size' ) }
							value={ size || '' }
							initialPosition={ 12 }
							onChange={ (newSize) => {
								setAttributes({
									size: newSize
								})
							} }
							min={ 12 }
							max={ 140 }
							beforeIcon="editor-textcolor"
							afterIcon="editor-textcolor"
						/>

						<RangeControl
							className="components-border-size-picker__custom-input"
							label={ __( 'Inner Space' ) }
							value={ innerSpaceSize || 0 }
							initialPosition={ 5 }
							onChange={ (next) => {
								setAttributes( { innerSpaceSize: next } );
							} }
							min={ 0 }
							max={ 100 }
							beforeIcon="minus"
							afterIcon="plus"
						/>

						<RangeControl
							className="components-border-size-picker__custom-input"
							label={ __( 'Outer Space' ) }
							value={ outerSpaceSize || 0 }
							initialPosition={ 5 }
							onChange={ (next) => {
								setAttributes( { outerSpaceSize: next } );
							} }
							min={ 0 }
							max={ 100 }
							beforeIcon="minus"
							afterIcon="plus"
						/>
					</PanelBody>

					<PanelColorSettings
						title={ __( 'Color Settings' ) }
						initialOpen={ false }
						colorSettings={ [
							{
								value: backgroundColor.value,
								onChange: setBackgroundColor,
								label: __( 'Background Color' ),
							},
							{
								value: textColor.value,
								onChange: setTextColor,
								label: __( 'Text Color' ),
							},
							{
								value: borderColor.value,
								onChange: setBorderColor,
								label: __( 'Border Color' ),
							},
							// {
							// 	value: shadowColor.value,
							// 	onChange: setShadowColor,
							// 	label: __( 'Shadow Color' ),
							// },
						] }
					>
						<ContrastChecker
							{ ...{
								textColor: textColor.value,
								backgroundColor: backgroundColor.value,
								borderColor: borderColor.value,
								shadowColor: shadowColor.value,
							} }
						/>
					</PanelColorSettings>

					<PanelBody title={ __( 'Border' ) }  className="blocks-border-size" initialOpen={ false }>

						<RangeControl
							className="components-border-size-picker__custom-input"
							label={ __( 'Border Size' ) }
							value={ borderSize || 0 }
							initialPosition={ 5 }
							onChange={ (next) => {
								setAttributes( { borderSize: next } );
							} }
							min={ 0 }
							max={ 120 }
							beforeIcon="marker"
							afterIcon="editor-help"
						/>

						<RangeControl
							className="components-border-size-picker__custom-input"
							label={ __( 'Border Radius' ) }
							value={ borderRadius || 0 }
							initialPosition={ 5 }
							onChange={ (next) => {
								setAttributes( { borderRadius: next } );
							} }
							min={ 0 }
							max={ 50 }
							beforeIcon="grid-view"
							afterIcon="marker"
						/>
					</PanelBody>

					{/*<PanelBody title={ __( 'Shadow' ) }  className="blocks-shadow-size" initialOpen={ false }>*/}

						{/*<RangeControl*/}
							{/*className="components-border-size-picker__custom-input"*/}
							{/*label={ __( 'Shadow Size' ) }*/}
							{/*value={ shadowSize || 0 }*/}
							{/*initialPosition={ 5 }*/}
							{/*onChange={ (next) => {*/}
								{/*setAttributes( { shadowSize: next } );*/}
							{/*} }*/}
							{/*min={ 0 }*/}
							{/*max={ 100 }*/}
							{/*beforeIcon="minus"*/}
							{/*afterIcon="plus"*/}
						{/*/>*/}

						{/*<RangeControl*/}
							{/*className="components-border-size-picker__custom-input"*/}
							{/*label={ __( 'Blur Radius' ) }*/}
							{/*value={ shadowBlurSize || 0 }*/}
							{/*initialPosition={ 5 }*/}
							{/*onChange={ (next) => {*/}
								{/*setAttributes( { shadowBlurSize: next } );*/}
							{/*} }*/}
							{/*min={ 0 }*/}
							{/*max={ 120 }*/}
							{/*beforeIcon="minus"*/}
							{/*afterIcon="plus"*/}
						{/*/>*/}


						{/*<RangeControl*/}
							{/*className="components-border-size-picker__custom-input"*/}
							{/*label={ __( 'Horizontal Direction' ) }*/}
							{/*value={ shadowHorizontalSize || 0 }*/}
							{/*initialPosition={ 5 }*/}
							{/*onChange={ (next) => {*/}
								{/*setAttributes( { shadowHorizontalSize: next } );*/}
							{/*} }*/}
							{/*min={ -100 }*/}
							{/*max={ 100 }*/}
							{/*beforeIcon="arrow-left"*/}
							{/*afterIcon="arrow-right"*/}
						{/*/>*/}


						{/*<RangeControl*/}
							{/*className="components-border-size-picker__custom-input"*/}
							{/*label={ __( 'Vertical Direction' ) }*/}
							{/*value={ shadowVerticalSize || 0 }*/}
							{/*initialPosition={ 5 }*/}
							{/*onChange={ (next) => {*/}
								{/*setAttributes( { shadowVerticalSize: next } );*/}
							{/*} }*/}
							{/*min={ -100 }*/}
							{/*max={ 100 }*/}
							{/*beforeIcon="arrow-up"*/}
							{/*afterIcon="arrow-down"*/}
						{/*/>*/}
					{/*</PanelBody>*/}
				</InspectorControls>

				<p style={pStyle} >
					<span style={ wrapperStyle }>
						<i className={icon_prefix + ' fa-' + icon } style={iconStyle}></i>
					</span>
				</p>
			</Fragment>
		)
	}

	suggestIcon( query, populateResults  ){
		if ( query.length < 1 || this.props.faIconsList.length < 0 ) {
			return;
		}

		const results = _.values( this.props.faIconsList ).filter( ( el ) => {
				return el.name.indexOf( query ) !== -1
		} );

		populateResults( results.map( ( icon ) => ( icon.name ) ) )
	}
}

FontAwesomeEditor = compose ( [
	withSelect( ( select, props ) => {
		const result = select( 'obfx/blocks' ).getFaIconsList()

		return {
			faIconsList: result
		}
	} ),
	withColors( 'backgroundColor', { textColor: 'color', borderColor: 'color', shadowColor: 'color' } ),
])( FontAwesomeEditor );

export default withColorContext(FontAwesomeEditor)

