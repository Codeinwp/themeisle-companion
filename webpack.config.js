/**
 * Custom Webpack configuration for a WordPress plugin.
 *
 * - Resolves module paths to the 'assets/src' directory.
 *
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	output: {
		...defaultConfig.output,
		filename: '[name].js',
	},
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve?.alias,
			'@': path.resolve(__dirname, 'assets/src'),
		},
	},
};
