/**
 * @TODO This block is at a WIP Point.
 * I'll disable it for the moment.
 */


/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {registerBlockType} = wp.blocks;

const{
	ToggleControl,
	PanelBody
} = wp.components;

const {
	InspectorControls,
	RichText,
	InnerBlocks
} = wp.editor;

import ContactFormEditor from './Editor'

registerBlockType('orbitfox/contact-form', {
	title: __('Contact Form'),
	icon: 'index-card',
	category: 'common',
	keywords: [
		__( 'contact' ),
		__( 'form' ),
		__( 'orbitfox' ),
	],
	attributes: {
		postTypeID: {
			type: 'string'
		},
		email: {
			type: 'string',
			source: 'meta',
			meta: 'email'
		},
	},
	edit: ContactFormEditor,

	save( { attributes, id } ) {

		return (
			<form className={ `obfx-contact-form` } method="post" name={'contact-form-' + id} id={'contact-form-' + id} >
				<InnerBlocks.Content />
			</form>
		);
	}
});

registerBlockType('orbitfox/contact-form-text', {
	title: __('Text Field'),
	icon: 'index-card',
	category: 'layout',
	keywords: [
		__( 'text' ),
		__( 'field' ),
		__( 'orbitfox' ),
	],

	attributes: {
		slug: {
			type: 'string'
		},

		label: {
			type: 'string'
		},
		placeholder: {
			type: 'string'
		},
		required: {
			type: 'boolean',
			default: false
		}
	},

	parent: [ 'orbitfox/contact-form' ],

	edit: ( props ) => {
		const {id, attributes, setAttributes} = props;
		const { label, placeholder, required, slug } = attributes;
		console.log( props )
		return (<div>
			<RichText
				tagName={ 'p' }
				value={ label }
				placeholder={ __('Label') }
				onChange={ ( newValue ) => setAttributes( { label: newValue } ) }
				keepPlaceholderOnFocus
			/>

			<RichText
				tagName={ 'label' }
				value={ placeholder }
				placeholder={ __('Placeholder') }
				onChange={ ( newValue ) => setAttributes( { placeholder: newValue } ) }
				keepPlaceholderOnFocus
			/>
			<input type="text" id={id} name={id} disabled="disabled" />
			<InspectorControls>
				<PanelBody>
					<RichText
						tagName={ 'p' }
						value={ slug }
						placeholder={ __('Field slug') }
						onChange={ ( newValue ) => setAttributes( { slug: newValue } ) }
						keepPlaceholderOnFocus
					/>
					<ToggleControl
						label={ __( 'Is required?' ) }
						checked={ required }
						onChange={ ( newValue ) => setAttributes( { required: newValue } ) }
					/>
				</PanelBody>
			</InspectorControls>
		</div>);
	},

	save( props ) {
		const { attributes } = props;
		const { label, slug } = attributes;

		return (<fieldset className={ `obfx-contact-forms-text-field` }>
				<label htmlFor={slug}>{label}</label>
				<input type="text" id={slug} name={slug} />
			</fieldset>);
	},

	// save( props ) {
	// 	const component = this
	// 	const {attributes} = props
	// 	const {fields} = attributes
	// 	let fieldsEl = []
	//
	// 	if ( typeof attributes.uid === "undefined" ) {
	// 		attributes.uid = props.id
	// 	}
	//
	// 	_.each(fields, function (args, key) {
	// 		fieldsEl.push(<p
	// 			key={key}
	// 			className="content-form-field-label"
	// 			data-field_id={args.field_id}
	// 			data-label={args.label}
	// 			data-field_type={args.type}
	// 			data-requirement={args.requirement ? "true": "false"}
	// 		/>)
	// 	})
	//
	// 	return (<div key="content-form-fields" className={"content-form-fields content-form-" + form} data-uid={props.id}>
	// 		{fieldsEl}
	// 	</div>)
	// }

});

registerBlockType('orbitfox/contact-form-textarea', {
	title: __('Textarea Field'),
	icon: 'index-card',
	category: 'layout',
	keywords: [
		__( 'textarea' ),
		__( 'field' ),
		__( 'orbitfox' ),
	],

	attributes: {
		slug: {
			type: 'string'
		},

		label: {
			type: 'string'
		},
		placeholder: {
			type: 'string'
		},
		required: {
			type: 'boolean',
			default: false
		}
	},

	parent: [ 'orbitfox/contact-form' ],

	edit: ( {id, attributes, setAttributes} ) => {
		const { label, placeholder, required, slug } = attributes;

		return (<div>
			<RichText
				tagName={ 'p' }
				value={ label }
				placeholder={ __('Label') }
				onChange={ ( newValue ) => setAttributes( { label: newValue } ) }
				keepPlaceholderOnFocus
			/>

			<RichText
				tagName={ 'label' }
				value={ placeholder }
				placeholder={ __('Placeholder') }
				onChange={ ( newValue ) => setAttributes( { placeholder: newValue } ) }
				keepPlaceholderOnFocus
			/>
			<InspectorControls>
				<PanelBody>
					<RichText
						tagName={ 'p' }
						value={ slug }
						placeholder={ __('Field slug') }
						onChange={ ( newValue ) => setAttributes( { slug: newValue } ) }
						keepPlaceholderOnFocus
					/>
					<ToggleControl
						label={ __( 'Is required?' ) }
						checked={ required }
						onChange={ ( newValue ) => setAttributes( { required: newValue } ) }
					/>
				</PanelBody>
			</InspectorControls>
		</div>);
	},

	save( { attributes } ) {
		const { label, slug } = attributes;

		return (
			<fieldset className={ `obfx-contact-forms-textarea-field` }>
				<label htmlFor={slug}>{label}</label>
				<textarea id={slug} name={slug} />
			</fieldset>
		);
	},
});

registerBlockType('orbitfox/contact-form-submit', {
	title: __('Submit Button'),
	icon: 'button',
	category: 'layout',
	keywords: [
		__( 'submit' ),
		__( 'button' ),
		__( 'orbitfox' ),
	],

	attributes: {
		label: {
			type: 'string'
		}
	},

	parent: [ 'orbitfox/contact-form' ],

	edit: ( {id, attributes, setAttributes} ) => {
		const { label } = attributes;

		return (<div>
			<RichText
				tagName={ 'p' }
				value={ label }
				placeholder={ __('Label') }
				onChange={ ( newValue ) => setAttributes( { label: newValue } ) }
				keepPlaceholderOnFocus
			/>
		</div>);
	},

	save( { attributes } ) {
		const { label } = attributes;

		return (
			<fieldset className={ `obfx-contact-forms-submit-button` }>
				<button>{label}</button>
			</fieldset>
		);
	},
});

