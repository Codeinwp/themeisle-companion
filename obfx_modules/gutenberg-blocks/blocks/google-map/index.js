/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/google-map', {
  title: __( 'Google Map' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'map',
    'google',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );