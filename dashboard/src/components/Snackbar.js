import { DashboardContext } from './DashboardContext';

import { Snackbar } from '@wordpress/components';
import { useContext, useEffect } from '@wordpress/element';

const SnackbarComponent = () => {
	const { toast, setToast } = useContext(DashboardContext);
	useEffect(() => {
		setTimeout(() => {
			setToast(null);
		}, 3000);
	}, []);

	const style = {
		opacity: null === toast ? 0 : 1,
	};

	return (
		<div style={style}>
			<Snackbar className="dash-notice">{toast}</Snackbar>
		</div>
	);
};

export default SnackbarComponent;
