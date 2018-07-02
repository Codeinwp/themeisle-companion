const {Component} = wp.element;
const {withSelect} = wp.data;

const {__} = wp.i18n;

const {RichText} = wp.editor;

export class AboutAuthorEditor extends Component {
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
			postAuthor,
			authors,
			customLabel,
		} = attributes;

		// @TODO this is wip; We need to build a nice preview of this block

		return (<section className="blocks-single-author">
			<h2>This author</h2>
			<p>
				<img src="http://2.gravatar.com/avatar/2103d6c58b7f6b25a98b25fcaafb2521?s=96&d=mm&r=g" alt=""/>
				A possible short description here
			</p>

			<RichText
				tagName="p"
				multiline="false"
				placeholder={__('Custom ;abe;')}
				value={customLabel}
				formattingControls={[]}
				onChange={(newValue) => {
					setAttributes({customLabel: newValue})
				}}
				keepPlaceholderOnFocus
			/>
		</section>);
	}
}

export default withSelect((select) => {
	//const store = select( 'core/editor' );
	const post = select('core/editor').getCurrentPost();

	// @TODO maybe fetch all the authors and pluck the current one.
	// we might need the ability to change it.
	return {
		postAuthor: select('core/editor').getEditedPostAttribute('author'),
		authors: select('core').getAuthors(),
		post: post,
	};
})(AboutAuthorEditor);

