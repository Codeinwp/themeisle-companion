const {Component, Fragment} = wp.element;
const {withSelect} = wp.data;

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
	}

	static getDerivedStateFromProps( props, state ){
		const {attributes} = props;
		const {
			chartName,
			data
		} = attributes;

		if ( data.labels.length < 1 ) {
			return {}
		}

		const labels = data.labels;
		const values = data.values;
		const backColors = data.backgroundColors;

		const el = document.getElementById('obfx-chart-pie');
		if ( null === el ) {
			return {}
		}

		if ( props.attributes === state.attributes ) {
			//return props
		}

		var chart = new Chart( el, {
			type: 'pie',
			data: {
				labels: labels,
				datasets: [{
					label: chartName,
					data: values,
					backgroundColor: backColors,
				}]
			}
		})
		return props
	}

	render() {
		const {
			isSelected,
			setAttributes,
			attributes
		} = this.props;

		const {
			chartName,
			backgroundColor,
			data,
			tempLabel,
			tempValue
		} = attributes;

		const dataOptions = data.labels.map( (label,index) => {
			// const {label, value, color} = entry
			const value = data.values[index]
			const color = data.backgroundColors[index]

			return (<div className="obfx-chart-entry" key={index}>
				<RichText
					tagName={'p'}
					value={ label }
					multiline="false"
					formattingControls={ [] }
					keepPlaceholderOnFocus
					onChange={ (newValue) => {
						data.labels[index] = newValue[0];
						setAttributes( { data: data } )
					} }
					placeholder={__('Label')}
				/>

				<RichText
					tagName={'p'}
					value={ value }
					multiline="false"
					formattingControls={ [] }
					keepPlaceholderOnFocus
					onChange={ (newValue) => {
						data.values[index] = newValue[0];
						setAttributes( { data: data } )
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
							// let newEntry = data[index];
							// newEntry = { ...newEntry, color: newColor};

							data.backgroundColors[index] = newValue;
							setAttributes( { data: data } )
							//
							// setAttributes( { data: [...data, newEntry] } )
						} }
					/>
				</PanelColor>

				<Button
					isSmall
					onClick={ (e) => {
						e.preventDefault();
						data.labels.splice(index, 1);
						data.values.splice(index, 1);
						data.backgroundColors.splice(index, 1);
						setAttributes({data: data})
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

			<canvas id="obfx-chart-pie" style={{backgroundColor: backgroundColor}}></canvas>

			{isSelected ? dataOptions : null }

			{isSelected ? <div className="obfx-chart-entry">
				<RichText
					tagName={'p'}
					multiline="false"
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

						data.push( {
							label: tempLabel,
							value: tempValue,
						} );

						setAttributes({ data: data, tempLabel: '', tempValue: '' })
					} }
				>
					{ __( 'Add row' ) }
				</Button>
			</div> : null }
		</div>)
	}
}


