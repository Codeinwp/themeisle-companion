/* global obfxDash */
import { Dashicon, ToggleControl } from '@wordpress/components';
import ReactHtmlParser from 'react-html-parser';
import fetch from 'node-fetch';
import { useState } from '@wordpress/element';

const { data, nonce } = obfxDash;

const ModuleCard = ( { slug, details } ) => {
	// eslint-disable-next-line camelcase
	const { module_status } = data;
	const [ loading, setLoading ] = useState( false );

	const updateModuleStatus = ( value ) => {
		const postData = {
			noance: nonce[ slug ],
			name: slug,
			checked: value,
		};
		const ajaxData = {
			action: 'obfx_update_module_active_status',
			data: encodeURIComponent( JSON.stringify( postData ) ),
		};

		const requestMetadata = {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Content-Type':
					'application/x-www-form-urlencoded; charset=UTF-8',
			},
			body: 'action=' + ajaxData.action + '&data=' + ajaxData.data,
		};

		return new Promise( ( resolve ) => {
			fetch( 'admin-ajax.php', requestMetadata ).then( ( res ) => {
				res.json().then( ( r ) => {
					if ( r.type === 'success' ) {
						module_status[ slug ].active = value;
						resolve( true );
						return;
					}

					resolve( false );
				} );
			} );
		} );
	};

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
						checked={ module_status[ slug ].active }
						onChange={ ( value ) => {
							setLoading( true );
							updateModuleStatus( value ).then( () =>
								setLoading( false )
							);
						} }
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
};

export default ModuleCard;
