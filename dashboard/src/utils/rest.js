/* global obfxDash */
import fetch from 'node-fetch';

export const requestData = async ( route, data = {}, method = 'POST' ) => {
	const options = {
		method,
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'x-wp-nonce': obfxDash.nonce,
		},
	};

	if ( 'POST' === method ) {
		options.body = JSON.stringify( data );
	}

	return await fetch( route, options ).then( ( response ) => {
		return response.json();
	} );
};
