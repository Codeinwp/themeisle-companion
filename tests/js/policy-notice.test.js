const fs = require( 'fs' );
const path = require( 'path' );

describe( 'cookie notice bar script', () => {
	const source = fs.readFileSync(
		path.resolve( __dirname, '../../obfx_modules/policy-notice/init.php' ),
		'utf8'
	);
	// The script is inline in the PHP callback that prints it.
	const script = source
		.split( 'public function wp_print_footer_scripts()' )[ 1 ]
		.match( /<script>([\s\S]*?)<\/script>/ )[ 1 ];

	const runScript = () => window.eval( script );

	afterEach( () => {
		document.body.innerHTML = '';
	} );

	it( 'does nothing on a page without the bar', () => {
		expect( runScript ).not.toThrow();
	} );

	it( 'shows a filtered bar that has no accept button', () => {
		document.body.innerHTML = '<div id="obfx-cookie-bar" style="display: none"><span>Notice</span></div>';

		expect( runScript ).not.toThrow();
		expect( document.getElementById( 'obfx-cookie-bar' ).style.display ).toBe( 'block' );
	} );
} );
