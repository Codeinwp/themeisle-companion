/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

import PluginCardEditor from './Editor';

registerBlockType('orbitfox/plugin-cards', {
	title: __('Plugin Card'),
	icon: 'index-card',
	category: 'layout',
	keywords: [
		'plugin',
		'card',
		'orbitfox'
	],

	attributes: {
		slug: {
			type: 'string',
		},
	},

	edit: PluginCardEditor,
	save() {
		return null;
	},
});