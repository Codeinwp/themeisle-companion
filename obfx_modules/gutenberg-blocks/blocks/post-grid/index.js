/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

import PostsGridEdit from './Editor';

registerBlockType('orbitfox/posts-grid', {
	title: __('Posts Grid'),
	icon: 'info',
	category: 'layout',
	keywords: [
		'posts',
		'grid',
		'orbitfox'
	],
	getEditWrapperProps(attributes) {
		const {align} = attributes;
		if ('left' === align || 'right' === align || 'wide' === align || 'full' === align) {
			return {'data-align': align};
		}
	},
	edit: PostsGridEdit,
	save() {
		return null;
	},
});