import Toggle from '../controls/Toggle';
import { VStack } from '@chakra-ui/react';
import Image from '../controls/Image';
import Range from '../controls/Range';
import Input from '../controls/Input';
import { __ } from '@wordpress/i18n';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';

const Logo = () => {
  const { getOption, options } = useLoginCustomizerOptions();

  const isLogoDisabled = getOption('disable_logo');

  return (
    <VStack align="flex-start" gap={5}>
      <Toggle label={__('Disable Logo', 'themeisle-companion')} id="disable_logo"/>
      
      { !isLogoDisabled && (<>
        <Image label={__('Custom Logo', 'themeisle-companion')} id="custom_logo_url"/>
        <Range label={__('Logo Width', 'themeisle-companion')} id="logo_width" max={800} min={20}/>
        <Range label={__('Logo Height', 'themeisle-companion')} id="logo_height" max={800} min={20}/>
        <Range label={__('Bottom Margin', 'themeisle-companion')} id="logo_bottom_margin" max={250} min={0}/>
        <Input label={__('Link URL', 'themeisle-companion')} id="logo_url" help={__('Clicking the logo will go to this URL', 'themeisle-companion')} placeholder="https://wordpress.org"/>
        <Input label={__('Link Text', 'themeisle-companion')} id="logo_title" help={__('Text of the logo link (hidden)', 'themeisle-companion')}/>
      </>) }
    </VStack>
  );
};

export default Logo;