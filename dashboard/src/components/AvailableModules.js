/* global obfxDash */
import ModuleCard from './ModuleCard';
import { ModulesContext } from './DashboardContext';
import { useState } from '@wordpress/element';

const { modules, data } = obfxDash;

const AvailableModules = () => {
	const [modulesData, setModulesData] = useState(
		data === '' ? { module_status: {}, module_settings: {} } : { ...data }
	);

	if (!modulesData.module_settings) {
		setModulesData({ ...modulesData, module_settings: {} });
	}

	if (!modulesData.module_status) {
		setModulesData({ ...modulesData, module_status: {} });
	}

	const renderModules = () => {
		return Object.entries(modules).map(([slug, details]) => {
			return <ModuleCard slug={slug} details={details} key={slug} />;
		});
	};

	return (
		<ModulesContext.Provider value={{ modulesData, setModulesData }}>
			<div className="modules-container">{renderModules()}</div>
		</ModulesContext.Provider>
	);
};

export default AvailableModules;
