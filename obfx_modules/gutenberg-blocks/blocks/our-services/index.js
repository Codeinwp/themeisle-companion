/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/our-services', {
  title: __( 'Our Services' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'services',
    'orbitfox'
  ],
  edit() {return null;},
  save() {return null;},
} );