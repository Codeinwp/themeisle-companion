import memoize from "memize";
import { stringify } from 'querystringify'
/**
 * WordPress dependencies
 */
const {Component, Fragment} = wp.element;
const {Placeholder, Spinner, withAPIData, FormToggle, PanelBody} = wp.components;
const {__, sprintf} = wp.i18n;

const { compose } = wp.compose;
const { withSelect, withDispatch } = wp.data;
const { apiRequest } = wp;
const {
	Editable,
	BlockEdit
} = wp.blocks;

const {
	InnerBlocks,
	RichText,
	InspectorControls
} = wp.editor;

const fieldStyle = {
	width: '40%',
	display: 'inline-block'
}

const fieldStyleR = {
	width: '10%',
	display: 'inline-block',
	textAlign: 'right'
}

export class ContactFormEditor extends Component {
	constructor() {
		super(...arguments);
		this.config = {
			id: 'contact',
			icon: 'eicon-align-left',
			title: __('Contact Form'),
			fields: {
				name: {
					type: 'text',
					label: __('Name'),
					default: __('Name'),
					placeholder: __('Your Name'),
					require: 'required'
				},
				email: {
					type: 'email',
					label: __('Email'),
					default: __('Email'),
					placeholder: __('Email address'),
					require: 'required'
				},
				phone: {
					type: 'number',
					label: __('Phone'),
					default: __('Phone'),
					placeholder: __('Phone Nr'),
					require: 'optional'
				},
				message: {
					type: 'textarea',
					label: __('Message'),
					default: __('Message'),
					placeholder: __('Your message'),
					require: 'required'
				}
			},

			controls: {
				to_send_email: {
					type: 'text',
					label: __('Send to'),
					description: __('Where should we send the email?'),
					default: ''
				},
				submit_label: {
					type: 'text',
					label: __('Submit'),
					default: __('Submit'),
					description: __('The Call To Action label')
				}
			}
		};
	}

