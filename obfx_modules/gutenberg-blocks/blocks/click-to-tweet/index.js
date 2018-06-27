/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;
const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/click-to-tweet', {
  title: __( 'Click To Tweet' ),
  icon: 'index-card',
  category: 'common',
  keywords: [
    'twitter',
    'tweet',
    'orbitfox'
  ],
  attributes: {
    content: {
      type: 'array',
      source: 'children',
      selector: 'p',
      default: [],
    },
  },
  edit() {
    return (
      <blockquote>
        { this.props.children }
      </blockquote>
    );
  },
  save(props) {
    return null;
  },
} );