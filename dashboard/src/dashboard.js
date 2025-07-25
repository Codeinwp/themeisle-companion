import App from './components/App';
import { createRoot } from "@wordpress/element";

const dashboard = document.getElementById("obfx-dash");

if (dashboard) {
  createRoot(dashboard).render(<App />);
}
