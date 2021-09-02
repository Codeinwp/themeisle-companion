import App from './components/App';
import { render } from '@wordpress/element';

const Root = () => <App />;
render(<Root />, document.getElementById('obfx-dash'));
