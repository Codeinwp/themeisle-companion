/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
	FormToggle,
	PanelBody
} = wp.components;

const {
	InspectorControls
} = wp.editor;

const {
	Fragment
} = wp.element;

import social_list from './social_list'

// default block attributes
const block_attributes = {
	output: {
		type: 'string',
		default: 'icons'
	},
	layout: {
		type: 'string',
		default: 'inline'
	}
}

// generate block attributes for each social platform
for (let key in social_list) {
	const attribute_key = 'show_' + key

	block_attributes[attribute_key] = {
		type: 'boolean',
		default: social_list[key]['default']
	}
}

registerBlockType('orbitfox/share-icons', {
	title: __('Share Icons'),
	icon: 'info',
	category: 'common',
	keywords: [
		'share',
		'orbitfox'
	],

	attributes: block_attributes,

	edit(props) {
		const {
			attributes,
			setAttributes,
			clientId
		} = props
		const {layout, output} = attributes

		const get_icon_markup = function (output, name, label) {
			switch (output) {
				case 'text_and_icons':
					return <span>{label} <i className={'fa fa-' + name}></i></span>
				case 'icons_and_text':
					return <span><i className={'fa fa-' + name}></i>{label}</span>
				case 'text':
					return <span>{label}</span>
				case 'icon':
				default :
					return <i className={'fa fa-' + name}></i>
			}
		}

		const checkboxes_html = [];
		const preview_html = [];

		Object.keys(social_list).map((social_name, i) => {
			const config = social_list[social_name];
			const checkbox_id = 'obfx-shareicon-checkbox-' + clientId + '-' + social_name
			const attribute_key = 'show_' + social_name
			let checked = false

			if (typeof attributes[attribute_key] !== "undefined") {
				checked = attributes[attribute_key]
			}

			checkboxes_html.push(<div className="components-panel__row" key={checkbox_id}>
				<label htmlFor={checkbox_id}>{__('Show ') + config.label}</label>
				<FormToggle
					key="toggle"
					checked={checked}
					onChange={(event) => {
						const toSaveObj = {}
						toSaveObj[attribute_key] = event.target.checked
						console.log(toSaveObj)
						setAttributes(toSaveObj)
					}}
					id={checkbox_id}
				/>
			</div>);

			if (typeof attributes[attribute_key] !== "undefined" && attributes[attribute_key]) {
				preview_html.push(<div key={'preview-icon-' + social_name}>
				<span>
					{get_icon_markup(output, social_name, config.label)}
				</span>
				</div>)
			}
		})

		return (<Fragment>
				<InspectorControls>
					<PanelBody>
						{checkboxes_html}
					</PanelBody>
					<PanelBody className="components-panel__row">
						<p><label htmlFor={'social-icons-output-' + id}>{__('Output')}</label></p>
						<select name={'social-icons-output-' + clientId} id={'social-icons-output-' + clientId} onChange={(e) => {
							setAttributes({output: e.target.value})
						}}>
							<option value="icons">{__('Icons')}</option>
							<option value="text">{__('Text')}</option>
							<option value="icons_and_text">{__('Icons and Text')}</option>
							<option value="text_and_icons">{__('Text and Icons')}</option>
						</select>
					</PanelBody>
					<PanelBody className="components-panel__row"  title={ __( 'Layout' ) }>
						<select name={'social-icons-layout-' + clientId} id={'social-icons-layout-' + clientId} onChange={(e) => {
							setAttributes({layout: e.target.value})
						}}>
							<option value="inline">{__('Inline')}</option>
							<option value="stacked">{__('Stacked')}</option>
						</select>
					</PanelBody>
				</InspectorControls>
				<div className={ layout + "preview-box" }>
					{preview_html}
				</div>
			</Fragment>
		);
	},
	save() {
		return null;
	},
});