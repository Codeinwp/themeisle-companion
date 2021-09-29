import '../style.scss';
import Header from './Header';
import Snackbar from './Snackbar';
import { DashboardContext } from './DashboardContext';
import { useState } from '@wordpress/element';
import { tabs, getTabHash } from '../utils/common';

const App = () => {
	const hash = getTabHash();
	const [activeTab, setActiveTab] = useState(hash ? hash : 'modules');
	const [toast, setToast] = useState();

	return (
		<DashboardContext.Provider value={{ toast, setToast }}>
			<Header activeTab={activeTab} setActiveTab={setActiveTab} />
			<div className="container">{tabs[activeTab].render()}</div>
			{toast && <Snackbar />}
		</DashboardContext.Provider>
	);
};

export default App;
