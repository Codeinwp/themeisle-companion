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
		}
	},

	selectors: {
		dispatchInit(id){
			return id
		},
		getPostMeta(data){
			return data
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
			//return result
		}
	},
} );