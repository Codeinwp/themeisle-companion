const {Component} = wp.element;
const {withSelect} = wp.data;

const {__} = wp.i18n;

const {RichText} = wp.editor;
const {Fragment} = wp.element;

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
			customLabel,
		} = attributes;

		// retrieve the post's author defaults
		let postAuthor = this.props.postAuthor
		let author_details = this.props.authors.filter((o) => {
			return o.id === postAuthor
		} );

		if ( typeof author_details[0] === "undefined" ) {
			return (<div></div>);
		}

		author_details = author_details[0]

		return (<section className="obfx-author-box">
			<h3>{author_details.name}</h3>
			<img src={author_details.avatar_urls['96']} alt=""/>
			<p>{author_details.description}</p>
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

