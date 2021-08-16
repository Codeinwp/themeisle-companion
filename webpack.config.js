const path = require( 'path' );

module.exports = [
	{
		entry: {
			'obfx_modules/mystock-import/js/script': path.resolve(
				__dirname,
				'obfx_modules/mystock-import/js/src/registerPlugin.js'
			),
		},
		output: {
			path: path.resolve( __dirname ),
			filename: '[name].js',
		},
		module: {
			rules: [
				{
					test: /.jsx?$/,
					loader: 'babel-loader',
					exclude: /node_modules/,
					options: {
						presets: [ '@babel/preset-env', '@babel/preset-react' ],
					},
				},
			],
		},
	},
	{
		entry: {
			'dashboard/build/dashboard': path.resolve(
				__dirname,
				'dashboard/src/dashboard.js'
			),
		},
		output: {
			path: path.resolve( __dirname ),
			filename: '[name].js',
		},
		module: {
			rules: [
				{
					test: /.jsx?$/,
					loader: 'babel-loader',
					exclude: /node_modules/,
					options: {
						presets: [ '@babel/preset-env', '@babel/preset-react' ],
					},
				},
				{
					test: /\.s[ac]ss$/i,
					use: [ 'style-loader', 'css-loader', 'sass-loader' ],
				},
			],
		},
	},
];
