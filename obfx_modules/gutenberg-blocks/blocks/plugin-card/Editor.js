const {Component} = wp.element;
const {withSelect} = wp.data;

const {__} = wp.i18n;

const {RichText} = wp.editor;

export default class PluginCardEditor extends Component {
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
			slug,
		} = attributes;

		return (<section className="blocks-plugin-card">
			<RichText
				tagName="p"
				placeholder={__('Plugin name')}
				value={customLabel}
				formattingControls={[]}
				onChange={(newValue) => {
					setAttributes({slug: newValue})
				}}
				keepPlaceholderOnFocus
			/>
		</section>);
	}

}

