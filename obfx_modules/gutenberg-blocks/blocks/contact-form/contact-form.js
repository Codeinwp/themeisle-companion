/*!
SerializeJSON jQuery plugin.
https://github.com/marioizquierdo/jquery.serializeJSON
version 2.8.1 (Dec, 2016)

Copyright (c) 2012, 2017 Mario Izquierdo
Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
*/
!function(a){if("function"==typeof define&&define.amd)define(["jquery"],a);else if("object"==typeof exports){var b=require("jquery");module.exports=a(b)}else a(window.jQuery||window.Zepto||window.$)}(function(a){"use strict";a.fn.serializeJSON=function(b){var c,d,e,f,g,h,i,j,k,l,m,n,o;return c=a.serializeJSON,d=this,e=c.setupOpts(b),f=d.serializeArray(),c.readCheckboxUncheckedValues(f,e,d),g={},a.each(f,function(a,b){h=b.name,i=b.value,k=c.extractTypeAndNameWithNoType(h),l=k.nameWithNoType,m=k.type,m||(m=c.attrFromInputWithName(d,h,"data-value-type")),c.validateType(h,m,e),"skip"!==m&&(n=c.splitInputNameIntoKeysArray(l),j=c.parseValue(i,h,m,e),o=!j&&c.shouldSkipFalsy(d,h,l,m,e),o||c.deepSet(g,n,j,e))}),g},a.serializeJSON={defaultOptions:{checkboxUncheckedValue:void 0,parseNumbers:!1,parseBooleans:!1,parseNulls:!1,parseAll:!1,parseWithFunction:null,skipFalsyValuesForTypes:[],skipFalsyValuesForFields:[],customTypes:{},defaultTypes:{string:function(a){return String(a)},number:function(a){return Number(a)},boolean:function(a){var b=["false","null","undefined","","0"];return b.indexOf(a)===-1},null:function(a){var b=["false","null","undefined","","0"];return b.indexOf(a)===-1?a:null},array:function(a){return JSON.parse(a)},object:function(a){return JSON.parse(a)},auto:function(b){return a.serializeJSON.parseValue(b,null,null,{parseNumbers:!0,parseBooleans:!0,parseNulls:!0})},skip:null},useIntKeysAsArrayIndex:!1},setupOpts:function(b){var c,d,e,f,g,h;h=a.serializeJSON,null==b&&(b={}),e=h.defaultOptions||{},d=["checkboxUncheckedValue","parseNumbers","parseBooleans","parseNulls","parseAll","parseWithFunction","skipFalsyValuesForTypes","skipFalsyValuesForFields","customTypes","defaultTypes","useIntKeysAsArrayIndex"];for(c in b)if(d.indexOf(c)===-1)throw new Error("serializeJSON ERROR: invalid option '"+c+"'. Please use one of "+d.join(", "));return f=function(a){return b[a]!==!1&&""!==b[a]&&(b[a]||e[a])},g=f("parseAll"),{checkboxUncheckedValue:f("checkboxUncheckedValue"),parseNumbers:g||f("parseNumbers"),parseBooleans:g||f("parseBooleans"),parseNulls:g||f("parseNulls"),parseWithFunction:f("parseWithFunction"),skipFalsyValuesForTypes:f("skipFalsyValuesForTypes"),skipFalsyValuesForFields:f("skipFalsyValuesForFields"),typeFunctions:a.extend({},f("defaultTypes"),f("customTypes")),useIntKeysAsArrayIndex:f("useIntKeysAsArrayIndex")}},parseValue:function(b,c,d,e){var f,g;return f=a.serializeJSON,g=b,e.typeFunctions&&d&&e.typeFunctions[d]?g=e.typeFunctions[d](b):e.parseNumbers&&f.isNumeric(b)?g=Number(b):!e.parseBooleans||"true"!==b&&"false"!==b?e.parseNulls&&"null"==b&&(g=null):g="true"===b,e.parseWithFunction&&!d&&(g=e.parseWithFunction(g,c)),g},isObject:function(a){return a===Object(a)},isUndefined:function(a){return void 0===a},isValidArrayIndex:function(a){return/^[0-9]+$/.test(String(a))},isNumeric:function(a){return a-parseFloat(a)>=0},optionKeys:function(a){if(Object.keys)return Object.keys(a);var b,c=[];for(b in a)c.push(b);return c},readCheckboxUncheckedValues:function(b,c,d){var e,f,g,h,i;null==c&&(c={}),i=a.serializeJSON,e="input[type=checkbox][name]:not(:checked):not([disabled])",f=d.find(e).add(d.filter(e)),f.each(function(d,e){if(g=a(e),h=g.attr("data-unchecked-value"),null==h&&(h=c.checkboxUncheckedValue),null!=h){if(e.name&&e.name.indexOf("[][")!==-1)throw new Error("serializeJSON ERROR: checkbox unchecked values are not supported on nested arrays of objects like '"+e.name+"'. See https://github.com/marioizquierdo/jquery.serializeJSON/issues/67");b.push({name:e.name,value:h})}})},extractTypeAndNameWithNoType:function(a){var b;return(b=a.match(/(.*):([^:]+)$/))?{nameWithNoType:b[1],type:b[2]}:{nameWithNoType:a,type:null}},shouldSkipFalsy:function(b,c,d,e,f){var g=a.serializeJSON,h=g.attrFromInputWithName(b,c,"data-skip-falsy");if(null!=h)return"false"!==h;var i=f.skipFalsyValuesForFields;if(i&&(i.indexOf(d)!==-1||i.indexOf(c)!==-1))return!0;var j=f.skipFalsyValuesForTypes;return null==e&&(e="string"),!(!j||j.indexOf(e)===-1)},attrFromInputWithName:function(a,b,c){var d,e,f;return d=b.replace(/(:|\.|\[|\]|\s)/g,"\\$1"),e='[name="'+d+'"]',f=a.find(e).add(a.filter(e)),f.attr(c)},validateType:function(b,c,d){var e,f;if(f=a.serializeJSON,e=f.optionKeys(d?d.typeFunctions:f.defaultOptions.defaultTypes),c&&e.indexOf(c)===-1)throw new Error("serializeJSON ERROR: Invalid type "+c+" found in input name '"+b+"', please use one of "+e.join(", "));return!0},splitInputNameIntoKeysArray:function(b){var c,d;return d=a.serializeJSON,c=b.split("["),c=a.map(c,function(a){return a.replace(/\]/g,"")}),""===c[0]&&c.shift(),c},deepSet:function(b,c,d,e){var f,g,h,i,j,k;if(null==e&&(e={}),k=a.serializeJSON,k.isUndefined(b))throw new Error("ArgumentError: param 'o' expected to be an object or array, found undefined");if(!c||0===c.length)throw new Error("ArgumentError: param 'keys' expected to be an array with least one element");f=c[0],1===c.length?""===f?b.push(d):b[f]=d:(g=c[1],""===f&&(i=b.length-1,j=b[i],f=k.isObject(j)&&(k.isUndefined(j[g])||c.length>2)?i:i+1),""===g?!k.isUndefined(b[f])&&a.isArray(b[f])||(b[f]=[]):e.useIntKeysAsArrayIndex&&k.isValidArrayIndex(g)?!k.isUndefined(b[f])&&a.isArray(b[f])||(b[f]=[]):!k.isUndefined(b[f])&&k.isObject(b[f])||(b[f]={}),h=c.slice(1),k.deepSet(b[f],h,d,e))}}});

