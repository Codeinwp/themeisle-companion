/**
 * External dependencies
 */
import { Chart } from "react-google-charts";

import { HotTable } from '@handsontable/react';

import 'handsontable/dist/handsontable.full.css';

/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;

const { registerBlockType } = wp.blocks;

const {
	Button,
	Dashicon,
	FormToggle,
    PanelBody,
	PanelRow,
	TextControl,
	Toolbar,
	Tooltip,
} = wp.components;

const {
	compose,
	withState,
} = wp.compose;

const { withSelect } = wp.data;

const {
	BlockControls,
	InspectorControls,
} = wp.editor;

const { Fragment } = wp.element;

/**
 * Internal dependencies
 */
import './editor.scss';

registerBlockType( 'orbitfox/chart-pie', {
	title: __( 'Pie Chart' ),
	description: __( 'Display a beautiful Pie Chart on your blog post with Pie Chart block.' ),
	icon: 'chart-pie',
	category: 'orbitfox',
	keywords: [
		__( 'pie' ),
		__( 'chart' ),
		__( 'orbitfox' ),
	],
	attributes: {
		data: {
			type: 'string',
			default: '[["Label","Data"],["Dogs",40],["Cats",30],["Racoons",20],["Monkeys",10]]',
		},
		options: {
			type: 'object',
			default: {
				title: 'Animals',
				is3D: true,
			},
		},
		id: {
			type: 'string',
			default: '',
		}
	},

	supports: {
		align: true,
	},

	edit: compose( [

		withSelect( ( select, props ) => {
			return {
				props,
			};
		} ),

		withState( {
			isOpen: false,
		} )

	] )( ( { isOpen, setState, props, className } ) => {

		const updateData = ( value, source ) => {
			if ( value !== null ) {
				const options = JSON.parse( [ props.attributes.data ] );
				value.forEach( item => {
					options[ item[0] ][ item[1] ] = item[3];
				});
				props.setAttributes( { data: JSON.stringify( options ) } );
			}
		};

		const addRow = () => {
			const options = JSON.parse( [ props.attributes.data ] );
			options.push( [ "", "" ] );
			props.setAttributes( { data: JSON.stringify( options ) } );
		};

		const changeChartTitle = value => {
			const options = { ...props.attributes.options };
			options['title'] = value;
			props.setAttributes( { options } );
		};

		const toggle3d = () => {
			const options = { ...props.attributes.options };
			options['is3D'] = !props.attributes.options.is3D;
			props.setAttributes( { options } );
		};

		if ( props.clientId && props.attributes.id === '' ) {
			const id = props.clientId;
			props.setAttributes( { id } );
		}

		return [
			<BlockControls key="toolbar-controls">
				<Toolbar
					className='components-toolbar'
				>
					<Tooltip text={ __( 'Edit Chart' )	}>
						<Button
							className="components-icon-button components-toolbar__control edit-pie-chart"
							onClick={ () => setState( { isOpen: ! isOpen } ) }
						>
							<Dashicon icon={ isOpen ? 'yes' : 'edit' } />
						</Button>
					</Tooltip>
				</Toolbar>
			</BlockControls>,

			<InspectorControls>
			<PanelBody
				title={ __( 'Chart Settings' ) }
			>
				<TextControl
					label={ __( 'Chart Title' ) }
					value={ props.attributes.options.title }
					onChange={ changeChartTitle }
				/>
				<PanelRow>
					<label
						htmlFor="is-3d-form-toggle"
					>
						{ __( 'Is chart 3d?' ) }
					</label>
					<FormToggle
						id="is-3d-form-toggle"
						label={ __( 'Is chart 3rd? ' ) }
						checked={ props.attributes.options.is3D }
						onChange={ toggle3d }
					/>
				</PanelRow>
			</PanelBody>
			</InspectorControls>,

			<div className={ className }>
				{ isOpen ?
					<Fragment>
						<HotTable
							data={ JSON.parse( props.attributes.data ) }
							allowInsertRow={ true }
							cell={ [
								{
									row: 0,
									col: 0,
									readOnly: true
								},
								{
									row: 0,
									col: 1,
									readOnly: true
								}
							] }
							columns={ [
								{
									type: 'text',
								},
								{
									type: 'numeric',
								}
							] }
							contextMenu={ true }
							className="htLeft"
							height="200"
							rowHeaders={ true }
							stretchH="all"
							onAfterChange={ ( value, source ) => updateData( value, source ) }
						/>
						<Button
							onClick={ addRow }
							isPrimary
						>
							{ __( 'Add another row' ) }
						</Button>
					</Fragment>
				:
					<Chart
						chartType="PieChart"
						data={ JSON.parse( props.attributes.data ) }
						options={ props.attributes.options }
						width="100%"
						height="400px"
						legendToggle
					/>
				}
		  </div>
		];
	} ),

	save: () => {
		return null;
	},
});