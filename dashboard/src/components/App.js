/* global obfxDash */
import '../style.scss';
import Header from './Header';
import Content from './Content';
import { useState } from '@wordpress/element';

const { modules, data } = obfxDash;

const App = () => {
	const [ activeTab, setActiveTab ] = useState( 'modules' );

	// eslint-disable-next-line no-console
	console.log( modules );
	// eslint-disable-next-line no-console
	console.log( data );

	return (
		<div>
			<Header activeTab={ activeTab } setActiveTab={ setActiveTab } />
			<Content />
		</div>
	);
};

export default App;
