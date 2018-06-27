/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./blocks/about-author/index.js":
/*!**************************************!*\
  !*** ./blocks/about-author/index.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/about-author', {\n  title: __('About Author'),\n  icon: 'index-card',\n  category: 'common',\n  keywords: ['about', 'author', 'orbitfox'],\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/about-author/index.js?");

/***/ }),

/***/ "./blocks/click-to-tweet/index.js":
/*!****************************************!*\
  !*** ./blocks/click-to-tweet/index.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies.\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/click-to-tweet', {\n  title: __('Click To Tweet'),\n  icon: 'index-card',\n  category: 'common',\n  keywords: ['twitter', 'tweet', 'orbitfox'],\n  attributes: {\n    content: {\n      type: 'array',\n      source: 'children',\n      selector: 'p',\n      default: []\n    }\n  },\n  edit: function edit() {\n    return wp.element.createElement(\n      'blockquote',\n      null,\n      this.props.children\n    );\n  },\n  save: function save(props) {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/click-to-tweet/index.js?");

/***/ }),

/***/ "./blocks/contact-form/index.js":
/*!**************************************!*\
  !*** ./blocks/contact-form/index.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/contact-form', {\n  title: __('Contact Form'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['contact', 'form', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/contact-form/index.js?");

/***/ }),

/***/ "./blocks/google-map/index.js":
/*!************************************!*\
  !*** ./blocks/google-map/index.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/google-map', {\n  title: __('Google Map'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['map', 'google', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/google-map/index.js?");

/***/ }),

/***/ "./blocks/notice/index.js":
/*!********************************!*\
  !*** ./blocks/notice/index.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! classnames */ \"./node_modules/classnames/index.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_0__);\n/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\n\n\n\n\nvar registerBlockType = wp.blocks.registerBlockType;\nvar RichText = wp.editor.RichText;\nvar Fragment = wp.element.Fragment;\n\n\nregisterBlockType('orbitfox/notice', {\n  title: __('Notice'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['notice', 'info'],\n  attributes: {\n    type: {\n      source: 'attribute',\n      selector: '.obfx-block-notice',\n      attribute: 'data-type',\n      default: 'info'\n    },\n    title: {\n      source: 'text',\n      type: 'string',\n      selector: '.obfx-block-notice__title',\n      default: 'Info'\n    },\n    content: {\n      type: 'array',\n      source: 'children',\n      selector: '.obfx-block-notice__content'\n    }\n  },\n  edit: function edit(props) {\n    var _props$attributes = props.attributes,\n        type = _props$attributes.type,\n        content = _props$attributes.content,\n        title = _props$attributes.title,\n        className = props.className,\n        setAttributes = props.setAttributes;\n\n    // @TODO Add a toolbar control and create a custom svg icon for this block\n\n    return wp.element.createElement(\n      Fragment,\n      null,\n      wp.element.createElement(\n        'div',\n        { className: classnames__WEBPACK_IMPORTED_MODULE_0___default()(className, className + '--' + type) },\n        wp.element.createElement(RichText, {\n          tagName: 'p',\n          value: title,\n          className: 'obfx-block-notice__title',\n          onChange: function onChange(title) {\n            return setAttributes({ title: title });\n          }\n        }),\n        wp.element.createElement(RichText, {\n          tagName: 'p',\n          placeholder: __('Your tip/warning content'),\n          value: content,\n          className: 'obfx-block-notice__content',\n          onChange: function onChange(content) {\n            return setAttributes({ content: content });\n          },\n          keepPlaceholderOnFocus: 'true'\n        })\n      )\n    );\n  },\n  save: function save(props) {\n    var _props$attributes2 = props.attributes,\n        type = _props$attributes2.type,\n        title = _props$attributes2.title,\n        content = _props$attributes2.content;\n\n\n    return wp.element.createElement(\n      'div',\n      { className: 'obfx-block-notice--' + type, 'data-type': type },\n      wp.element.createElement(\n        'p',\n        { className: 'obfx-block-notice__title' },\n        title\n      ),\n      wp.element.createElement(\n        'p',\n        { className: 'obfx-block-notice__content' },\n        content\n      )\n    );\n  }\n});\n\n//# sourceURL=webpack:///./blocks/notice/index.js?");

/***/ }),

/***/ "./blocks/our-services/index.js":
/*!**************************************!*\
  !*** ./blocks/our-services/index.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/our-services', {\n  title: __('Our Services'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['services', 'orbitfox'],\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/our-services/index.js?");

/***/ }),

