/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType,
} = wp.blocks;

const {
	getColorClass,
} = wp.editor;

import FontAwesomeEditor from './Editor'

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
		icon_prefix: {
			type: 'string',
			default: 'fab'
		},
		icon: {
			type: 'string',
			default: 'wordpress'
		},
		size: {
			type: 'number',
			default: 32
		},
		align: {
			type: 'string',
			default: 'center'
		},
		textColor: {
			type: 'string'
		},
		backgroundColor: {
			type: 'string'
		},
		borderColor: {
			type: 'string'
		},
		shadowColor: {
			type: 'string'
		},
		customTextColor: {
			type: 'string'
		},
		customBackgroundColor: {
			type: 'string'
		},
		customBorderColor: {
			type: 'string'
		},
		customShadowColor: {
			type: 'string'
		},
		borderRadius: {
			type: 'string',
			default: 50
		},
		borderSize: {
			type: 'string',
			default: 0
		},
		borderStyle: {
			type: 'string'
		},
		innerSpaceSize: {
			type: 'string',
			default: 0
		},
		outerSpaceSize: {
			type: 'string',
			default: 0
		},

		shadowSize: {
			type: 'string',
			default: 0
		},
		shadowBlurSize: {
			type: 'string',
			default: 0
		},
		shadowHorizontalSize: {
			type: 'string',
			default: 0
		},
		shadowVerticalSize: {
			type: 'string',
			default: 0
		},
	},

	edit: FontAwesomeEditor,

	save( props ) {
		const { attributes } = props

		const {
			align,
			icon, icon_prefix, size,
			borderRadius, borderSize, borderStyle,
			innerSpaceSize, outerSpaceSize,
			textColor, backgroundColor, borderColor, shadowColor,
			customBackgroundColor, customTextColor, customBorderColor, customShadowColor,
			shadowHorizontalSize, shadowVerticalSize, shadowBlurSize, shadowSize
		} = attributes;

		const textClass = getColorClass( 'color', textColor );
		const backgroundClass = getColorClass( 'background-color', backgroundColor );
		const borderClass = getColorClass( 'border-color', borderColor );
		const shadowClass = getColorClass( 'shadow-color', shadowColor );

		const className = classnames( {
			'obfx-font-awesome-icon': true,
			'has-background': backgroundColor || customBackgroundColor,
			'has-color': textColor || customTextColor,
			'has-border-color': borderColor || customBorderColor,
			'has-shadow-color': shadowClass || customShadowColor,
			[ textClass ]: textClass,
			[ backgroundClass ]: backgroundClass,
			[ borderClass ]: borderClass,
			[ shadowClass ]: shadowClass,
		} );

		const pStyle = {
			textAlign: align,
		}

		const iconStyle = {
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
			backgroundColor: typeof backgroundColor !== "undefined" ? backgroundColor.value : 'transparent',
			borderColor: typeof borderColor !== "undefined" ? borderColor.value : 'transparent' ,
			borderRadius: borderRadius + '%',
			borderStyle: 'solid',
			borderWidth: borderSize + 'px',
			margin: outerSpaceSize + 'px',
			// boxShadow: shb
		};

		return <p style={pStyle} >
			<span style={ wrapperStyle } className={className}>
				<i className={icon_prefix + ' fa-' + icon } style={iconStyle}></i>
			</span>
		</p>
	},
});
