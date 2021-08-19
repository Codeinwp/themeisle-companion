/* global obfxDash */
import ModuleCard from './ModuleCard';

const { modules, data } = obfxDash;

const AvailableModules = () => {
	// eslint-disable-next-line camelcase
	const { module_status } = data;

	const renderModules = () => {
		return Object.entries( modules )
			.filter( ( [ slug ] ) => {
				return (
					module_status[ slug ] &&
					module_status[ slug ].hasOwnProperty( 'active' )
				);
			} )
			.map( ( [ slug, details ] ) => {
				return (
					<ModuleCard
						slug={ slug }
						details={ details }
						key={ slug }
					/>
				);
			} );
	};

	return <div className="modules-container">{ renderModules() }</div>;
};

export default AvailableModules;
