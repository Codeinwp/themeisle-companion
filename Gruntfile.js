/* jshint node:true */
/* global require */

module.exports = function (grunt) {
	grunt.loadNpmTasks('grunt-version');
	grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
	grunt.initConfig({
		version: {
			json: {
				options: {
					flags: ''
				},
				src: [ 'package.json', 'package-lock.json' ]
			},
			metatag: {
				options: {
					prefix: 'Version:\\s*',
					flags: ''
				},
				src: [ 'themeisle-companion.php' ]
			},
			php: {
				options: {
					prefix: '->version\\s.*?=\\s.*?\'',
					flags: ''
				},
				src: [ 'core/includes/class-orbit-fox.php' ]

			}
		},
		wp_readme_to_markdown: {
			plugin: {
				files: {
					'readme.md': 'readme.txt'
				},
			},
		},
	});

};