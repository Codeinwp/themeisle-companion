/* global obfxDash */
import ReactHtmlParser from 'react-html-parser';
import { ToggleControl } from '@wordpress/components';

const { modules, data } = obfxDash;

const AvailableModules = () => {
	// eslint-disable-next-line no-console
	console.log( modules, data );

	const renderModules = () => {
		// eslint-disable-next-line camelcase
		const { module_status } = data;
		return Object.entries( modules )
			.filter( ( [ slug ] ) => {
				return (
					module_status[ slug ] &&
					module_status[ slug ].hasOwnProperty( 'active' )
				);
			} )
			.map( ( [ slug, details ] ) => {
				return (
					<div className="module-card" key={ slug }>
						<div className="module-card-header">
							<h3 className="title">{ details.name }</h3>
							<div className="toggle-wrap">
								<ToggleControl
									checked={ module_status[ slug ].active }
									onChange={ () => {} }
								/>
							</div>
						</div>
						<div className="module-card-content">
							<div className="description">
								{ ReactHtmlParser( details.description ) }
							</div>
						</div>
					</div>
				);
			} );
	};

	return <div className="modules-container">{ renderModules() }</div>;
};

export default AvailableModules;
