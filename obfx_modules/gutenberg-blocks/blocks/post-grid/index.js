/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/posts-grid', {
  title: __( 'Posts Grid' ),
  icon: 'info',
  category: 'layout',
  keywords: [
    'posts',
    'grid',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );