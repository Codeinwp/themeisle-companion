/**
 * WordPress dependencies.
 */
const {__} = wp.i18n;

const {
	registerBlockType,
	createBlock
} = wp.blocks;

const {RichText} = wp.editor;


import ChartEditor from './Editor';

registerBlockType('orbitfox/pie-chart', {
	title: __('Chart'),
	icon: 'twitter',
	category: 'common',
	keywords: [
		__('pie'),
		__('chart'),
		__('orbitfox'),
	],
	attributes: {
		chartName: {
			type: 'string',
			default: 'Pie Chart',
		},
		backgroundColor: {
			type: 'string',
			default: '#e4e7e1'
		},
		// data: {
		// 	type: 'array',
		// 	source: 'attribute',
		// 	selector: 'span.data-holder',
		// 	default: [
		// 		{label: 'Red', value: 23, color: '#cf2e2e'},
		// 		{label: 'Blue', value: 77, color: '#0693e3'}
		// 	],
		// },
		data: {
			source: 'query',
			selector: '.obfx-chart-pie',
			query: {
				labels: { source: 'attribute', attribute: 'data-label' },
				values: { source: 'attribute', attribute: 'data-value' },
			}
		},
		// data: {
		// 	type: 'array',
		// 	default: {
		// 		labels: ['Red', 'Blue'],
		// 		values: [23, 77],
		// 		backgroundColors: ['#cf2e2e', '#0693e3'],
		// 	}
		// }

	},

	edit: ChartEditor,

	save({attributes, id}) {
		const {data} = attributes

		return (<div className="data-holder">
			<canvas className="obfx-chart-pie" id={"obfx-p"}></canvas>
		</div>);
	},
});