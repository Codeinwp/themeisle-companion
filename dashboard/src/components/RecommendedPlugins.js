/* global obfxDash */
import PluginCard from './PluginCard';

const RecommendedPlugins = () => {
	const { plugins } = obfxDash;
	console.log( plugins );

	if (!plugins) {
		return null;
	}

	return (
		<div className="plugins-grid">
			{Object.keys(plugins).map((slug) => {
				return (
					<PluginCard key={slug} slug={slug} data={plugins[slug]} />
				);
			})}
		</div>
	);
};

export default RecommendedPlugins;
