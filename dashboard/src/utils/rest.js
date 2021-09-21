/* global obfxDash */
import fetch from 'node-fetch';

export const requestData = async (
	route,
	simple = false,
	data = {},
	method = 'POST'
) => {
	const options = {
		method,
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'x-wp-nonce': obfxDash.nonce,
		},
	};

	if ('POST' === method) {
		options.body = JSON.stringify(data);
	}

	return await fetch(route, options).then((response) => {
		return simple ? response : response.json();
	});
};

export const get = (route, simple) => {
	return requestData(route, simple, {}, 'GET');
};