/***/ "./blocks/post-grid/index.js":
/*!***********************************!*\
  !*** ./blocks/post-grid/index.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/posts-grid', {\n  title: __('Posts Grid'),\n  icon: 'info',\n  category: 'layout',\n  keywords: ['posts', 'grid', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/post-grid/index.js?");

/***/ }),

/***/ "./blocks/pricing-table/index.js":
/*!***************************************!*\
  !*** ./blocks/pricing-table/index.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/pricing-table', {\n  title: __('Pricing Table'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['pricing', 'table', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/pricing-table/index.js?");

/***/ }),

/***/ "./blocks/share-icons/index.js":
/*!*************************************!*\
  !*** ./blocks/share-icons/index.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/share-icons', {\n  title: __('Share Icons'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['share', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/share-icons/index.js?");

/***/ }),

/***/ "./blocks/testimonials/index.js":
/*!**************************************!*\
  !*** ./blocks/testimonials/index.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/testimonials', {\n  title: __('Testimonials'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['testimonials', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/testimonials/index.js?");

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!\n  Copyright (c) 2017 Jed Watson.\n  Licensed under the MIT License (MIT), see\n  http://jedwatson.github.io/classnames\n*/\n/* global define */\n\n(function () {\n\t'use strict';\n\n\tvar hasOwn = {}.hasOwnProperty;\n\n\tfunction classNames () {\n\t\tvar classes = [];\n\n\t\tfor (var i = 0; i < arguments.length; i++) {\n\t\t\tvar arg = arguments[i];\n\t\t\tif (!arg) continue;\n\n\t\t\tvar argType = typeof arg;\n\n\t\t\tif (argType === 'string' || argType === 'number') {\n\t\t\t\tclasses.push(arg);\n\t\t\t} else if (Array.isArray(arg) && arg.length) {\n\t\t\t\tvar inner = classNames.apply(null, arg);\n\t\t\t\tif (inner) {\n\t\t\t\t\tclasses.push(inner);\n\t\t\t\t}\n\t\t\t} else if (argType === 'object') {\n\t\t\t\tfor (var key in arg) {\n\t\t\t\t\tif (hasOwn.call(arg, key) && arg[key]) {\n\t\t\t\t\t\tclasses.push(key);\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t}\n\t\t}\n\n\t\treturn classes.join(' ');\n\t}\n\n\tif (typeof module !== 'undefined' && module.exports) {\n\t\tclassNames.default = classNames;\n\t\tmodule.exports = classNames;\n\t} else if (true) {\n\t\t// register as 'classnames', consistent with npm package name\n\t\t!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {\n\t\t\treturn classNames;\n\t\t}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),\n\t\t\t\t__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));\n\t} else {}\n}());\n\n\n//# sourceURL=webpack:///./node_modules/classnames/index.js?");

/***/ }),

/***/ 0:
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./blocks/about-author/index.js ./blocks/click-to-tweet/index.js ./blocks/contact-form/index.js ./blocks/google-map/index.js ./blocks/notice/index.js ./blocks/our-services/index.js ./blocks/post-grid/index.js ./blocks/pricing-table/index.js ./blocks/share-icons/index.js ./blocks/testimonials/index.js ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("__webpack_require__(/*! ./blocks/about-author/index.js */\"./blocks/about-author/index.js\");\n__webpack_require__(/*! ./blocks/click-to-tweet/index.js */\"./blocks/click-to-tweet/index.js\");\n__webpack_require__(/*! ./blocks/contact-form/index.js */\"./blocks/contact-form/index.js\");\n__webpack_require__(/*! ./blocks/google-map/index.js */\"./blocks/google-map/index.js\");\n__webpack_require__(/*! ./blocks/notice/index.js */\"./blocks/notice/index.js\");\n__webpack_require__(/*! ./blocks/our-services/index.js */\"./blocks/our-services/index.js\");\n__webpack_require__(/*! ./blocks/post-grid/index.js */\"./blocks/post-grid/index.js\");\n__webpack_require__(/*! ./blocks/pricing-table/index.js */\"./blocks/pricing-table/index.js\");\n__webpack_require__(/*! ./blocks/share-icons/index.js */\"./blocks/share-icons/index.js\");\nmodule.exports = __webpack_require__(/*! ./blocks/testimonials/index.js */\"./blocks/testimonials/index.js\");\n\n\n//# sourceURL=webpack:///multi_./blocks/about-author/index.js_./blocks/click-to-tweet/index.js_./blocks/contact-form/index.js_./blocks/google-map/index.js_./blocks/notice/index.js_./blocks/our-services/index.js_./blocks/post-grid/index.js_./blocks/pricing-table/index.js_./blocks/share-icons/index.js_./blocks/testimonials/index.js?");

/***/ })

/******/ });