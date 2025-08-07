import { Show, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { useLoginCustomizerOptions } from "../../hooks/useLoginCustomizerOptions";
import Color from "../controls/Color";
import Heading from "../controls/Heading";
import Range from "../controls/Range";
import Toggle from "../controls/Toggle";

const FormFooter = () => {
  const {getOption}= useLoginCustomizerOptions();
  return (
    <VStack align="flex-start" gap={5}>
      <Heading label={__('Navigation Links', 'themeisle-companion')} />
      <Toggle label={__('Enabled', 'themeisle-companion')} id="show_navigation_links" />
      <Show when={getOption('show_navigation_links')}>
        <Color label={__('Color', 'themeisle-companion')} id="nav_color" />
        <Color label={__('Hover Color', 'themeisle-companion')} id="nav_hover_color" />
        <Range label={__('Font Size', 'themeisle-companion')} id="nav_font_size" max={50} min={10}/>
      </Show>

      <Heading label={__('Homepage Link', 'themeisle-companion')} />
      <Toggle label={__('Enabled', 'themeisle-companion')} id="show_link_to_homepage" />
      <Show when={getOption('show_link_to_homepage')}> 
        <Color label={__('Color', 'themeisle-companion')} id="homepage_link_color" />
        <Color label={__('Hover Color', 'themeisle-companion')} id="homepage_link_hover_color" />
        <Range label={__('Font Size', 'themeisle-companion')} id="homepage_link_font_size" max={50} min={10}/>
      </Show>

      <Heading label={__('Privacy Policy Link', 'themeisle-companion')} />
      <Toggle label={__('Enabled', 'themeisle-companion')} id="show_privacy_policy" />
      <Show when={getOption('show_privacy_policy')}>
        <Color label={__('Color', 'themeisle-companion')} id="privacy_policy_link_color" />
        <Color label={__('Hover Color', 'themeisle-companion')} id="privacy_policy_link_hover_color" />
        <Range label={__('Font Size', 'themeisle-companion')} id="privacy_policy_link_font_size" max={50} min={10}/>
      </Show>
    </VStack>
  );
};

export default FormFooter; 