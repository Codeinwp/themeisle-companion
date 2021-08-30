/* global obfxDash */
import { Dashicon, ToggleControl } from '@wordpress/components';
import ReactHtmlParser from 'react-html-parser';
import { requestData } from '../utils/rest';
import { useContext, useState } from '@wordpress/element';
import { ModulesContext } from './DashboardContext';
import ModuleSettings from './ModuleSettings';

const { root, toggleStateRoute, options } = obfxDash;

const ModuleCard = ( { slug, details } ) => {
	const [ loading, setLoading ] = useState( false );
	const [ errorState, setErrorState ] = useState( false );
	const { modulesData, setModulesData } = useContext( ModulesContext );
	// eslint-disable-next-line camelcase
	const { module_status } = modulesData;

	const updateModuleStatus = ( value ) => {
		const dataToSend = { slug, value };
		return requestData( root + toggleStateRoute, dataToSend );
	};

	if ( errorState ) {
		setTimeout( () => setErrorState( false ), 2500 );
	}

	return (
		<div className="module-card">
			<div className="module-card-header">
				<h3 className="title">{ details.name }</h3>
				<div className="toggle-wrap">
					{ loading && (
						<Dashicon
							size={ 18 }
							icon="update"
							className="is-loading"
						/>
					) }
					<ToggleControl
						checked={
							// eslint-disable-next-line camelcase
							module_status && module_status[ slug ]
								? module_status[ slug ].active
								: false
						}
						onChange={ ( value ) => {
							setLoading( true );
							updateModuleStatus( value ).then( ( r ) => {
								if ( r.type !== 'success' ) {
									setErrorState( true );
									setLoading( false );
									return;
								}

								// eslint-disable-next-line camelcase
								if ( ! module_status[ slug ] ) {
									module_status[ slug ] = {};
								}

								module_status[ slug ].active = value;
								setModulesData( modulesData );
								setLoading( false );
							} );
						} }
					/>
					{ errorState && <p> Something went wrong! Try again. </p> }
				</div>
			</div>
			<div className="module-card-content">
				<div className="description">
					{ ReactHtmlParser( details.description ) }
				</div>
			</div>
			{ module_status[ slug ] &&
				module_status[ slug ].active &&
				options[ slug ].length > 0 && <ModuleSettings slug={ slug } /> }
		</div>
	);
};

export default ModuleCard;
