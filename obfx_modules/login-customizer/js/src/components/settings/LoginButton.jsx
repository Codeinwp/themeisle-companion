import { SegmentGroup, Tabs, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import Toggle from "../controls/Toggle";
import Range from "../controls/Range";
import Spacing from "../controls/Spacing";
import { useLoginCustomizerOptions } from "../../hooks/useLoginCustomizerOptions";
import Heading from "../controls/Heading";
import BorderControl from "../controls/BorderControl";
import Color from "../controls/Color";
import { useState } from "@wordpress/element";

const LoginButton = () => {
  const [activeTab, setActiveTab] = useState(__('Initial', 'themeisle-companion'));
  const { getOption } = useLoginCustomizerOptions();
  return (
    <VStack align="flex-start" gap={5}>
      <Heading label={__('Layout', 'themeisle-companion')} />

      <Toggle label={__('Display on new row', 'themeisle-companion')} id="button_display_below" />

      {getOption('button_display_below') && (
        <>
          <Range label={__('Button Width', 'themeisle-companion')} id="button_width" min={0} max={100} unit="％" />
          <Range label={__('Button Top Spacing', 'themeisle-companion')} id="button_margin_top" min={0} max={100} unit="px" />
        </>
      )}

      <Spacing label={__('Padding', 'themeisle-companion')} id="button_padding" unit="px" />
      <Range label={__('Font Size', 'themeisle-companion')} id="button_font_size" min={10} max={100} unit="px" />

      <Heading label={__('Style', 'themeisle-companion')} />
      
      <Range label={__('Border Radius', 'themeisle-companion')} id="button_border_radius" min={0} max={75} unit="px" />
      
      <Tabs.Root defaultValue="initial" variant="outline" colorPalette='purple' w="full" size="sm">
        <Tabs.List w="full" fontWeight="medium">
          <Tabs.Trigger value="initial" flexGrow={1} justifyContent="center">
            {__('Initial', 'themeisle-companion')}
          </Tabs.Trigger>
          <Tabs.Trigger value="hover" flexGrow={1} justifyContent="center">
            {__('Hover', 'themeisle-companion')}
          </Tabs.Trigger>
        </Tabs.List>

        <Tabs.Content value="initial">
          <VStack align="flex-start" gap={5}>
            <Color label={__('Background', 'themeisle-companion')} id="button_background" />
            <Color label={__('Text Color', 'themeisle-companion')} id="button_text_color" />
            <BorderControl label={__('Border', 'themeisle-companion')} id="button_border" unit="px" />
          </VStack>
        </Tabs.Content>
        
        <Tabs.Content value="hover">        
          <VStack align="flex-start" gap={5}>
            <Color label={__('Background', 'themeisle-companion')} id="button_hover_background" />
            <Color label={__('Text Color', 'themeisle-companion')} id="button_hover_text_color" />
            <Color label={__('Border Color', 'themeisle-companion')} id="button_hover_border_color" />
          </VStack>
        </Tabs.Content>
      </Tabs.Root>

    </VStack>
  );
};

export default LoginButton; 