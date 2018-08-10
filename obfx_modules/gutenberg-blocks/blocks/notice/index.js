/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const { RichText } = wp.editor;

const { Dashicon } = wp.components;

/**
 * Internal dependencies
 */
import './style.scss';
import './editor.scss';

registerBlockType( 'orbitfox/notice', {
	title: __('Notice'),
	icon: 'info',
	category: 'common',
	keywords: [
		'notice',
		'info'
	],
	attributes: {
		title: {
			source: 'text',
			type: 'string',
			selector: '.obfx-block-notice__title',
			default: 'Info',
		},
		content: {
			type: 'array',
			source: 'children',
			selector: '.obfx-block-notice__content',
		},
	},

	styles: [
		{ name: 'sucess', label: __( 'Success' ), isDefault: true },
		{ name: 'info', label: __( 'Info' ) },
		{ name: 'warning', label: __( 'Warning' ) },
		{ name: 'error', label: __( 'Error' ) },
	],

	edit: props => {
		let icon = <Dashicon icon='yes' size="28" />;
		if ( props.attributes.className && props.attributes.className.includes( 'is-style-info') ) {
			icon = <Dashicon icon='info' size="24" />;
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-warning') ) {
			icon = <Dashicon icon='warning' size="24" />;
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-error') ) {
			icon = <Dashicon icon='no' size="26" />;
		}
		return (
			<div className={ `obfx-block-notice ${ props.attributes.className ? props.attributes.className : '' }` }>
				{ icon }
				<RichText
					tagName="p"
					placeholder={ __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ) }
					value={ props.attributes.content }
					className="obfx-notice"
					onChange={ content => props.setAttributes( { content } ) }
					keepPlaceholderOnFocus="true"
				/>
			</div>
		)
	},
	save: props => {
		let icon = <Dashicon icon='yes' size="28" />;
		if ( props.attributes.className && props.attributes.className.includes( 'is-style-info') ) {
			icon = <Dashicon icon='info' size="24" />;
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-warning') ) {
			icon = <Dashicon icon='warning' size="24" />;
		} else if ( props.attributes.className && props.attributes.className.includes( 'is-style-error') ) {
			icon = <Dashicon icon='no' size="26" />;
		}
		return (
			<div className={ `obfx-block-notice ${ props.attributes.className ? props.attributes.className : '' }` }>
				{ icon }<p className="obfx-notice">{ props.attributes.content }</p>
			</div>
		)
	},
});