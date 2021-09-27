/* global obfxDash */
import { ModulesContext } from './DashboardContext';
import ModuleSettings from './ModuleSettings';
import { requestData } from '../utils/rest';

import { Dashicon, ExternalLink, ToggleControl } from '@wordpress/components';
import { useContext, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const { root, toggleStateRoute, options } = obfxDash;

const ModuleCard = ({ slug, details }) => {
	const refreshAfterEnabled = details.refresh_after_enabled;
	const activeDefault = details.active_default;
	const [loading, setLoading] = useState(false);
	const [errorState, setErrorState] = useState(false);
	const { modulesData, setModulesData } = useContext(ModulesContext);
	const moduleStatus = modulesData.module_status;

	const updateModuleStatus = (value) => {
		setLoading(true);

		const dataToSend = { slug, value };
		requestData(root + toggleStateRoute, false, dataToSend).then((r) => {
			toggleStatusCallback(r, value);
		});
	};

	const toggleStatusCallback = (r, value) => {
		if (r.type !== 'success') {
			setErrorState(true);
			setLoading(false);
			return;
		}

		if (refreshAfterEnabled) {
			window.location.reload(false);
		}

		if (!moduleStatus[slug]) {
			moduleStatus[slug] = {};
		}

		moduleStatus[slug].active = value;
		setModulesData(modulesData);
		setLoading(false);
	};

	if (errorState) {
		setTimeout(() => setErrorState(false), 2500);
	}

	const handleDescription = (description) => {
		const start = description.indexOf('<a');
		const end = description.indexOf('</a>');

		if (start === -1) {
			return <div className="description">{description}</div>;
		}

		const hrefStart = description.indexOf('href="') + 'href="'.length;
		const hrefEnd = description.indexOf('"', hrefStart);
		const href = description.slice(hrefStart, hrefEnd);

		const anchorText = description.slice(description.indexOf('>') + 1, end);

		return (
			<div className="description">
				{description.slice(0, start)}
				<ExternalLink href={href}>{anchorText}</ExternalLink>
				{description.slice(end + '</a>'.length)}
			</div>
		);
	};

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
								: activeDefault
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
				{handleDescription(details.description)}
			</div>
			{moduleStatus[slug] &&
				moduleStatus[slug].active &&
				options[slug].length > 0 && <ModuleSettings slug={slug} />}
		</div>
	);
};

export default ModuleCard;
