/**
 * WordPress dependencies...
 */
const {__} = wp.i18n;

const {InspectorControls} = wp.editor;
const {Button, TextControl, ToggleControl, RangeControl, SelectControl, Spinner} = wp.components;
const {
	Component,
	Fragment
} = wp.element;

const {RichText} = wp.editor;

let settings;
wp.api.loadPromise.then(() => {
	settings = new wp.api.models.Settings();
});

export default class GoogleMapEditor extends Component {
	constructor() {
		super(...arguments);

		this.state = {
			apiKey: '',
			isSavedKey: false,
			isLoading: true,
			isSaving: false,
			keySaved: false,
		};

		settings.on('change:orbitfox_google_map_block_api_key', (model) => {
			const apiKey = model.get('orbitfox_google_map_block_api_key');
			this.setState({
				apiKey: settings.get('orbitfox_google_map_block_api_key'),
				isSavedKey: (apiKey === '') ? false : true
			});
		});

		settings.fetch().then(response => {
			this.setState({apiKey: response.orbitfox_google_map_block_api_key});
			if (this.state.apiKey && this.state.apiKey !== '') {
				this.setState({isSavedKey: true});
			}
			this.setState({isLoading: false});
		});

		this.saveApiKey = this.saveApiKey.bind(this);

		this.getMapHTML = this.getMapHTML.bind(this);
		this.getMapURL = this.getMapURL.bind(this);
	}

	render() {
		const {attributes, className, isSelected, setAttributes} = this.props;
		const {location, mapType, zoom, interactive, maxWidth, maxHeight, aspectRatio} = attributes;
		const editorPadding = '0 1em';
		const classNames = (!interactive) ? `${className} ratio${aspectRatio}` : `${className}  ratio${aspectRatio} interactive`;
		const linkOptions = [
			{value: 'roadmap', label: __('roadmap')},
			{value: 'satellite', label: __('satellite')},
		];

		const aspectRatioOptions = [
			{value: '2_1', label: __('2:1')},
			{value: '1_1', label: __('1:1')},
			{value: '4_3', label: __('4:3')},
			{value: '16_9', label: __('16:9')},
			{value: '1_2', label: __('1:2')},
		];

		if (!!this.state.isLoading) {
			return (<Fragment>
				<div className="wp-block-embed is-loading">
					<Spinner/>
					<p>{__('Loading…')}</p>
				</div>
			</Fragment>);
		}

		const keyInput = (
			<div>
				<p style={{textAlign: 'center'}}>
					{__('A Google Maps API key is required, please enter one below.')}<br/>
					{__('Note: changing the API key effects all Google Map Embed blocks.')}
				</p>
				<TextControl
					key="api-input"
					value={this.state.apiKey}
					onChange={value => this.setState({apiKey: value})}
					style={{textAlign: 'center', border: 'solid 1px rgba(100,100,100,0.25)'}}
					placeholder={__('API Key')}
				/>
				<p style={{textAlign: 'center', paddingBottom: '1em'}}>
					{__('Need an API key? Get one')}&nbsp;
					<a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,static_maps_backend,maps_embed_backend&keyType=CLIENT_SIDE&reusekey=true">
						{__('here.')}
					</a><br/><br/>
					<Button
						isPrimary
						onClick={this.saveApiKey}
						isBusy={this.state.isSaving}
						disabled={this.state.apiKey === ''}
					>
						{__('Save API key')}
					</Button>
				</p>
			</div>
		);

		if (!this.state.isSavedKey) {
			return (
				<div className={`${className} error`} style={{padding: editorPadding}}>
					{keyInput}
				</div>
			)
		}

		return [
			!!isSelected && (
				<InspectorControls>
					{!!interactive ? (
						<SelectControl
							label={__('Aspect Ratio')}
							select={aspectRatio}
							options={aspectRatioOptions}
							onChange={(value) => setAttributes({aspectRatio: value})}
							value={aspectRatio}
						/>
					) : null}
					<RangeControl
						label={__('Zoom Level')}
						value={zoom}
						onChange={(value) => setAttributes({zoom: value})}
						min={5}
						max={20}
					/>
					<SelectControl
						label={__('Map Type')}
						select={mapType}
						options={linkOptions}
						onChange={(value) => setAttributes({mapType: value})}
						value={mapType}
					/>
					<ToggleControl
						label={__('Toggle interactive map (on) or static image (off)')}
						checked={!!interactive}
						onChange={() => setAttributes({interactive: !interactive})}
					/>
					{!interactive ? (
						<div>
							<TextControl
								label={__('Maximum width (in pixels)')}
								onChange={(value) => setAttributes({maxWidth: Number.parseInt(value, 10)})}
								value={maxWidth}
								type='number'
								min={0}
								step={1}
							/>
							<TextControl
								label={__('Maximum height (in pixels)')}
								onChange={(value) => setAttributes({maxHeight: Number.parseInt(value, 10)})}
								value={maxHeight}
								type='number'
								min={0}
								step={1}
							/>
						</div>
					) : null}
					{keyInput}
				</InspectorControls>
			),
			(<TextControl
				key="location-input"
				style={{textAlign: 'center', border: 'solid 1px rgba(100,100,100,0.25)'}}
				onChange={(value) => setAttributes({location: value})}
				value={location}
				placeholder={__('Enter a location...')}
				label={(location === '' || !location.length) ? __('Location') : null}
			/>),
			(location === '' || !location.length) ? (
				<div className={`${className} error`} style={{padding: editorPadding}}>
					<p style={{textAlign: 'center'}}>
						{__('A location is required. Please enter one in the field above.')}
					</p>
				</div>
			) : (
				(this.state.apiKey === '' && this.state.keySaved === false) ?
					keyInput
					: (<div className={classNames}>
						<div className="map">
							{this.getMapHTML(attributes, this.state.apiKey)}
						</div>
					</div>)
			),
		];
	}

	saveApiKey() {
		this.setState({isSaving: true});
		const model = new wp.api.models.Settings({orbitfox_google_map_block_api_key: this.state.apiKey});
		model.save().then(response => {
			this.setState({isSavedKey: true, isLoading: false, isSaving: false, keySaved: true});
			settings.fetch();
		});
	}

	getMapHTML(attributes, apiKey) {
		const {interactive} = attributes;

		if (apiKey === '' || apiKey === undefined) {
			return null;
		}

		const mapURL = this.getMapURL(attributes, apiKey);

		if (!!interactive) {

			return (
				<iframe
					width='100%'
					height='100%'
					frameBorder="0"
					style={{border: 0}}
					src={mapURL}
					allowFullScreen={true}>
				</iframe>
			)
		} else {
			return (
				<img src={mapURL}/>
			);
		}
	}

	getMapURL(attributes, apiKey) {
		const {location, mapType, zoom, interactive, maxHeight, maxWidth} = attributes;

		if (apiKey === '' || apiKey === undefined) {
			return null;
		}

		if (!!interactive) {

			return (
				`https://www.google.com/maps/embed/v1/place?key=${apiKey}&q=${encodeURI(location)}&zoom=${zoom}&maptype=${mapType}`
			);
		} else {
			return (
				`https://maps.googleapis.com/maps/api/staticmap?center=${encodeURI(location)}&zoom=${zoom}&size=${maxWidth}x${maxHeight}&maptype=${mapType}&key=${apiKey}`
			);
		}
	}
}