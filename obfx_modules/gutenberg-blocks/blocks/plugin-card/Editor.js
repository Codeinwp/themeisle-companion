const {Component, Fragment} = wp.element;
const {withSelect} = wp.data;

const {__, sprintf} = wp.i18n;

const {ENTER, ESCAPE} = wp.utils.keycodes;

const {Spinner} = wp.components;

export default class PluginCardEditor extends Component {
	constructor() {
		super(...arguments);

		this.searchPlugins = this.searchPlugins.bind(this)
		this.selectPlugin = this.selectPlugin.bind(this)
	}

	render() {
		const {
			isSelected,
			setAttributes,
			attributes
		} = this.props;

		const {
			slug,
			fetching,
			plugin_name,
			plugin_author,
			plugin_desc,
			plugin_version,
			search_data
		} = attributes;

		let output = null;
		let desc_style = null;

		if ( ! isSelected ) {
			desc_style = {height: 120, overflow: 'hidden', display: 'block'};
		}

		if ( plugin_name ) {
			output = (<Fragment>
				<h3 dangerouslySetInnerHTML={{ __html:_.unescape( plugin_name ) }} ></h3>
				<ul>
					<li><strong>Author:</strong> <span dangerouslySetInnerHTML={{ __html: _.unescape( plugin_author ) }}></span></li>
					<li><strong>Description:</strong> <span style={ desc_style } dangerouslySetInnerHTML={{ __html: _.unescape( plugin_desc ) }}></span></li>
					<li><strong>Version:</strong><Fragment>{plugin_version}</Fragment></li>
				</ul>
			</Fragment>)
		} else {
			output = (<Fragment>
				<input
					key="search-field"
					type="text"
					placeholder={__('Type a plugin name and hit Enter...')}
					defaultValue={slug}
					onKeyDown={(event) => {
						if (event.keyCode === ESCAPE) {
							event.target.value = '';
							setAttributes({slug: ''});
						}

						if (event.keyCode === ENTER) {
							this.searchPlugins(event.target.value)
						}
					}}
				/>

				{ fetching ? <Spinner /> : null }

				{ search_data
					? <ul>
						{Object.keys( search_data ).map((i,j) => {
							const plugin_data = search_data[i]

							return <li key={i} onClick={ (e) => {
								e.preventDefault();
								this.selectPlugin( plugin_data )
							} }>
								<span dangerouslySetInnerHTML={{ __html: _.unescape( plugin_data.name ) }}></span>
							</li>
						})}
					</ul>
					: null }

			</Fragment>)
		}

		return output;
	}

	searchPlugins(search) {
		const {
			setAttributes,
			attributes
		} = this.props;

		if (attributes.fetching) {
			return;
		}

		setAttributes({fetching: true});

		fetch(wpApiSettings.root + 'obfx-plugin-card/v1/search?search=' + encodeURIComponent(search), {
			method: 'GET',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': wpApiSettings.nonce,
			},
			credentials: 'same-origin'
		})
			.then(data => data.json())
			.then(result => {
				if ( result.success ) {
					setAttributes( { fetching: false, search_data: result.data.plugins } );
				} else {
					setAttributes({fetching: false});
				}
			});

	}

	selectPlugin(plugin_data){
		const {
			setAttributes
		} = this.props;

		setAttributes({
			plugin_name: plugin_data.name,
			plugin_author: plugin_data.author,
			plugin_desc: plugin_data.short_description,
			plugin_version: plugin_data.version,
		})
	}
}

