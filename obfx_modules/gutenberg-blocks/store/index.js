const { data, apiRequest } = wp;
const { registerStore, dispatch } = data;

const DEFAULT_STATE = {
	prices: {},
	discountPercent: 0,
};

registerStore( 'obfx/blocks', {
	reducer( state = DEFAULT_STATE, action ) {
		switch ( action.type ) {
			case 'INIT_FORM':
				console.log( state )
				return {
					...state,
					id: action.id
				};
		}

		return state;
	},

	actions: {
		initForm( id ) {
			return {
				type: 'INIT_FORM',
				id
			};
		}
	},

	selectors: {
		initForm(id){
			return id
		}
	},

	resolvers: {
		async initForm( title ) {
				console.log( title )
				// const id = await apiRequest( { path: '/wp/v2/obfx_contact_form/', method: 'POST' } );
			// dispatch( 'obfx/blocks' ).initForm( id );
		},
	},
} );