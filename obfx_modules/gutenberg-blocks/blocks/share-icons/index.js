/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/share-icons', {
  title: __( 'Share Icons' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'share',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );