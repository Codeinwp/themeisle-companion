/* jshint node:true */
/* global require */

module.exports = function (grunt) {
	grunt.loadNpmTasks('grunt-version');
	grunt.loadNpmTasks('grunt-wp-readme-to-markdown');
    grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.initConfig({
		package: grunt.file.readJSON('package.json'),
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
			},
		},
		addtextdomain: {
			options: {
				textdomain: '<%= package.textdomain %>',
				updateDomains: ['textdomain']
			},
			target: {
				files: {
					src: [
						'vendor/codeinwp/**/*.php'
					]
				}
			}
		},
		makepot: {
			target: {
				options: {
					cwd: '',
					domainPath: 'languages',
					exclude: ['node_modules/.*'],
					include: ['.*', 'vendor/codeinwp/.*'],
					mainFile: 'themeisle-companion.php',
					potFilename: 'themeisle-companion.pot',
					potHeaders: {
						poedit: true,
						'x-poedit-keywordslist': true,
						'report-msgid-bugs-to': '<%= package.pot.reportmsgidbugsto %>',
						'language-team': '<%= package.pot.languageteam %>',
						'last-translator': '<%= package.pot.lasttranslator %>',
					},
					processPot: null,
					type: 'wp-plugin',
					updateTimestamp: true,
					updatePoFiles: false
				}
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

	grunt.registerTask('i18n', ['addtextdomain','makepot']);
};
