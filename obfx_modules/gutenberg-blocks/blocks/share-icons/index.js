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
	RichText,
	InspectorControls
} = wp.editor;

const {
	Fragment
} = wp.element;

const share_icons = {
	facebook: {
		type: 'boolean',
		label: __('Facebook'),
		default: true
	},
	twitter: {
		type: 'boolean',
		label: __('Twitter'),
		default: false
	},
	instagram: {
		type: 'boolean',
		label: __('Instagram'),
		default: true
	}
};

// @TODO I reallly don't like this so I'll rewrite it via 1 attribute with childrens

registerBlockType('orbitfox/share-icons', {
	title: __('Share Icons'),
	icon: 'info',
	category: 'common',
	keywords: [
		'share',
		'orbitfox'
	],

	attributes: share_icons2,

	edit(props) {
		const {
			attributes,
			setAttributes
		} = props

		const html = Object.keys( share_icons ).map( (i, social) => {
			const config = share_icons[social];

			return (<div key={social}>
				<label key="label" htmlFor={social}>{ __('Show ')  }</label>,
				<FormToggle
					key="toggle"
					checked={1 === 'open'}
					onChange={( newValue ) => {
						return 1;
					}}
					id={social}
				/>
			</div>);
		})
		
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<div className={'obfx-social-icons'}>
							{html}
						</div>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	},
	save() {
		return null;
	},
});