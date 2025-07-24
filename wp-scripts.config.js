/**
 * Custom WP Scripts configuration.
 *
 * - Enables HOT reloading for development.
 *
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	devServer: {
		...defaultConfig.devServer,
		allowedHosts: ['all', '.test'],
		headers: {
			'Access-Control-Allow-Origin': '*',
			'Access-Control-Allow-Methods':
				'GET, POST, PUT, DELETE, PATCH, OPTIONS',
			'Access-Control-Allow-Headers':
				'X-Requested-With, content-type, Authorization',
		},
		host: '0.0.0.0',
		port: 8887,
		hot: true,
		https: true,
		client: {
			webSocketURL: 'wss://localhost:8887/ws',
			overlay: true,
		},
		setupMiddlewares: (middlewares, devServer) => {
			if (!devServer) {
				throw new Error('webpack-dev-server is not defined');
			}

			// Add CORS headers to all responses
			devServer.app.use((req, res, next) => {
				res.header('Access-Control-Allow-Origin', '*');
				next();
			});

			return middlewares;
		},
	},
	watchOptions: {
		ignored: [
			'**/node_modules/**',
			'**/assets/build/**',
			'**/dist/**',
			'**/*.hot-update.*',
		],
		aggregateTimeout: 300,
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /tailwind.*\.css$/,
				sideEffects: false,
				use: [
					{
						loader: 'style-loader',
						options: {
							injectType: 'singletonStyleTag',
						},
					},
					'css-loader',
				],
			},
		],
	},
};
