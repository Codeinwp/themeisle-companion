var glob = require("glob"),
	webpack = require( 'webpack' ),
	NODE_ENV = process.env.NODE_ENV || 'development',
	webpackConfig = {
		mode: 'development',
		entry: glob.sync("./blocks/**/*.js"),
		output: {
			path: __dirname,
			filename: './build/block.js',
		},
		module: {
			rules: [
				{
					test: /.js$/,
					use: 'babel-loader',
					exclude: /node_modules/,
				},
			],
		},
		plugins: [
			new webpack.DefinePlugin( {
				'process.env.NODE_ENV': JSON.stringify( NODE_ENV )
			} ),
		]
	};


module.exports = webpackConfig;