(function ($) {
	/**
	 * Watch for all the content forms and listen for a submit action
	 */
	$(document).on('submit', 'form.obfx-contact-form', function (e) {
		e.preventDefault();

		var $form = $(this),
			serialized = $form.serializeJSON();

		console.log( serialized )
		var postData = {
				form_id: serialized['form-store-id'],
				data: serialized,
				nonce: window.obfxContactFormsSettings.nonce,
			};

		$form.addClass('content-form-loading');

		window.jQuery.ajax({
			url: window.obfxContactFormsSettings.restUrl + `submit`,
			dataType: 'json',
			method: 'POST',
			data: postData,
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', window.obfxContactFormsSettings.nonce);
			},
			success: function (data) {
				$form.removeClass('content-form-loading');

				var status = 'error';

				if ( typeof data.success !== "undefined" && data.success ) {
					status = 'success';
				}

				addContentFormNotice( data.msg, status, $form );
			},
			error: function (e) {
				$form.removeClass('content-form-loading');

				addContentFormNotice( e, 'error', $form );

				console.error(e)
			}
		});
	});

/**
 * Handle Form notices
 * @param notice
 * @param type
 * @param $form
 */
var addContentFormNotice = function ( notice, type, $form ) {
		var	noticeStatus = '',
			$currentNotice = $form.children( '.content-form-notice' );

		if ( 'success' === type ) {
			noticeStatus = 'content-form-success';
		} else {
			noticeStatus = 'content-form-error';
		}

		var noticeEl = "<h3 class='content-form-notice " + noticeStatus + "' >" + notice + "</h3>";

		// if an notice already exist, replace it; otherwise create one
		if ( $currentNotice.length > 0 ) {
			$currentNotice.replaceWith( noticeEl )
		} else {
			$form.prepend( noticeEl );
		}
	}
})(jQuery);