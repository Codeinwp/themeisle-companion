/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/testimonials', {
  title: __( 'Testimonials' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'testimonials',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );