/**
 * WordPress dependencies...
 */
const {
	__,
	sprintf
} = wp.i18n;

// import {times } from 'lodash';
import classnames from 'classnames';
import memoize from 'memize';

const {Component} = wp.element;

import './editor.scss';

const {
	PanelBody,
	RangeControl
} = wp.components;

const {
	InspectorControls,
	InnerBlocks,
	RichText
} = wp.editor;

const {
	Fragment
} = wp.element;

export default class PriceTableEditor extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		const {attributes, setAttributes} = this.props;
		const {columns, className} = attributes;
		const classes = classnames(className, `wp-block-orbitfox-pricing-table has-${ columns }-columns`);

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<RangeControl
							label={__('Panels')}
							value={columns}
							onChange={(nextPanels) => {
								setAttributes({
									columns: nextPanels,
								});
							}}
							min={2}
							max={4}
						/>
					</PanelBody>
				</InspectorControls>
				<div className={classes}>
					<InnerBlocks
						templateLock={ 'all' }
						allowedBlocks={ [ 'core/column' ] }
						template={memoize( ( columns ) => {
							return _.times( columns, (i) => {
								const index = i + 1;
								return [ 'core/column', {}, [
									['core/heading', {
										content: 'Panel ' + index,
										align: "center",
									}],
									['core/paragraph', {
										content: 'Small description, but a pretty long one.',
										align: "center",
									}],
									['core/separator', {}],
									['core/paragraph', {
										content: 'First Feature',
										align: "center",
									}],
									['core/paragraph', {
										content: 'Second Feature',
										align: "center",
									}],
									['core/paragraph', {
										content: 'Last Feature',
										align: "center",
									}],
									['core/separator', {}],
									['core/button', {
										url: "#",
										text: "Buy me",
										align: "center",
										backgroundColor: 'vivid-red'
									}],
								] ]
							});
						} )( columns )}
					/>
				</div>
			</Fragment>
		);
	}
}


