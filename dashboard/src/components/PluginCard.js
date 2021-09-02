import classnames from 'classnames';

import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

const PluginCard = ({ slug, data }) => {
	const { banner, name, description, version, author, cta } = data;

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
				<Button className="plugin-action" isPrimary>
					{stringMap[cta].static}
				</Button>
			</div>
		</div>
	);
};

export default PluginCard;
