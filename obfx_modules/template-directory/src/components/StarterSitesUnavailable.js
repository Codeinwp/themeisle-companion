/* global template_directory */
import { useState } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

const StarterSitesUnavailable = () => {
	const {
		assets,
		neveData,
		tpcData,
		strings,
		tpcAdminURL,
	} = template_directory;

	const [activatingNeve, setActivatingNeve] = useState(false);
	const [installingNeve, setInstallingNeve] = useState(false);
	const [activatingTpc, setActivatingTpc] = useState(false);
	const [installingTpc, setInstallingTpc] = useState(false);
	const [currentStateNeve, setCurrentStateNeve] = useState(neveData.cta);
	const [currentStateTpc, setCurrentStateTpc] = useState(tpcData.cta);
	const [error, setError] = useState(false);

	let themeError = strings.themeNotInstalled;
	if ( 'deactivate' === currentStateNeve ) themeError = strings.themeNotActive;

	let pluginError = strings.tpcNotInstalled;
	if ( 'deactivate' === currentStateTpc ) pluginError = strings.tpcNotActive;

	const installPlugin = () => {
		setInstallingTpc(true);
		wp.updates.ajax('install-plugin', {
			slug: 'templates-patterns-collection',
			success: () => {
				activatePlugin();
			},
			error: (e) => {
				if ('folder_exists' === e.errorCode) {
					activatePlugin();
				} else {
					setError(
						e.errorMessage
							? e.errorMessage
							: __(
								'Something went wrong while installing the plugin.'
							)
					);
				}
			},
		});
	};

	const activatePlugin = () => {
		setInstallingTpc(false);
		setActivatingTpc(true);
		setCurrentStateTpc('activate');
		const activationURL = tpcData.activate;

		get(activationURL, true).then((r) => {
			if (!r.ok) {
				setError(__('Could not activate plugin.'));
			} else {
				setActivatingTpc(false);
				setCurrentStateTpc('deactivate');
			}
		});
	};

	const installTheme = () => {
		setInstallingNeve(true);
		wp.updates.ajax('install-theme', {
			slug: 'neve',
			success: () => {
				activateTheme();
			},
			error: (e) => {
				if ('folder_exists' === e.errorCode) {
					activateTheme();
				} else {
					setError(
						e.errorMessage
							? e.errorMessage
							: __(
								'Something went wrong while installing the theme.'
							)
					);
				}
			},
		});
	};

	const activateTheme = () => {
		setInstallingNeve(false);
		setActivatingNeve(true);
		setCurrentStateNeve('activate');
		const activationURL = neveData.activate;

		get(activationURL, true).then((r) => {
			if (!r.ok) {
				setError(__('Could not activate theme.'));
			} else {
				setActivatingNeve(false);
				setCurrentStateNeve('deactivate');
			}
		});
	};

	const renderNoticeContent = (installing, activating, currentState, slug) => {
		const buttonMap = {
			install: (
				<Button
					disabled={installing}
					isPrimary={!installing}
					isSecondary={installing}
					className={installing && 'is-loading'}
					icon={installing && 'update'}
					onClick={'neve' === slug ? installTheme : installPlugin}
				>
					{installing
						? __('Installing') + '...'
						: __('Install and Activate')}
				</Button>
			),
			activate: (
				<Button
					disabled={activating}
					isPrimary={!activating}
					isSecondary={activating}
					className={activating && 'is-loading'}
					icon={activating && 'update'}
					onClick={'neve' === slug ? activateTheme : activatePlugin}
				>
					{activating ? __('Activating') + '...' : __('Activate')}
				</Button>
			),
		};
		return (
			<>
				{buttonMap[currentState]}
			</>
		);
	}

	const shouldRedirect = 'deactivate' === currentStateTpc && 'deactivate' === currentStateNeve
	if (shouldRedirect) {
		window.location.href = tpcAdminURL;
	}

	return (
		<div className={classnames(['unavailable-starter-sites', { empty: shouldRedirect }])}>
			<div
				className="ss-background"
				style={{ backgroundImage: `url(${assets}/img/starter.jpg)` }}
			/>
			{('install' === currentStateNeve || 'activate' === currentStateNeve) && (
				<div className="content-wrap">
					<h1 className="error">{themeError}</h1>
					{renderNoticeContent(installingNeve, activatingNeve, currentStateNeve, 'neve')}
				</div>
			)}
			{('install' === currentStateTpc || 'activate' === currentStateTpc) && (
				<div className="content-wrap">
					<h1 className="error">{pluginError}</h1>
					{renderNoticeContent(installingTpc, activatingTpc, currentStateTpc, 'tpc')}
				</div>
			)}
		</div>
	);
};

const get = (route, simple = false, useNonce = true) => {
	return requestData(route, simple, {}, 'GET', useNonce);
};

const requestData = async (
	route,
	simple = false,
	data = {},
	method = 'POST',
	useNonce = true
) => {
	const options = {
		method,
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
	};

	if (useNonce) {
		options.headers['x-wp-nonce'] = template_directory.nonce;
	}

	if ('POST' === method) {
		options.body = JSON.stringify(data);
	}

	return await fetch(route, options).then((response) => {
		return simple ? response : response.json();
	});
};

export default StarterSitesUnavailable;
