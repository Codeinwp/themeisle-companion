/* global obfxDash */
import fetch from 'node-fetch';

export const requestData = async (
	route,
	simple = false,
	data = {},
	urlEncoded = false,
	method = 'POST'
) => {
	const options = {
		method,
		headers: {
			Accept: 'application/json',
			'Content-Type': urlEncoded
				? 'application/x-www-form-urlencoded'
				: 'application/json',
			'x-wp-nonce': obfxDash.nonce,
		},
	};

	if ('POST' === method) {
		options.body = urlEncoded ? data : JSON.stringify(data);
	}

	return await fetch(route, options).then((response) => {
		return simple ? response : response.json();
	});
};

export const get = (route, simple) => {
	return requestData(route, simple, {}, 'GET');
};

export const post = (route, data) => {
	return requestData(route, false, data, true);
};
