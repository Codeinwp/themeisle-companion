const {Component, Fragment} = wp.element;
const {withSelect} = wp.data;

import update from 'immutability-helper';

const {__} = wp.i18n;
const {
	FormToggle,
	PanelBody,
	PanelColor,
	Button
} = wp.components;

const {
	InspectorControls,
	RichText,
	ColorPalette
} = wp.editor;
import './editor.scss';

export default class ChartEditor extends Component {
	constructor() {
		super(...arguments);

		this.state = {}

		this.props.attributes.blockID = this.props.clientId
	}

	static getDerivedStateFromProps( props, state ){
		const {attributes, setAttributes, clientId} = props;

		const {
			doPreview,
			chartName,
			dataLabels,
			dataValues,
			dataColors,
			blockID
		} = attributes;

		if ( dataLabels.length < 1 ) {
			return {}
		}

		setTimeout(() => {
			const el = document.getElementById('obfx-chart-pie-' + blockID);

			const chart = new Chart( el, {
				type: 'pie',
				data: {
					labels: dataLabels,
					datasets: [{
						label: chartName,
						data: dataValues,
						backgroundColor: dataColors,
					}]
				}
			});

		}, 11 )

		return props
	}

	render() {
		const {
			isSelected,
			setAttributes,
			attributes,
			clientId
		} = this.props;

		const {
			chartName,
			blockID,
			backgroundColor,
			tempLabel,
			tempValue,
			dataLabels,
			dataValues,
			dataColors
		} = attributes;

		const dataOptions = dataLabels.map( (label,index) => {
			const value = dataValues[index]
			const color = dataColors[index]

			return (<div className="obfx-chart-entry" key={index}>
				<RichText
					tagName={'p'}
					value={ label }
					multiline="false"
					formattingControls={ [] }
					onChange={ (newValue) => {
						const newLabels = [...dataLabels]
						newLabels[index] = newValue[0];
						setAttributes( { dataLabels: newLabels } )
					} }
					placeholder={__('Label')}
				/>

				<RichText
					tagName={'p'}
					value={ value }
					multiline="false"
					formattingControls={ [] }
					onChange={ (newValue) => {
						const newValues = [...dataValues]
						newValues[index] = newValue[0];
						setAttributes( { dataValues: newValues } )
					} }
					placeholder={__('value')}
				/>

				<PanelColor
					title={ __( 'Color' ) }
					colorValue={ color }
					initialOpen={ false } >
					<ColorPalette
						label={ __( 'Color' ) }
						value={ color }
						onChange={ (newColor) => {
							const newColors = [...dataColors]
							newColors[index] = newColor;
							setAttributes( { dataColors: newColors } )
						} }
					/>
				</PanelColor>

				<Button
					isSmall
					onClick={ (e) => {
						e.preventDefault();
						let newLabels = [...dataLabels];
						let newValues = [...dataValues];
						let newColors = [...dataColors];

						newLabels.splice(index, 1);
						newValues.splice(index, 1);
						newColors.splice(index, 1);

						setAttributes({ dataLabels: newLabels, dataValues: newValues, dataColors: newColors })
					} }
				>
					{ __( 'Delete' ) }
				</Button>
			</div>)
		})

		return (<div>
			<InspectorControls>
				<PanelBody>
					<RichText
						tagName={'p'}
						value={ chartName }
						className={ 'obfx-accordion-label' }
						onChange={ (newValue) => setAttributes( { chartName: newValue } ) }
						placeholder={__('Chart Name')}
					/>
					<PanelColor title={ __( 'Background Color' ) } colorValue={ backgroundColor } initialOpen={ false }>
						<ColorPalette
							label={ __( 'Background Color' ) }
							value={ backgroundColor }
							onChange={ ( value ) => setAttributes( { backgroundColor: value } ) }
						/>
					</PanelColor>
				</PanelBody>
			</InspectorControls>

			<canvas id={"obfx-chart-pie-" + blockID} style={{backgroundColor: backgroundColor}}>loading</canvas>

			{isSelected ? dataOptions : null }

			{isSelected ? <div className="obfx-chart-entry">
				<RichText
					tagName={'p'}
					multiline="false"
					value={tempLabel}
					formattingControls={ [] }
					keepPlaceholderOnFocus
					placeholder={__('Label')}
					onChange={(label)=>{
						setAttributes({tempLabel: label[0]})
					}}
				/>

				<RichText
					tagName={'p'}
					multiline="false"
					value={tempValue}
					formattingControls={ [] }
					keepPlaceholderOnFocus
					placeholder={__('Value')}
					onChange={(val)=>{
						setAttributes({tempValue: val[0]})
					}}
				/>

				<div></div>

				<Button
					isSmall
					onClick={ (e) => {
						e.preventDefault();
						if ( _.isEmpty (tempLabel) || _.isEmpty (tempValue) ) {
							return;
						}

						setAttributes({
							dataLabels: update(dataLabels, {$push: [tempLabel]}),
							dataValues: update(dataValues, {$push: [tempValue]}),
							dataColors: update(dataColors, {$push: ['#333']}),
							tempLabel: '',
							tempValue: ''
						})
					} }
				>
					{ __( 'Add row' ) }
				</Button>
			</div> : null }

		</div>)
	}
}


