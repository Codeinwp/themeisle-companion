/**
 * WordPress dependencies.
 */
const {__} = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

import ChartEditor from './Editor';

registerBlockType('orbitfox/chart-pie', {
	title: __('Pie Chart'),
	icon: 'chart-pie',
	category: 'common',
	keywords: [
		__('pie'),
		__('chart'),
		__('orbitfox'),
	],
	attributes: {
		blockID: {
			type: 'string'
		},
		chartName: {
			type: 'string',
			default: 'Pie Chart',
		},
		backgroundColor: {
			type: 'string',
			default: '#e4e7e1'
		},
		dataLabels: {
			type: 'array',
			default: [ 'Blue', 'Red']
		},

		dataValues: {
			type: 'array',
			default: [ 22, 78]
		},

		dataColors: {
			type: 'array',
			default: [ 'red', 'blue']
		}
		// data: {
		// 	type: 'array',
		// 	source: 'attribute',
		// 	selector: 'span.data-holder',
		// 	default: [
		// 		{label: 'Red', value: 23, color: '#cf2e2e'},
		// 		{label: 'Blue', value: 77, color: '#0693e3'}
		// 	],
		// },
	},

	edit: ChartEditor,

	save() { return null; },
});