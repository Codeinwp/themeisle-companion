/* global template_directory */
import { Button } from '@wordpress/components';

// export const get = (route, simple = false, useNonce = true) => {
// 	return requestData(route, simple, {}, 'GET', useNonce);
// };
//
// const requestData = async (
// 	route,
// 	simple = false,
// 	data = {},
// 	method = 'POST',
// 	useNonce = true
// ) => {
// 	const options = {
// 		method,
// 		headers: {
// 			Accept: 'application/json',
// 			'Content-Type': 'application/json',
// 		},
// 	};
//
// 	if (useNonce) {
// 		options.headers['x-wp-nonce'] = template_directory.nonce;
// 	}
//
// 	if ('POST' === method) {
// 		options.body = JSON.stringify(data);
// 	}
//
// 	return await fetch(route, options).then((response) => {
// 		return simple ? response : response.json();
// 	});
// };
//
// const untrailingSlashIt = (str) => str.replace(/\/$/, '');

const StarterSitesUnavailable = () => {
	const {
		assets,
		tpcIsInstalled,
		tpcIsActive,
		neveIsInstalled,
		neveIsActive,
	} = template_directory;
	// const tpcRedirect = tpcAdminURL + (isOnboarding ? '&onboarding=yes' : '');
	// const [installing, setInstalling] = useState(false);
	// const [activating, setActivating] = useState(false);
	// const [updating, setUpdating] = useState(false);
	// const [error, setError] = useState(false);
	// const [currentState, setCurrentState] = useState(templatesPluginData.cta);
	// const installPlugin = () => {
	// 	setInstalling(true);
	// 	wp.updates.ajax('install-plugin', {
	// 		slug: 'templates-patterns-collection',
	// 		success: () => {
	// 			activatePlugin();
	// 		},
	// 		error: (e) => {
	// 			if ('folder_exists' === e.errorCode) {
	// 				activatePlugin();
	// 			} else {
	// 				setError(
	// 					e.errorMessage
	// 						? e.errorMessage
	// 						: __(
	// 								'Something went wrong while installing the plugin.'
	// 						  )
	// 				);
	// 			}
	// 		},
	// 	});
	// };
	//
	// const activatePlugin = () => {
	// 	setInstalling(false);
	// 	setActivating(true);
	// 	setCurrentState('activate');
	// 	const activationURL = templatesPluginData.activate;
	//
	// 	get(activationURL, true).then((r) => {
	// 		if (r.ok) {
	// 			window.location.href = tpcRedirect;
	// 		} else {
	// 			setError(__('Could not activate plugin.'));
	// 		}
	// 	});
	// };
	//
	// const updatePlugin = () => {
	// 	setUpdating(true);
	// 	wp.updates.ajax('update-plugin', {
	// 		slug: 'templates-patterns-collection',
	// 		plugin: untrailingSlashIt(tpcPath),
	// 		success: () => {
	// 			window.location.href = tpcRedirect;
	// 		},
	// 		error: (e) => {
	// 			setError(
	// 				e.errorMessage
	// 					? e.errorMessage
	// 					: __('Something went wrong while updating the plugin.')
	// 			);
	// 		},
	// 	});
	// };

	// const renderNoticeContent = () => {
	// 	const buttonMap = {
	// 		install: (
	// 			<Button
	// 				disabled={installing}
	// 				isPrimary={!installing}
	// 				isSecondary={installing}
	// 				className={installing && 'is-loading'}
	// 				icon={installing && 'update'}
	// 				onClick={installPlugin}
	// 			>
	// 				{installing
	// 					? __('Installing') + '...'
	// 					: __('Install and Activate')}
	// 			</Button>
	// 		),
	// 		activate: (
	// 			<Button
	// 				disabled={activating}
	// 				isPrimary={!activating}
	// 				isSecondary={activating}
	// 				className={activating && 'is-loading'}
	// 				icon={activating && 'update'}
	// 				onClick={activatePlugin}
	// 			>
	// 				{activating ? __('Activating') + '...' : __('Activate')}
	// 			</Button>
	// 		),
	// 		deactivate: (
	// 			<Button
	// 				disabled={updating}
	// 				isPrimary={!updating}
	// 				isSecondary={updating}
	// 				className={updating && 'is-loading'}
	// 				icon={updating && 'update'}
	// 				onClick={updatePlugin}
	// 			>
	// 				{updating ? __('Updating') + '...' : __('Update')}
	// 			</Button>
	// 		),
	// 	};
	// 	return (
	// 		<>
	// 			<h1>
	// 				{'deactivate' === currentState
	// 					? template_directory.strings.starterSitesUnavailableUpdate
	// 					: template_directory.strings.starterSitesUnavailableActive}
	// 			</h1>
	// 			<br />
	// 			{buttonMap[currentState]}
	// 		</>
	// 	);
	// };
	const themeError = !neveIsActive
		? template_directory.strings.themeNotActive
		: template_directory.strings.themeNotInstalled;

	const pluginError = !tpcIsActive
		? template_directory.strings.tpcNotActive
		: template_directory.strings.tpcNotInstalled;
	return (
		<div className="unavailable-starter-sites">
			<div
				className="ss-background"
				style={{ backgroundImage: `url(${assets}/img/starter.jpg)` }}
			/>
			{(!neveIsInstalled || !neveIsActive) && (
				<div className="content-wrap">
					<h1 className="error">{themeError}</h1>
				</div>
			)}
			{(!tpcIsInstalled || !tpcIsActive) && (
				<div className="content-wrap">
					<h1 className="error">{pluginError}</h1>
				</div>
			)}
		</div>
	);
};

export default StarterSitesUnavailable;

// export default withSelect((select) => {
// 	const { getPlugins } = select('neve-dashboard');
// 	return {
// 		templatesPluginData: getPlugins()['templates-patterns-collection'],
// 	};
// })(StarterSitesUnavailable);
