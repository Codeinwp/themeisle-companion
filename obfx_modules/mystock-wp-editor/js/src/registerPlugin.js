/**
 * WordPress dependencies
 */
import Mystock from './components/index';

const { Fragment } = wp.element;
const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { registerPlugin } = wp.plugins;

const MystockPhotos = () => (
	<Fragment>
		<Mystock/>
	</Fragment>
);

registerPlugin( 'mystock-images', {
	render: MystockPhotos
});
