/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/notice', {
  title: __( 'Notice' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'notice',
    'info',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );