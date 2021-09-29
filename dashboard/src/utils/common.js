import AvailableModules from '../components/AvailableModules';
import RecommendedPlugins from '../components/RecommendedPlugins';
import { post } from './rest';

import {
	Button,
	CheckboxControl,
	RadioControl,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export const tabs = {
	modules: {
		label: __('Available Modules', 'themeisle-companion'),
		render: () => <AvailableModules />,
	},
	plugins: {
		label: __('Recommended Plugins', 'themeisle-companion'),
		render: () => <RecommendedPlugins />,
	},
};

export const getTabHash = () => {
	let hash = window.location.hash;

	if ('string' !== typeof window.location.hash) {
		return null;
	}

	hash = hash.substring(1);

	if (!Object.keys(tabs).includes(hash)) {
		return null;
	}

	return hash;
};

const unregister = (url, setToast) => {
	post(url, 'deactivate=unregister').then((r) => {
		if (r === false) {
			setToast(
				__(
					'Could not unregister the site. Please try again.',
					'themeisle-companion'
				)
			);
			return;
		}
		window.location.reload();
	});
};

/**
 * Decodes a html encoded string while preserving tags
 *
 * @param {string} html encoded string
 * @return {string} decoded string
 */
const decodeHtml = (html) => {
	const txt = document.createElement('textarea');
	txt.innerHTML = html;
	return txt.value;
};

export const renderOption = (setting, tempData, changeOption, setToast) => {
	const selectedValue =
		tempData[setting.id] !== undefined
			? tempData[setting.id]
			: setting.default;

	switch (setting.type) {
		case 'checkbox':
			return (
				<CheckboxControl
					label={setting.label}
					checked={selectedValue === '1'}
					onChange={(newValue) =>
						changeOption(setting.id, newValue ? '1' : '0')
					}
				/>
			);
		case 'radio':
			return (
				<RadioControl
					label={setting.title}
					options={setting.options.map((label, value) => {
						return { label, value };
					})}
					selected={parseInt(selectedValue)}
					onChange={(newValue) => changeOption(setting.id, newValue)}
				/>
			);
		case 'toggle':
			return (
				<ToggleControl
					label={
						<div
							dangerouslySetInnerHTML={{
								__html: setting.label,
							}}
						/>
					}
					checked={selectedValue === '1'}
					onChange={(newValue) =>
						changeOption(setting.id, newValue ? '1' : '0')
					}
				/>
			);
		case 'select':
			return (
				<div className="select-wrap">
					<SelectControl
						label={setting.title}
						value={selectedValue}
						options={Object.entries(setting.options).map(
							([value, label]) => {
								return { value, label };
							}
						)}
						onChange={(newValue) =>
							changeOption(setting.id, newValue)
						}
					/>
				</div>
			);
		case 'text':
			return (
				<TextControl
					label={setting.title}
					value={decodeHtml(selectedValue)}
					onChange={(newValue) => changeOption(setting.id, newValue)}
				/>
			);
		case 'link':
			const isUnregister = setting.id === 'analytics_accounts_unregister';
			return (
				<div className="select-wrap">
					<Button
						isPrimary={!isUnregister}
						isDestructive={isUnregister}
						href={setting.url ? setting.url : null}
						onClick={
							isUnregister &&
							(() => {
								unregister(setting.unregisterURL, setToast);
							})
						}
					>
						<div
							dangerouslySetInnerHTML={{ __html: setting.text }}
						/>
					</Button>
				</div>
			);
	}
};
