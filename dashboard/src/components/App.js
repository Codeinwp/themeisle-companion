import '../style.scss';
import Header from './Header';
import { useState } from '@wordpress/element';
import { tabs, getTabHash } from '../utils/common';

const App = () => {
	const hash = getTabHash();
	const [activeTab, setActiveTab] = useState(hash ? hash : 'modules');

	return (
		<div>
			<Header activeTab={activeTab} setActiveTab={setActiveTab} />
			<div className="container">{tabs[activeTab].render()}</div>
		</div>
	);
};

export default App;
