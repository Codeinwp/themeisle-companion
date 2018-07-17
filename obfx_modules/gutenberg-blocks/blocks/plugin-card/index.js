/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;
const {Component, Fragment, RawHTML} = wp.element;
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
	// @TODO we still need to decide which attributes we should handle
	attributes: {
		slug: {
			type: 'string',
		},
		plugin_name: {
			type: 'string',
		},
		plugin_author: {
			type: 'raw',
		},
		plugin_desc: {
			type: 'string',
		},
		plugin_version: {
			type: 'string',
		},
	},

	edit: PluginCardEditor,
	save( {attributes} ) {
		const {
			plugin_name,
			plugin_author,
			plugin_desc,
			plugin_version,
		} = attributes;

		// the author comes wrapped in <a href=> tag and we need to escape it
		const link = <div dangerouslySetInnerHTML={{__html: plugin_author}}></div>

		const output = (<div>
			<h3 className="plugin_name">{_.unescape(plugin_name) }</h3>
			<ul>
				<li><strong>Author:</strong><span>{link}</span></li>
				<li><strong>Description:</strong><span>{ plugin_desc }</span></li>
				<li><strong>Version:</strong><span>{plugin_version}</span></li>
			</ul>
		</div>);
		return output;
	},
});