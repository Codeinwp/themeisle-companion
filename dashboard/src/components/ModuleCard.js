/* global obfxDash */
import { Dashicon, ToggleControl } from '@wordpress/components';
import { requestData } from '../utils/rest';
import { useContext, useState } from '@wordpress/element';
import { ModulesContext } from './DashboardContext';
import ModuleSettings from './ModuleSettings';
import { __ } from '@wordpress/i18n';

const { root, toggleStateRoute, options } = obfxDash;

const ModuleCard = ({ slug, details }) => {
	const [loading, setLoading] = useState(false);
	const [errorState, setErrorState] = useState(false);
	const { modulesData, setModulesData } = useContext(ModulesContext);
	const moduleStatus = modulesData.module_status;

	const updateModuleStatus = (value) => {
		setLoading(true);

		const dataToSend = { slug, value };
		requestData(root + toggleStateRoute, false, dataToSend).then((r) => {
			if (r.type !== 'success') {
				setErrorState(true);
				setLoading(false);
				return;
			}

			if (!moduleStatus[slug]) {
				moduleStatus[slug] = {};
			}

			moduleStatus[slug].active = value;
			setModulesData(modulesData);
			setLoading(false);
		});
	};

	if (errorState) {
		setTimeout(() => setErrorState(false), 2500);
	}

	return (
		<div className="module-card">
			<div className="module-card-header">
				<h3 className="title">{details.name}</h3>
				<div className="toggle-wrap">
					{loading && (
						<Dashicon
							size={18}
							icon="update"
							className="is-loading"
						/>
					)}
					<ToggleControl
						checked={
							moduleStatus[slug]
								? moduleStatus[slug].active
								: false
						}
						onChange={updateModuleStatus}
					/>
					{errorState && (
						<p className="error">
							{__(
								'Something went wrong! Try again.',
								'themeisle-companion'
							)}
						</p>
					)}
				</div>
			</div>
			<div className="module-card-content">
				<div
					className="description"
					dangerouslySetInnerHTML={{ __html: details.description }}
				/>
			</div>
			{moduleStatus[slug] &&
				moduleStatus[slug].active &&
				options[slug].length > 0 && <ModuleSettings slug={slug} />}
		</div>
	);
};

export default ModuleCard;