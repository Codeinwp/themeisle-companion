/**
 * WordPress dependencies...
 */
const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

import GoogleMapEditor from './Editor.js';

registerBlockType( 'orbitfox/google-map', {
	title: __( 'Google Map' ),
	icon: 'admin-site',
	category: 'embed',
	keywords: [
		'map',
		'google',
		'orbitfox'
	],
	supports: {
		html: false
	},

	edit: GoogleMapEditor,

	save() {
		return null;
	},
});