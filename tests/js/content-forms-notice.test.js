const fs = require( 'fs' );
const path = require( 'path' );

describe( 'content form notices', () => {
	let submitHandler;
	let $;

	class JQueryFacade {
		constructor( elements ) {
			this.elements = elements;
			this.length = elements.length;
			elements.forEach( ( element, index ) => {
				this[ index ] = element;
			} );
		}

		on( event, selector, handler ) {
			if ( event === 'submit' && selector === 'form.content-form' ) {
				submitHandler = handler;
			}
			return this;
		}

		addClass( className ) {
			this.elements.forEach( ( element ) => element.classList.add( className ) );
			return this;
		}

		removeClass( className ) {
			this.elements.forEach( ( element ) => element.classList.remove( className ) );
			return this;
		}

		find( selector ) {
			return new JQueryFacade(
				this.elements.flatMap( ( element ) => [ ...element.querySelectorAll( selector ) ] )
			);
		}

		text( value ) {
			this.elements.forEach( ( element ) => {
				element.textContent = value;
			} );
			return this;
		}

		append( child ) {
			this.elements.forEach( ( element ) => element.append( ...child.elements ) );
			return this;
		}

		attr( name, value ) {
			this.elements.forEach( ( element ) => element.setAttribute( name, value ) );
			return this;
		}

		replaceWith( replacement ) {
			this.elements.forEach( ( element ) => element.replaceWith( ...replacement.elements ) );
			return this;
		}

		prepend( child ) {
			this.elements.forEach( ( element ) => element.prepend( ...child.elements ) );
			return this;
		}
	}

	beforeEach( () => {
		document.body.innerHTML = '<form class="content-form"></form>';
		Element.prototype.scrollIntoView = jest.fn();

		$ = ( value, attributes = {} ) => {
			if ( typeof value === 'string' && /^<\w+>$/.test( value ) ) {
				const element = document.createElement( value.slice( 1, -1 ) );
				Object.entries( attributes ).forEach( ( [ name, attributeValue ] ) => {
					element.setAttribute( name, attributeValue );
				} );
				return new JQueryFacade( [ element ] );
			}

			return new JQueryFacade( [ value ] );
		};
		$.fn = JQueryFacade.prototype;
		$.parseJSON = JSON.parse;
		window.jQuery = $;
		window.$ = $;
		window.contentFormsSettings = { restUrl: '/wp-json/content-forms/v1/' };
		window.formStyle = { formStyle: 'style="text-align:center"' };

		const script = fs.readFileSync(
			path.resolve( __dirname, '../../obfx_modules/content-forms/assets/content-forms.js' ),
			'utf8'
		);
		window.eval( script );

		$.fn.serializeJSON = () => ( {
			'form-type': 'contact',
			'form-id': 'form1',
			'post-id': '1',
			'form-builder': 'elementor',
			data: {},
			_wpnonce_contact: 'nonce',
		} );
	} );

	test( 'renders response messages as text while preserving entities', () => {
		$.ajax = ( options ) => options.success( {
			success: true,
			message: 'You&#039;re ready &amp; </textarea><em>set</em>',
		} );

		const form = document.querySelector( 'form' );
		submitHandler.call( form, { preventDefault: jest.fn() } );

		const notice = form.querySelector( '.content-form-notice' );
		expect( notice.textContent ).toBe( "You're ready & </textarea><em>set</em>" );
		expect( notice.querySelector( 'em' ) ).toBeNull();
		expect( notice.style.textAlign ).toBe( 'center' );
	} );
} );
