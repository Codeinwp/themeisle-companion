/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
	registerBlockType,
} = wp.blocks;

// @TODO I've tried to import this one from content-forms but I really don't like this
// Will try another way in the future.


// import ContactFormEditor from './Editor'
//
// registerBlockType('orbitfox/contact-form', {
// 	title: __('Contact Form'),
// 	icon: 'index-card',
// 	category: 'common',
// 	keywords: [
// 		__( 'contact' ),
// 		__( 'form' ),
// 		__( 'orbitfox' ),
// 	],
// 	edit: ContactFormEditor,
// 	save( props ) {
// 		const component = this
// 		const {attributes} = props
// 		const {fields} = attributes
// 		let fieldsEl = []
//
// 		if ( typeof attributes.uid === "undefined" ) {
// 			attributes.uid = props.id
// 		}
//
// 		_.each(fields, function (args, key) {
//
// 			fieldsEl.push(<p
// 				key={key}
// 				className="content-form-field-label"
// 				data-field_id={args.field_id}
// 				data-label={args.label}
// 				data-field_type={args.type}
// 				data-requirement={args.requirement ? "true": "false"}
// 			/>)
// 		})
//
// 		return (<div key="content-form-fields" className={"content-form-fields content-form-" + form} data-uid={props.id}>
// 			{fieldsEl}
// 		</div>)
// 	}
//
// });

