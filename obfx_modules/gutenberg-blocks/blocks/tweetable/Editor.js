const {Component} = wp.element;
const {withSelect} = wp.data;

const {__} = wp.i18n;

const {RichText} = wp.editor;

export class ClickToTweetEditor extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		const {
			isSelected,
			setAttributes,
			attributes
		} = this.props;

		const {
			quote,
			buttonText,
			permalink,
			via,
		} = attributes;

		return (<blockquote>
			<RichText
				tagName="p"
				multiline="false"
				placeholder={__('What should we tweet?')}
				value={quote}
				formattingControls={[]}
				onChange={(newValue) => setAttributes({quote: newValue})}
				keepPlaceholderOnFocus
			/>

			<RichText
				tagName="span"
				placeholder={__('Tweet this!')}
				value={buttonText ? buttonText : __('Tweet this!')}
				formattingControls={[]}
				onChange={(newValue) => setAttributes({buttonText: newValue})}
				keepPlaceholderOnFocus
			/>
		</blockquote>);
	}
}

export default withSelect((select) => {
	const {getPermalink} = select('core/editor');

	return {
		permalink: getPermalink(),
	};
})(ClickToTweetEditor);

