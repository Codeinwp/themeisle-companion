/**
 * Version File for Grunt
 *
 * @package themeisle-companion
 */

/* jshint node:true */
// https://github.com/kswedberg/grunt-version
module.exports = {
	options: {
		pkg: {
			version: '<%= package.version %>'
		}
	},
	project: {
		src: [
			'package.json'
		]
	},
	style: {
		options: {
			prefix: 'Version\\:\.*\\s'
		},
		src: [
			'themeisle-companion.php',
			'core/assets/css/orbit-fox-admin.css',
		]
	},
	class: {
		options: {
			prefix: '\\.*version\.*\\s=\.*\\s\''
		},
		src: [
			'core/includes/class-orbit-fox.php',
		]
	}
};
