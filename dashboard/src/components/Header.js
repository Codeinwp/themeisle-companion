/* global obfxDash */
import { tabs } from '../utils/common';

const Title = () => {
	return (
		<div className="top">
			<img src={obfxDash.path + 'assets/orbit-fox.png'} alt="logo" />
			<h1>Orbit Fox</h1>
		</div>
	);
};

const Tabs = ({ activeTab, setActiveTab }) => {
	return (
		<nav className="navigation">
			{Object.keys(tabs).map((tab, index) => {
				return (
					<li
						key={'tab' + index}
						className={tab === activeTab ? 'active' : ''}
					>
						<a href={'#' + tab} onClick={() => setActiveTab(tab)}>
							{tabs[tab].label}
						</a>
					</li>
				);
			})}
		</nav>
	);
};

const Header = ({ activeTab, setActiveTab }) => {
	return (
		<header>
			<div className="container">
				<Title />
				<div id="tsdk_banner"></div>
				<Tabs activeTab={activeTab} setActiveTab={setActiveTab} />
			</div>
		</header>
	);
};

export default Header;
