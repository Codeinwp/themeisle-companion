import { render } from "@wordpress/element";
import LoginControl from "./components/LoginControl";
import { Provider } from "./components/ui/provider";
import maybeAddPolyFills from "../../../../dashboard/src/wp-element-polyfill";

const { customize } = window.wp;
const { controlConstructor, Control } = customize;

maybeAddPolyFills();

controlConstructor.obfx_login_control = Control.extend({
  renderContent: function renderContent() {
    customize.bind("ready", () => {
      render(
        <Provider>
          <LoginControl control={this} />
        </Provider>,
        this.container[0]
      );
    });
  },
});
