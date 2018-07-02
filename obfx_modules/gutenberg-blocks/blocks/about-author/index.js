/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
  registerBlockType
} = wp.blocks;

import AboutAuthorEditor from './Editor';

registerBlockType( 'orbitfox/about-author', {
  title: __( 'About Author' ),
  icon: 'index-card',
  category: 'common',
  keywords: [
    'about',
    'author',
    'orbitfox'
  ],

  attributes: {
    customLabel: {
      type: 'string',
    },
  },

  edit: AboutAuthorEditor,
  save() {return null;},
} );