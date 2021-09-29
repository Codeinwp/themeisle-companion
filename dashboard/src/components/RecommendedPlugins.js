/* global obfxDash */
import { PluginsContext } from './DashboardContext';
import PluginCard from './PluginCard';
import { useState } from '@wordpress/element';

const RecommendedPlugins = () => {
	const { plugins } = obfxDash;
	const [pluginsData, setPluginsData] = useState(plugins);

	if (!plugins) {
		return null;
	}

	return (
		<PluginsContext.Provider value={{ pluginsData, setPluginsData }}>
			<div className="plugins-grid">
				{Object.keys(plugins).map((slug) => {
					return (
						<PluginCard
							key={slug}
							slug={slug}
							data={plugins[slug]}
						/>
					);
				})}
			</div>
		</PluginsContext.Provider>
	);
};

export default RecommendedPlugins;