	render() {
		const { attributes, setAttributes, updatePostMeta } = this.props
		const {
			email
		} = attributes
		const placeholderEl = <Placeholder key="form-loader" icon="admin-post" label={__('Form')}>
			<Spinner/>
		</Placeholder>
		let controlsEl = []
		let fieldsEl = []

		console.log( attributes )

		return (
			<div>
				<InnerBlocks
					templateLock="all"
					allowedBlocks={ [
						'core/heading',
						'core/paragraph',
						'orbitfox/contact-form-text',
						'orbitfox/contact-form-textarea',
						'orbitfox/contact-form-submit',
					] }
					template={[
						['core/heading', {
							content: 'Contact Form',
							align: "center",
							nodeName: "H3"
						}],
						['orbitfox/contact-form-text', {
							slug: 'name',
							label: 'Name',
							placeholder: "Name",
							required: true
						}],
						['orbitfox/contact-form-text', {
							slug: 'email',
							label: 'Email',
							placeholder: "email",
							required: true
						}],

						['core/paragraph', {
							content: 'Lorem ipsum dolor sit amet elit do.',
							align: "center"
						}],

						['orbitfox/contact-form-textarea', {
							slug: 'message',
							label: 'Message',
							placeholder: "message",
							required: true
						}],

						['orbitfox/contact-form-submit', {
							label: 'Send'
						}],
					]}
				/>
				<InspectorControls>
					<PanelBody>
						<RichText
							tagName={ 'p' }
							value={ email }
							placeholder={ __('Form email') }
							onChange={ ( newValue ) => {
								setAttributes( { email: newValue[0] } )
								updatePostMeta( 'email', newValue[0])
							} }
							keepPlaceholderOnFocus
						/>
					</PanelBody>
				</InspectorControls>
			</div>);

		// _.each(component.config.controls, function (args, key) {
		//
		// 	controlsEl.push(<div key={key}>
		// 		<BlockEdit key={'block-edit-custom-' + key}/>
		// 		<InspectorControls.TextControl
		// 			key={key}
		// 			label={args.label}
		// 			value={attributes[key] || ''}
		// 			onFocus={ f => {
		// 				console.log(f)
		// 			}}
		// 			onChange={value => {
		// 				let newValues = {}
		// 				newValues[key] = value
		// 				setAttributes(newValues)
		// 			}}
		// 		/>
		// 	</div>)
		// })
		//
		// _.each(fields, function (args, key) {
		// 	let val = ''
		// 	let field_id = args.field_id
		// 	let field_config = component.config.fields[field_id]
		// 	let isRequired = false
		//
		// 	if (typeof args.label === "object") {
		// 		val = args.label[0]
		// 	} else if (typeof args.label === "string") {
		// 		val = [args.label]
		// 	}
		//
		// 	if (typeof args.requirement !== "undefined") {
		// 		isRequired = args.requirement
		// 	}
		//
		// 	const focusOn = 'field-' + field_id
		//
		// 	fieldsEl.push(<div key={key} style={{border: '1px solid #333', padding: '5px', margin: '5px', borderRadius: '8px'}} className="content-form-field">
		//
		// 		<fieldset style={fieldStyle}>
		// 			<Editable
		// 				value={val}
		// 				tagName="label"
		// 				placeholder={__('Label for ') + field_id}
		// 				className="content-form-field-label"
		// 				onChange={(value) => {
		// 					let newValues = attributes.fields
		// 					newValues[key]['label'] = value
		// 					setAttributes({fields: newValues})
		// 					component.forceUpdate()
		// 				}}/>
		// 		</fieldset>
		//
		// 		<fieldset style={fieldStyle}>
		// 			<select
		// 				name="field-type-select"
		// 				value={typeof args['type'] !== "undefined" ? args['type'] : 'text'}
		// 				onChange={(event) => {
		// 					let newValues = attributes.fields
		// 					newValues[key]['type'] = event.target.selected
		// 					setAttributes({fields: newValues})
		// 					component.forceUpdate()
		// 				}}>
		// 				<option value="text" key="text">text</option>
		// 				<option value="textarea" key="textarea">textarea</option>
		// 				<option value="password" key="password">password</option>
		// 				<option value="email" key="email">email</option>
		// 				<option value="number" key="number">number</option>
		// 			</select>
		// 		</fieldset>
		//
		// 		<fieldset style={fieldStyleR}>
		// 			<FormToggle
		// 				checked={isRequired}
		// 				showHint={false}
		// 				onChange={(event) => {
		// 					let newValues = attributes.fields
		// 					newValues[key]['requirement'] = event.target.checked
		// 					setAttributes({fields: newValues})
		// 					component.forceUpdate()
		// 				}}
		// 			/>
		// 		</fieldset>
		//
		// 	</div>)
		// })
		//
		// return [
		// 	focus && (<InspectorControls key="inspector">
		// 		<h3>{__('Form Settings')}</h3>
		// 		{controlsEl}
		// 	</InspectorControls>),
		// 	(<div key="fields" className={'fields ' + component.props.className} data-uid={attributes.uid}>
		// 		{(fieldsEl === [])
		// 			? placeholderEl
		// 			: fieldsEl}
		// 	</div>)
		// ]
	}
}

export default compose(
	withDispatch( ( dispatch, {attributes} ) => {

		return {
			async updatePostMeta( key, value ) {
				//const result = dispatch( 'obfx/blocks' ).updatePostMeta( attributes.storePostId, key, value );
				//console.log( result )
				let newObj = {}
				newObj[key] = value

				const query = stringify( _.pick( {
					form_data: stringify( newObj ),
				}, ( value ) => ! _.isUndefined( value ) ) );

				const result = await apiRequest( { path: `/wp/v2/obfx_contact_form/${attributes.storePostId}?${query}`, method: 'POST' } );
				///console.log( result )
				//yield result
			}
		}
	}),
	withSelect( ( select, params ) => {
		const {attributes, setAttributes} = params

		if ( attributes.storePostId === 0 ) {
			const result = select( 'obfx/blocks' ).dispatchInit( params.id );
			setAttributes({storePostId: result.storePostId})
		} else if ( typeof attributes.storePostId !== "undefined" ) {
			const data = select( 'obfx/blocks' ).getPostMeta( attributes.storePostId );
			console.log( data )
		}

	} ),
)( ContactFormEditor );