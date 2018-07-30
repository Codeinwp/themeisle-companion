import { stringify } from 'querystringify'
const { data, apiRequest } = wp;
const { registerStore, dispatch } = data;

const DEFAULT_STATE = {
	storePostId: 0,
};

registerStore( 'obfx/blocks', {
	reducer( state = DEFAULT_STATE, action ) {

		switch ( action.type ) {
			case 'INIT_FORM':
				return {
					...state,
					storePostId: action.storePostId
				};

			case 'GET_POST_META':
				return {
					...state,
					data: action
				};

			case 'UPDATE_POST_META':
				return {
					...state,
					key: action.key,
					value: action.value,
				};

			case 'GET_ICONS_LIST':
				return {
					iconsList: action.data,
				};
		}

		return state;
	},

	actions: {
		setInitID( title ) {
			return {
				type: 'INIT_FORM',
				storePostId: title
			};
		},
		updatePostMeta( postID, key, value ) {
			return {
				type: 'UPDATE_POST_META',
				key: key,
				value: value
			};
		},
		setFaIconsList(data) {
			return {
				type: 'GET_ICONS_LIST',
				data: data
			}
		}
	},

	selectors: {
		dispatchInit(id){
			return id
		},
		getPostMeta(data){
			return data
		},
		getFaIconsList( data ) {

			if ( typeof data.iconsList !== "undefined" ) {
				//let x = Object.keys(data.iconsList).map(function (key) { return data.iconsList[key]; });


				return data.iconsList

				// return Object.keys( data.iconsList ).map(k =>  data.iconsList[k])
			}

		}
	},

	resolvers: {
		async dispatchInit( state, title ) {
			const query = stringify( _.pick( {
				title: title,
			}, ( value ) => ! _.isUndefined( value ) ) );

			const result = await apiRequest( { path: `/wp/v2/obfx_contact_form?${query}`, method: 'POST' } );
			dispatch( 'obfx/blocks' ).setInitID( result.id );
			//return result.id
		},

		async getPostMeta( state, id ) {
			const query = stringify( _.pick( {
				_fields: [ 'form_data' ],
			}, ( value ) => ! _.isUndefined( value ) ) );

			const result = await apiRequest( { path: `/wp/v2/obfx_contact_form/${id}?${query}` } );

			// @TODO call an action here to set the meta
		},
		async getFaIconsList() {
			let result = []

			result = await apiRequest( { path: `obfx-font-awesome-icons/v1/get_icons_list` } );

			dispatch( 'obfx/blocks' ).setFaIconsList( result );
		}
	},
} );