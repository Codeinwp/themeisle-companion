/**
 * Internal dependencies
 */
import Mystock from './components/index';

/**
 * WordPress dependencies
 */
const { registerPlugin } = wp.plugins;
const { Icon } = wp.components;

registerPlugin( 'mystock-images', {
	icon: <Icon icon="camera"/>,
	render: Mystock
});
