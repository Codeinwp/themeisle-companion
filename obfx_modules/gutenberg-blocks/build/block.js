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
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\r\n * WordPress dependencies...\r\n */\nvar __ = wp.i18n.__;\nvar registerBlockType = wp.blocks.registerBlockType;\n\n\nregisterBlockType('orbitfox/notice', {\n  title: __('Notice'),\n  icon: 'info',\n  category: 'common',\n  keywords: ['notice', 'info', 'orbitfox'],\n\n  edit: function edit() {\n    return null;\n  },\n  save: function save() {\n    return null;\n  }\n});\n\n//# sourceURL=webpack:///./blocks/notice/index.js?");

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

/***/ 0:
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** multi ./blocks/about-author/index.js ./blocks/click-to-tweet/index.js ./blocks/contact-form/index.js ./blocks/google-map/index.js ./blocks/notice/index.js ./blocks/our-services/index.js ./blocks/post-grid/index.js ./blocks/pricing-table/index.js ./blocks/share-icons/index.js ./blocks/testimonials/index.js ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("__webpack_require__(/*! ./blocks/about-author/index.js */\"./blocks/about-author/index.js\");\n__webpack_require__(/*! ./blocks/click-to-tweet/index.js */\"./blocks/click-to-tweet/index.js\");\n__webpack_require__(/*! ./blocks/contact-form/index.js */\"./blocks/contact-form/index.js\");\n__webpack_require__(/*! ./blocks/google-map/index.js */\"./blocks/google-map/index.js\");\n__webpack_require__(/*! ./blocks/notice/index.js */\"./blocks/notice/index.js\");\n__webpack_require__(/*! ./blocks/our-services/index.js */\"./blocks/our-services/index.js\");\n__webpack_require__(/*! ./blocks/post-grid/index.js */\"./blocks/post-grid/index.js\");\n__webpack_require__(/*! ./blocks/pricing-table/index.js */\"./blocks/pricing-table/index.js\");\n__webpack_require__(/*! ./blocks/share-icons/index.js */\"./blocks/share-icons/index.js\");\nmodule.exports = __webpack_require__(/*! ./blocks/testimonials/index.js */\"./blocks/testimonials/index.js\");\n\n\n//# sourceURL=webpack:///multi_./blocks/about-author/index.js_./blocks/click-to-tweet/index.js_./blocks/contact-form/index.js_./blocks/google-map/index.js_./blocks/notice/index.js_./blocks/our-services/index.js_./blocks/post-grid/index.js_./blocks/pricing-table/index.js_./blocks/share-icons/index.js_./blocks/testimonials/index.js?");

/***/ })

/******/ });