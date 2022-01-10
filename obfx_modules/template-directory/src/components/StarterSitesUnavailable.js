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
	const [slide, setSlide] = useState(false);

	let themeError = themeNotInstalled;
	if ('activate' === neveData.cta) themeError = themeNotActive;

	let pluginError = tpcNotInstalled;
	if ('activate' === tpcData.cta) pluginError = tpcNotActive;

	const stringMap = {
		install: {
			static: __('Install and Activate', 'neve'),
			progress: __('Installing', 'neve'),
		},
		activate: {
			static: __('Activate', 'neve'),
			progress: __('Activating', 'neve'),
		},
	};

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
						? stringMap.install.progress + '...'
						: stringMap.install.static}
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
					{activating
						? stringMap.activate.progress + '...'
						: stringMap.activate.static}
				</Button>
			),
			deactivate: (
				<Button
					disabled
					isSecondary
					className="is-loading"
				>
					{__('Activated')}
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
				setSlide(true);
			}
		});
	};

	const shouldDisplayTpc = 'deactivate' !== tpcData.cta;
	const shouldDisplayNeve = 'deactivate' !== neveData.cta;

	if ('deactivate' === currentStateTpc && 'deactivate' === currentStateNeve) {
		window.location.href = tpcAdminURL;
	}

	const isSingle = !shouldDisplayTpc || !shouldDisplayNeve;

	return (
		<>
			<div
				className="ss-background"
				style={{ backgroundImage: `url(${assets}/img/starter.jpg)` }}
			/>
			<div className="unavailable-starter-sites">
				<div
					className={classnames([
						'slider',
						{ 'move-right': slide && !isSingle },
						{ single: isSingle },
					])}
				>
					{shouldDisplayTpc && (
						<div className="content-wrap">
							<img
								src={assets + '/img/tpc.svg'}
								alt={__('Templates Cloud Logo', 'themeisle-companion')}
							/>
							<h1>
								{stringMap[tpcData.cta].static +
								' Templates Cloud'}
							</h1>
							<p className="error">{pluginError}</p>
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
					{shouldDisplayNeve && (
						<div className="content-wrap">
							<img
								src={assets + '/img/neve.svg'}
								alt={__('Neve Theme Logo', 'themeisle-companion')}
							/>
							<h1>{stringMap[neveData.cta].static + ' Neve'}</h1>
							<p className="error">{themeError}</p>
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
				</div>
				{neveData.cta !== 'deactivate' && tpcData.cta !== 'deactivate' && (
					<div className="stepper">
						<button
							className={classnames(['dot', { current: !slide }])}
							onClick={() => setSlide(!slide)}
						/>
						<button
							className={classnames(['dot', { current: slide }])}
							onClick={() => setSlide(!slide)}
						/>
					</div>
				)}
				{error && <div className="error"> {error} </div>}
			</div>
		</>
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
