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
	attributes: {
		postType: {type: "string"},
		taxonomy: {type: "string"},
		categories: {type: "string"},
		className: {type: "string"},
		postsToShow: {type: "number", default: 5},
		displayFeaturedImage: {type: "boolean", default: false},
		displayPostDate: {type: "boolean", default: false},
		postLayout: {type: "string", default: "list"},
		columns: {type: "number", default: 3},
		align: {type: "string", default: "center"},
		order: {type: "string", default: "desc"},
		orderBy: {type: "string", default: "date"}
	},

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