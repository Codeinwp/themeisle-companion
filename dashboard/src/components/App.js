import '../style.scss';
import Header from './Header';
import { useState } from '@wordpress/element';
import { tabs } from '../utils/common';

const App = () => {
	const [ activeTab, setActiveTab ] = useState( 'modules' );

	return (
		<div>
			<Header activeTab={ activeTab } setActiveTab={ setActiveTab } />
			<div className="container">{ tabs[ activeTab ].render() }</div>
		</div>
	);
};

export default App;
