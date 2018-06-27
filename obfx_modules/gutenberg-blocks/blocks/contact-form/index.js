/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/contact-form', {
  title: __( 'Contact Form' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'contact',
    'form',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );