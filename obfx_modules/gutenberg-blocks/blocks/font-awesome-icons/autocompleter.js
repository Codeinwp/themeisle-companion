/**
 * WordPress dependencies
 */
const { apiRequest } = wp;

/**
 * A user mentions completer.
 *
 * @type {Completer}
 */
const iconsAutocompleter = {
	name: 'font-awesome-icons',
	className: 'editor-autocompleters__font-awesome-icons',
	triggerPrefix: 'f',
	options( search ) {
		let payload = '';

		if ( search ) {
			payload = '?search=' + encodeURIComponent( search );
		}

		//return apiRequest( { path: '/wp/v2/users' + payload } );
		const vals = _.values( apiRequest( { path: `obfx-font-awesome-icons/v1/get_icons_list` } ) );

		console.debug( vals )

		return vals

	},
	isDebounced: true,
	getOptionKeywords( option ) {
		console.log( option)
		return [ option.icon ];
	},
	getOptionLabel( icon ) { console.log(icon)
		return [
			<span key="name" className="editor-autocompleters__user-name"><i className={ ' fa-' + icon.icon }></i></span>,
			<span key="slug" className="editor-autocompleters__user-slug">{ icon.icon }</span>,
		];
	},
	allowNode() {
		return true;
	},
	getOptionCompletion( icon ) {
		return `f${ icon.icon }`;
	},
};

// Our filter function
const aaa = function( completers, blockName ) {

	console.log( 'aaaa ')

	console.log( completers )

	console.log( blockName )

	return blockName === 'orbitfox/font-awesome-icons' ?
		[ iconsAutocompleter ] :
		completers;
};

// Adding the filter
wp.hooks.addFilter(
	'editor.Autocomplete.completers',
	'editor/autocompleters/font-awesome-icons',
	aaa
);

export default iconsAutocompleter