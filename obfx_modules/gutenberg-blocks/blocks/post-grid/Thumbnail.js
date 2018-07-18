/**
 * WordPress Dependencies
 */
const {__} = wp.i18n;

const {Spinner} = wp.components;

const {withSelect} = wp.data;

const {Component} = wp.element;

class Thumbnail extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const { url, alt, id, thumbnail } = this.props;

		const img = thumbnail ? <img src={ thumbnail } alt={ alt } data-id={ id } /> : <Spinner />;

		return (
			<div className="post-thumbnail" >
				{ img }
			</div>
		);
	}
}

export default withSelect( ( select, ownProps ) => {
	const { getMedia } = select( 'core' );
	const { id } = ownProps;
	const image = id ? getMedia( id ) : null;
	const size = 'thumbnail';

	const thumbnail = image ? image.media_details.sizes[size].source_url : null;

	return image ? {
		url: image.source_url,
		thumbnail: thumbnail,
		alt: image.alt_text,
	} : {
		url: null,
		alt: null,
	};
} )( Thumbnail );
