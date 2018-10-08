const { data, apiRequest } = wp;
const { registerStore, dispatch } = data;

const DEFAULT_STATE = {};

registerStore( 'obfx/blocks', {
	reducer( state = DEFAULT_STATE, action ) {

		switch ( action.type ) {
			case 'GET_ICONS_LIST':
				return {
					iconsList: action.data,
				};
		}

		return state;
	},

	actions: {
		setFaIconsList(data) {
			return {
				type: 'GET_ICONS_LIST',
				data: data
			}
		}
	},

	selectors: {
		getFaIconsList( data ) {
			if ( typeof data.iconsList !== "undefined" ) {
				return data.iconsList
			}
		}
	},

	resolvers: {
		async getFaIconsList() {
			let result = []

			result = await apiRequest( { path: `obfx-font-awesome-icons/v1/get_icons_list` } );

			dispatch( 'obfx/blocks' ).setFaIconsList( result );
		}
	},
} );