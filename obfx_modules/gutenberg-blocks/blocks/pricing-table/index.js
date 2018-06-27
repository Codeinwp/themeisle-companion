/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

registerBlockType( 'orbitfox/pricing-table', {
  title: __( 'Pricing Table' ),
  icon: 'info',
  category: 'common',
  keywords: [
    'pricing',
    'table',
    'orbitfox'
  ],

  edit() {return null;},
  save() {return null;},
} );