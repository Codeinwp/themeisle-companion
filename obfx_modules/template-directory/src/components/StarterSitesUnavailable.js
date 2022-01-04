/* global template_directory, fetch */
import { useState } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

const StarterSitesUnavailable = () => {
	const { assets, neveData, tpcData, strings, tpcAdminURL } =
		template_directory;
	const { themeNotInstalled, themeNotActive, tpcNotInstalled, tpcNotActive } =
		strings;

	const [activatingNeve, setActivatingNeve] = useState(false);
	const [installingNeve, setInstallingNeve] = useState(false);
	const [currentStateNeve, setCurrentStateNeve] = useState(neveData.cta);

	const [activatingTpc, setActivatingTpc] = useState(false);
	const [installingTpc, setInstallingTpc] = useState(false);
	const [currentStateTpc, setCurrentStateTpc] = useState(tpcData.cta);

	const settersNeve = {
		activating: setActivatingNeve,
		installing: setInstallingNeve,
		currentState: setCurrentStateNeve,
	};
	const settersTpc = {
		activating: setActivatingTpc,
		installing: setInstallingTpc,
		currentState: setCurrentStateTpc,
	};

	const [error, setError] = useState(false);

	let themeError = themeNotInstalled;
	if ('activate' === neveData.cta) themeError = themeNotActive;

	let pluginError = tpcNotInstalled;
	if ('activate' === tpcData.cta) pluginError = tpcNotActive;

	const renderNoticeContent = (
		installing,
		activating,
		currentState,
		setters,
		type,
		slug,
		activationURL
	) => {
		const buttonMap = {
			install: (
				<Button
					disabled={installing}
					isPrimary={!installing}
					isSecondary={installing}
					className={installing && 'is-loading'}
					icon={installing && 'update'}
					onClick={() => install(setters, type, slug, activationURL)}
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
					onClick={() => activate(setters, activationURL)}
				>
					{activating ? __('Activating') + '...' : __('Activate')}
				</Button>
			),
		};
		return <>{buttonMap[currentState]}</>;
	};

	const install = (setters, type, slug, activationURL) => {
		setters.installing(true);
		wp.updates.ajax(`install-${type}`, {
			slug,
			success: () => {
				activate(setters, activationURL);
			},
			error: (e) => {
				if ('folder_exists' === e.errorCode) {
					activate(setters, activationURL);
				} else {
					setError(
						e.errorMessage
							? e.errorMessage
							: __(
								'Something went wrong. Please try again.',
								'themeisle-companion'
							)
					);
					setters.installing(false);
					setTimeout(() => setError(false), 3000);
				}
			},
		});
	};

	const activate = (setters, activationURL) => {
		setters.installing(false);
		setters.activating(true);
		setters.currentState('activate');

		get(activationURL).then((r) => {
			if (!r.ok) {
				setError(
					__(
						'Something went wrong. Please try again.',
						'themeisle-companion'
					)
				);
				setters.activating(false);
				setTimeout(() => setError(false), 3000);
			} else {
				setters.activating(false);
				setters.currentState('deactivate');
			}
		});
	};

	const shouldRedirect =
		'deactivate' === currentStateTpc && 'deactivate' === currentStateNeve;
	if (shouldRedirect) {
		window.location.href = tpcAdminURL;
	}

	return (
		<div
			className={classnames([
				'unavailable-starter-sites',
				{ empty: shouldRedirect },
			])}
		>
			<div
				className="ss-background"
				style={{ backgroundImage: `url(${assets}/img/starter.jpg)` }}
			/>
			{'deactivate' !== currentStateNeve && (
				<div className="content-wrap">
					<h1 className="error">{themeError}</h1>
					{renderNoticeContent(
						installingNeve,
						activatingNeve,
						currentStateNeve,
						settersNeve,
						'theme',
						'neve',
						neveData.activate
					)}
				</div>
			)}
			{'deactivate' !== currentStateTpc && (
				<div className="content-wrap">
					<h1 className="error">{pluginError}</h1>
					{renderNoticeContent(
						installingTpc,
						activatingTpc,
						currentStateTpc,
						settersTpc,
						'plugin',
						'templates-patterns-collection',
						tpcData.activate
					)}
				</div>
			)}
			{error && <div className="error"> {error} </div>}
		</div>
	);
};

const get = async (route) => {
	const options = {
		method: 'GET',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
			'x-wp-nonce': template_directory.nonce,
		},
	};

	return await fetch(route, options).then((response) => {
		return response;
	});
};

export default StarterSitesUnavailable;
