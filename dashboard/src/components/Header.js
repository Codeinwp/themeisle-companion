/* global obfxDash */
import { tabs } from '../utils/common';

const Header = ({ activeTab, setActiveTab }) => {
	const renderHead = () => {
		return (
			<div className="top">
				<img src={obfxDash.path + 'assets/orbit-fox.png'} alt="logo" />
				<h1>Orbit Fox</h1>
			</div>
		);
	};

	const renderNavbar = () => {
		return (
			<nav className="navigation">
				{Object.keys(tabs).map((tab, index) => {
					return (
						<li
							key={'tab' + index}
							className={tab === activeTab ? 'active' : ''}
						>
							<a
								href={'#' + tab}
								onClick={() => setActiveTab(tab)}
							>
								{tabs[tab].label}
							</a>
						</li>
					);
				})}
			</nav>
		);
	};

	return (
		<header>
			<div className="container">
				{renderHead()}
				{renderNavbar()}
			</div>
		</header>
	);
};

export default Header;
