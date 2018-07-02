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
} = wp.editor;


const {
	Fragment
} = wp.element;
const {RichText} = wp.editor;


/**
 * Returns the layouts configuration for a given number of panels.
 *
 * @param {number} panels Number of panels.
 *
 * @return {Object[]} Columns layout configuration.
 */
const getPanelLayouts = memoize((panels) => {

	return Array.apply(null, Array(panels)).map((i, n) => ({
		name: `panel-${ n + 1 }`,
		label: sprintf(__('Panel %d'), n + 1),
		icon: 'panels',
	}));
});

export default class PriceTableEditor extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		const {attributes, setAttributes} = this.props;
		const {panels, className} = attributes;
		const classes = classnames(className, `wp-block-orbitfox-pricing-table has-${ panels }-panels`);

		// @TODO provide a better html structure and better css classes.

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody>
						<RangeControl
							label={__('Panels')}
							value={panels}
							onChange={(nextPanels) => {
								setAttributes({
									panels: nextPanels,
								});
							}}
							min={2}
							max={6}
						/>
					</PanelBody>
				</InspectorControls>
				<div className={classes}>
					<InnerBlocks
						layouts={getPanelLayouts(panels)}
						template={[
							['core/heading', {
								content: 'Panel 1',
								layout: 'panel-1',
							}],
							['core/heading', {
								content: 'Panel 2',
								layout: 'panel-2',
							}],
							['core/heading', {
								content: 'Panel 3',
								layout: 'panel-3',
							}],
							['core/paragraph', {
								content: 'Small description',
								layout: 'panel-1',
							}],
							['core/paragraph', {
								content: 'Small description',
								layout: 'panel-2',
							}],
							['core/paragraph', {
								content: 'Small description',
								layout: 'panel-3',
							}],
							['core/list', {
								values: [ 'Feature 1' ],
								layout: 'panel-1',
							}],
							['core/list', {
								values: [ 'Feature 1' ],
								layout: 'panel-2',
							}],
							['core/list', {
								values: [ 'Feature 1' ],
								layout: 'panel-3',
							}],

							['core/separator', {
								layout: 'panel-1',
							}],

							['core/separator', {
								layout: 'panel-2',
							}],

							['core/separator', {
								layout: 'panel-3',
							}],

							['core/button', {
								url: "#",
								text: "Buy me",
								layout: 'panel-1',
							}],

							['core/button', {
								url: "#",
								text: "Buy me",
								layout: 'panel-2',
							}],

							['core/button', {
								url: "#",
								text: "Buy me",
								layout: 'panel-3',
							}],

						]}
					/>
				</div>
			</Fragment>
		);
	}
}


