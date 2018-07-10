/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {RichText} = wp.editor;
const {Fragment} = wp.element;

registerBlockType('orbitfox/notice', {
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
		{ name: 'info', label: __( 'Info' ), isDefault: true },
		{ name: 'warning', label: __( 'Warning' ) },
		{ name: 'error', label: __( 'Error' ) },
	],

	edit: props => {
		const {attributes: { content, title}, className, setAttributes} = props

		// @TODO Add a toolbar control and create a custom svg icon for this block
		return (
			<Fragment>

				<RichText
					tagName="p"
					value={title}
					className='obfx-block-notice__title'
					onChange={title => setAttributes({title})}
				/>

				<RichText
					tagName="p"
					placeholder={__('Your tip/warning content')}
					value={content}
					className='obfx-block-notice__content'
					onChange={content => setAttributes({content})}
					keepPlaceholderOnFocus="true"
				/>

			</Fragment>
		)
	},
	save: props => {
		const { title, content} = props.attributes

		return (
			<div className={`obfx-block-notice`}>
				<p className='obfx-block-notice__title'>{title}</p>
				<p className='obfx-block-notice__content'>{content}</p>
			</div>
		)
	},
});