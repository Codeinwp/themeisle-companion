import App from './components/App';
import { render } from "@wordpress/element";
import maybeAddPolyFills from "./wp-element-polyfill";

const dashboard = document.getElementById("obfx-dash");

if (dashboard) {
  maybeAddPolyFills();
  render(<App />, dashboard);
}
