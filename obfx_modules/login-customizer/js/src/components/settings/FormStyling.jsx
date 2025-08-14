import { HStack, VStack, Separator, Text, Box } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import Color from "../controls/Color";
import Range from "../controls/Range";
import BorderControl from "../controls/BorderControl";
import Heading from "../controls/Heading";
import Spacing from "../controls/Spacing";

const FormStyling = () => {
  return (
    <VStack align="flex-start" gap={5}>
      
      <Heading label={__('Box', 'themeisle-companion')} />

      <Color label={__('Background', 'themeisle-companion')} id="form_bg_color"/>
      <BorderControl label={__('Border', 'themeisle-companion')} id="form_border" />
      <Range label={__('Border Radius', 'themeisle-companion')} id="form_border_radius" max={100}/>
      <Range label={__('Width', 'themeisle-companion')} id="form_width" max={800} min={300} />
      <Spacing label={__('Padding', 'themeisle-companion')} id="form_padding" max={100} min={0} unit="px" />
      
      <Heading label={__('Fields', 'themeisle-companion')} />
      <Color label={__('Field Background', 'themeisle-companion')} id="form_field_bg_color"/>
      <Color label={__('Field Color', 'themeisle-companion')} id="form_field_text_color"/>
      <Range label={__('Field Font Size', 'themeisle-companion')} id="form_field_font_size" max={50} min={10}/>
      <Range label={__('Border Radius', 'themeisle-companion')} id="form_field_border_radius" max={100}/>
      <BorderControl label={__('Border', 'themeisle-companion')} id="form_field_border" />
      <Range label={__('Field Bottom Spacing', 'themeisle-companion')} id="form_field_margin_bottom" max={50} min={0}/>
      <Spacing label={__('Field Padding', 'themeisle-companion')} id="form_field_padding" max={100} min={0} unit="px" />

      <Heading label={__('Labels', 'themeisle-companion')} />
      <Color label={__('Label Color', 'themeisle-companion')} id="form_text_color"/>
      <Range label={__('Label Font Size', 'themeisle-companion')} id="form_label_font_size" max={50} min={10}/>
      <Range label={__('Label Bottom Spacing', 'themeisle-companion')} id="form_label_margin_bottom" max={50} min={0}/>
    </VStack>);
};

export default FormStyling; 