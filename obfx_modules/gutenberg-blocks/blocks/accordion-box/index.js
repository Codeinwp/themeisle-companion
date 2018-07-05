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

registerBlockType('orbitfox/accordion-box', {
	title: __('Accordion Box'),
	icon: 'index-card',
	category: 'common',
	keywords: [
		'accordion',
		'collapsible',
		'orbitfox'
	],

	attributes: {
		label: {
			type: 'string',
		},
		text: {
			type: 'string',
		},
		openByDefault: {
			type: 'boolean',
			default: 0
		}
	},

	edit(props){
		const {
			setAttributes,
			attributes
		} = props;

		const {
			label,
			text,
			openByDefault
		} = attributes;

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<label key="label" htmlFor="opened-by-default">{ __('Opened by default ')  }</label>
						<FormToggle
							key="toggle"
							checked={ openByDefault }
							onChange={( event ) => {
								setAttributes({openByDefault: event.target.checked});
							}}
							id="opened-by-default"
						/>
					</PanelBody>
				</InspectorControls>

				<RichText
					tagName={'h3'}
					value={ label }
					className={ 'obfx-accordion-label' }
					onChange={ (label) => setAttributes( { label: label } ) }
					keepPlaceholderOnFocus
					placeholder={__('Title')}
				/>
				<RichText
					tagName={'p'}
					value={ text }
					className={ 'obfx-accordion-text' }
					onChange={ (text) => setAttributes( { text: text } ) }
					keepPlaceholderOnFocus
					placeholder={__('Collabsible text in here ...')}
				/>
			</Fragment>
		);
	},
	save(props){
		const {
			attributes,
			id
		} = props;

		const {
			label,
			text,
			openedByDefault
		} = attributes;

		checkedByDefault = 0;

		if ( openedByDefault ) {
			checkedByDefault = 'checked';
		}

		return (
			<div className="obfx-accordion">
				<label className="obfx-accordion-label" for={'accordion-id-' + id}>{label}</label>
				<input type="hidden" className="obfx-accordion-input" id={'accordion-id-' + id} checked={checkedByDefault} />
				<div  className="obfx-accordion-text">{text}</div>
			</div>
		);
	},
});