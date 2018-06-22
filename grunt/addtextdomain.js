/* jshint node:true */
// https://github.com/blazersix/grunt-wp-i18n
module.exports = {
	dependencyDomains: {
		options: {
			textdomain: '<%= package.textdomain %>',
			updateDomains: ['textdomain']
		},
		files: {
			src: [
				'vendor/codeinwp/**/*.php'
			]
		}
	}
};
