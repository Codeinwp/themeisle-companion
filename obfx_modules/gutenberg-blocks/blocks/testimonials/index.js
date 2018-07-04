/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;


const {
	Button,
	TextControl,
	Spinner
} = wp.components;
const {
	MediaUpload,
	RichText,
} = wp.editor;

// @TODO create a block which will handle a group of testimonials which could be a carusel slider
// or a simple group of testimonials.

// registerBlockType( 'orbitfox/testimonials', {
//   title: __( 'Testimonials' ),
//   icon: 'info',
//   category: 'layout',
//   keywords: [
//     'testimonials',
//     'orbitfox'
//   ],
//
//   edit() {return null;},
//   save() {return null;},
// } );

registerBlockType( 'orbitfox/testimonial', {
	title: __( 'Testimonial' ),
	icon: 'info',
	category: 'layout',
	keywords: [
		'testimonial',
		'orbitfox'
	],
	attributes: {
		href: {
			type: 'url',
		},
		name: {
			type: 'string',
		},
		title: {
			type: 'string',
		},
		text: {
			type: 'array',
			source: 'children',
			selector: '.obfx-testimonial-text',
			default: __( 'Testimonial text to make a great webpage.' ),
		},
		mediaID: {
			type: 'number',
		},
		mediaURL: {
			type: 'string',
			source: 'attribute',
			selector: '.testimonial-image',
			attribute: 'data-src',
		},
	},

	edit(props) {
		const {
			isSelected,
			editable,
			setState,
			className,
			setAttributes
		} = props;

		const {
			href,
			name,
			title,
			text,
			mediaID,
			mediaURL,
		} = props.attributes;

		return (<div>
			<MediaUpload
				onSelect={ ( media ) => setAttributes( { mediaURL: media.url, mediaID: media.id } ) }
				type={'image'}
				value={mediaID}
				render={ function( obj ) {
					return <Button
						className={ mediaID ? '' : 'button button-large' }
						onClick={ obj.open } >
						{
							mediaID ? <div className="testimonial-image" style={{
								backgroundImage: `url(${mediaURL})`,
								backgroundSize: 'cover',
								width: '200px',
								height: '200px',
							}}></div> : __( 'Upload Image' )
						}
					</Button>
				} }
			/>

			<RichText
				tagName={ 'h3' }
				value={ name }
				placeholder={ __('Author') }
				onChange={ (name) => setAttributes( { name: name } ) }
				keepPlaceholderOnFocus
			/>

			<RichText
				tagName={ 'h4' }
				value={ title }
				placeholder={ __('Title') }
				onChange={ (title) => setAttributes( { title: title } ) }
				keepPlaceholderOnFocus
			/>

			<RichText
				tagName={'p'}
				value={ text }
				className={ 'obfx-testimonial-text' }
				onChange={ (text) => setAttributes( { text: text } ) }
				keepPlaceholderOnFocus
			/>
		</div>);
	},
	save(props) {
		const {
			isSelected,
			editable,
			setState,
			className,
			setAttributes
		} = props;

		const {
			href,
			name,
			title,
			text,
			mediaID,
			mediaURL,
		} = props.attributes;

		return (<div>
			{ mediaID ? <div className="testimonial-image" style={{
				backgroundImage: `url(${mediaURL})`,
				backgroundSize: 'cover',
				width: '200px',
				height: 'auto',
			}} data-src={mediaURL}></div> : null }
			{ name ? <h3>{name}</h3> : null }
			{ title ? <h4>{title}</h4> : null }
			{ text ? <p className="obfx-testimonial-text">{text}</p> : null }
		</div>);
	},
} );