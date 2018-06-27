/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/about-author', {
  title: __( 'About Author' ),
  icon: 'index-card',
  category: 'common',
  keywords: [
    'about',
    'author',
    'orbitfox'
  ],
  edit() {return null;},
  save() {return null;},
} );