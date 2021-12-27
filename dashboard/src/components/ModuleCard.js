/* global obfxDash */
import { DashboardContext, ModulesContext } from './DashboardContext';
import ModuleSettings from './ModuleSettings';
import { requestData } from '../utils/rest';

import { Dashicon, ExternalLink, ToggleControl } from '@wordpress/components';
import { renderToString, useContext, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

const { root, toggleStateRoute, options } = obfxDash;

const ModuleCard = ({ slug, details }) => {
	const refreshAfterEnabled = details.refresh_after_enabled;
	const activeDefault = details.active_default;
	const [loading, setLoading] = useState(false);
	const { modulesData, setModulesData } = useContext(ModulesContext);
	const { setToast } = useContext(DashboardContext);
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
			setLoading(false);
			setToast(
				__(
					'Could not activate module. Please try again.',
					'themeisle-companion'
				)
			);
			return;
		}

		if (refreshAfterEnabled) {
			window.location.reload();
		}

		if (!moduleStatus[slug]) {
			moduleStatus[slug] = {};
		}

		moduleStatus[slug].active = value;
		setModulesData(modulesData);
		setLoading(false);
		setToast(
			(value
				? __('Module activated', 'themeisle-companion')
				: __('Module deactivated', 'themeisle-companion')) +
				` (${details.name})`
		);
	};

	const renderDescription = (description) => {
		const elements = [];
		if (description.indexOf('neve-pro-notice') >= 0) {
			return (
				<p
					className="description"
					dangerouslySetInnerHTML={{ __html: description }}
				/>
			);
		}

		while (description.indexOf('<a') >= 0) {
			const start = description.indexOf('<a');
			const end = description.indexOf('</a>');

			elements.push(description.slice(0, start));

			const hrefStart = description.indexOf('href="') + 'href="'.length;
			const hrefEnd = description.indexOf('"', hrefStart);
			const href = description.slice(hrefStart, hrefEnd);

			const anchorText = description.slice(
				description.indexOf('>', start) + 1,
				end
			);

			elements.push(
				renderToString(
					<ExternalLink href={href}>{anchorText}</ExternalLink>
				)
			);

			description = description.slice(end + '</a>'.length);
		}

		elements.push(description);
		return (
			<p
				className="description"
				dangerouslySetInnerHTML={{ __html: elements.join(' ') }}
			/>
		);
	};

	const isActive = moduleStatus[slug] && moduleStatus[slug].active !== undefined
		? moduleStatus[slug].active
		: activeDefault;
	const classes = classnames('module-card', {'active': isActive});

	return (
		<div className={classes}>
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
						checked={isActive}
						onChange={updateModuleStatus}
					/>
				</div>
			</div>
			<div className="module-card-content">
				{renderDescription(details.description)}
			</div>
			{isActive && options[slug].length > 0 && <ModuleSettings slug={slug} />}
		</div>
	);
};

export default ModuleCard;
