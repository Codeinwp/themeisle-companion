/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

import classnames from 'classnames';
import PriceTableEditor from './Editor'

const {
	registerBlockType
} = wp.blocks;

const {
	InnerBlocks,
} = wp.editor;

const {
	Fragment
} = wp.element;

/**
 * Internal dependencies
 */
import './style.scss';

registerBlockType('orbitfox/pricing-table', {
	title: __('Pricing Table'),
	icon: 'slides',
	category: 'layout',
	keywords: [
		'pricing',
		'table',
		'orbitfox'
	],

	attributes: {
		columns: {
			type: 'number',
			default: 3,
		},
	},

	edit: PriceTableEditor,

	save({attributes}) {
		const {columns} = attributes;

		return (
			<div className={`wp-block-orbitfox-pricing-table has-${ columns }-columns`}>
				<InnerBlocks.Content/>
			</div>
		);
	},
});