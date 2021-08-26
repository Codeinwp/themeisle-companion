/* global obfxDash */
import { useContext } from '@wordpress/element';
import { ModulesContext } from './DashboardContext';

const { options, modules } = obfxDash;

const ModuleSettings = ( { slug } ) => {
	const { modulesData, setModulesData } = useContext( ModulesContext );
	// eslint-disable-next-line camelcase
	const { module_status, module_settings } = modulesData;

	console.log( options[ slug ] );

	return null;
};

export default ModuleSettings;
