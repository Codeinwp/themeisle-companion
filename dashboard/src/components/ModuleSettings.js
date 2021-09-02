/* global obfxDash */
import { useContext, useState } from '@wordpress/element';
import { ModulesContext } from './DashboardContext';
import { requestData } from '../utils/rest';
import { Dashicon, Button } from '@wordpress/components';
import classnames from 'classnames';
import _ from 'lodash';
import { __ } from '@wordpress/i18n';
import { renderOption } from '../utils/common';

const { options, root, setSettingsRoute } = obfxDash;

const ModuleSettings = ({ slug }) => {
	const { modulesData, setModulesData } = useContext(ModulesContext);
	const [open, setOpen] = useState(false);
	const [loading, setLoading] = useState(false);
	const [errorState, setErrorState] = useState(false);
	const moduleSettings = modulesData.module_settings[slug] || {};
	const [tempData, setTempData] = useState({
		...moduleSettings,
	});

	const loadingIcon = (
		<Dashicon size={18} icon="update" className="is-loading" />
	);

	if (errorState) {
		setTimeout(() => setErrorState(false), 2500);
	}

	const changeOption = (name, newValue) => {
		const newTemp = tempData;
		newTemp[name] = newValue;
		setTempData({ ...newTemp });
	};

	const sendData = () => {
		setLoading(true);

		const dataToSend = {
			slug,
			value: tempData,
		};

		requestData(root + setSettingsRoute, dataToSend).then((r) => {
			if (r.type !== 'success') {
				setTempData({ ...moduleSettings });
				setLoading(false);
				setErrorState(true);
				return;
			}

			modulesData.module_settings[slug] = { ...tempData };
			setModulesData({ ...modulesData });
			setLoading(false);
		});
	};

	const getContent = () => {
		const content = [];

		for (let i = 0; i < options[slug].length; i++) {
			let element = options[slug][i];
			if (element.title && element.label) {
				content.push(<p className="title"> {element.title} </p>);
			}

			if (element.hasOwnProperty('before_wrap')) {
				const row = [];
				const active =
					tempData[element.id] === '1' ||
					(!tempData[element.id] && element.default === '1');

				while (true) {
					row.push(renderOption(element, tempData, changeOption));
					if (element.hasOwnProperty('after_wrap')) break;
					element = options[slug][++i];
				}

				const classes = classnames([
					'settings-row',
					active && 'active',
				]);
				content.push(<div className={classes}> {row} </div>);
				continue;
			}

			content.push(renderOption(element, tempData, changeOption));
		}

		return content;
	};

	return (
		<div
			className={classnames([
				'module-settings',
				open ? 'open' : 'closed',
			])}
		>
			<button
				aria-expanded={open}
				className="accordion-header"
				onClick={() => setOpen(!open)}
			>
				<div className="accordion-title"> Settings </div>
				<Dashicon icon={open ? 'arrow-up-alt2' : 'arrow-down-alt2'} />
			</button>
			{open && (
				<div
					className={classnames([
						'accordion-content',
						loading ? 'loading' : '',
					])}
				>
					{getContent()}
					<div className="buttons-container">
						<Button
							isSecondary
							className="obfx-button"
							onClick={() => setOpen(false)}
						>
							{__('Close', 'themeisle-companion')}
						</Button>
						<Button
							isPrimary
							disabled={_.isEqual(tempData, moduleSettings)}
							className="obfx-button"
							onClick={sendData}
						>
							{loading
								? loadingIcon
								: __('Save', 'themeisle-companion')}
						</Button>
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
			)}
		</div>
	);
};

export default ModuleSettings;
