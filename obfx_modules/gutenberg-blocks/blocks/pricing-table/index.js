/**
 * WordPress dependencies...
 */
const {
	__
} = wp.i18n;

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
	icon: 'info',
	category: 'layout',
	keywords: [
		'pricing',
		'table',
		'orbitfox'
	],

	attributes: {
		panels: {
			type: 'number',
			default: 3,
		},
	},

	edit: PriceTableEditor,

	save({attributes}) {
		const {panels} = attributes;

		return (
			<div className={`wp-block-orbitfox-pricing-table has-${ panels }-panels`}>
				<InnerBlocks.Content/>
			</div>
		);
	},
});

registerBlockType('orbitfox/pricing-box', {
	title: __('Pricing Box'),
	icon: 'info',
	category: 'layout',
	parent: 'orbitfox/pricing-table',
	keywords: [
		'pricing',
		'box',
		'orbitfox'
	],

	edit({attributes, setAttributes, className}) {
		const {panels} = attributes;
		const classes = classnames(className, `has-${ panels }-panels`);

		return (
			<Fragment>
				<div className={classes}>
					<div>test test test</div>
				</div>
			</Fragment>
		);
	},

	save() {
		return null;
	},
});