/**
 * Internal dependencies
 */
import Mystock from './components/index';

/**
 * WordPress dependencies
 */
const { registerPlugin } = wp.plugins;

registerPlugin( 'mystock-images', {
	render: Mystock
});
