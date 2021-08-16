import { __ } from '@wordpress/i18n';
import AvailableModules from '../components/AvailableModules';
import RecommendedPlugins from '../components/RecommendedPlugins';

export const tabs = {
	modules: {
		label: __( 'Available Modules', 'themeisle-companion' ),
		render: () => <AvailableModules />,
	},
	plugins: {
		label: __( 'Recommended Plugins', 'themeisle-companion' ),
		render: () => <RecommendedPlugins />,
	},
};
