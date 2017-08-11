/* jshint node:true */
/* global require */
module.exports = function (grunt) {
	'use strict';

	var loader = require('load-project-config'),
		config = require('grunt-plugin-fleet');
	config = config();
	// Ignore all legacy items.
	config.files.php.push('!obfx_modules/companion-legacy/inc/**/*.php');
	config.files.js.push('!obfx_modules/companion-legacy/assets/**/*.js');
	config.files.js.push('!obfx_modules/companion-legacy/inc/**/*.js');

	loader(grunt, config).init();
};
