import classnames from 'classnames';

import { __ } from '@wordpress/i18n';
import { Button, Dashicon } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { get } from '../utils/rest';

const PluginCard = ({ slug, data }) => {
	const { banner, name, description, version, author, action } = data;
	const [inProgress, setInProgress] = useState(false);
	const [errorState, setErrorState] = useState(false);
	const [pluginState, setPluginState] = useState(action);

	if (errorState) {
		setTimeout(() => setErrorState(false), 2500);
	}

	const stringMap = {
		install: {
			static: __('Install', 'neve'),
			progress: __('Installing', 'neve'),
		},
		activate: {
			static: __('Activate', 'neve'),
			progress: __('Activating', 'neve'),
		},
		deactivate: {
			static: __('Deactivate', 'neve'),
			progress: __('Deactivating', 'neve'),
		},
	};

	const handleActionButton = () => {
		setInProgress(true);
		if ('install' === pluginState) {
			installPlugin(slug).then((r) => {
				if (!r.success) {
					setInProgress(false);
					setErrorState(true);
					return;
				}
				setInProgress(false);
				setPluginState('activate');
			});
			return;
		}

		get(data[pluginState], true).then((r) => {
			if (!r.ok) {
				setInProgress(false);
				setErrorState(true);
				return;
			}

			if ('activate' === pluginState) {
				setPluginState('deactivate');
			} else {
				setPluginState('activate');
			}

			setInProgress(false);
		});
	};

	return (
		<div className={classnames(['card', 'plugin', slug])}>
			<div className="card-header">
				<img src={banner} alt={__('Banner Image', 'name')} />
			</div>
			<div className="card-body">
				<h3 className="card-title">{name}</h3>
				<p className="card-description">{description}</p>
			</div>
			<div className="card-footer">
				<div className="plugin-data">
					<span className="version">v{version}</span> |{' '}
					<span className="author">{author}</span>
				</div>
				<Button
					className="plugin-action"
					isPrimary={['install', 'activate'].includes(pluginState)}
					isSecondary={pluginState === 'deactivate'}
					disabled={inProgress}
					onClick={handleActionButton}
				>
					{!inProgress && stringMap[pluginState].static}
					{inProgress && (
						<span style={{ display: 'flex', alignItems: 'center' }}>
							<Dashicon icon="update" />
							{stringMap[pluginState].progress + '...'}
						</span>
					)}
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
	);
};

const installPlugin = (slug) => {
	return new Promise((resolve) => {
		wp.updates.ajax('install-plugin', {
			slug,
			success: () => {
				resolve({ success: true });
			},
			error: () => {
				resolve({ success: false });
			},
		});
	});
};

export default PluginCard;