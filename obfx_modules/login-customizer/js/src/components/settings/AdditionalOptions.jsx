import { Button, HStack, Icon, Separator, SimpleGrid, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { Key, LogIn, Undo2, UserPlus } from "lucide-react";
import { useLoginCustomizerOptions } from "../../hooks/useLoginCustomizerOptions";
import Toggle from "../controls/Toggle";
import Heading from "../controls/Heading";
import { useEffect, useState } from "@wordpress/element";

const PREVIEW_OPTIONS = {
  'login': {
    label: __('Login', 'themeisle-companion'),
    url: window.OBFXData.loginUrl,
    icon: LogIn
  },
  'register': {
    label: __('Register', 'themeisle-companion'),
    url: window.OBFXData.registerUrl,
    icon: UserPlus
  },
  'lost-password': {
    label: __('Reset', 'themeisle-companion'),
    url: window.OBFXData.lostPasswordUrl,
    icon: Key
  },
}

const AdditionalOptions = () => {
  const {resetOptions}= useLoginCustomizerOptions();

  const handleReset = () => {
    const confirm = window.confirm(__('Are you sure you want to reset the settings to default?', 'themeisle-companion'));
    
    if( confirm ) {
      resetOptions();
    } 
  };

  const handlePreview = (url, key) => {
    window.wp.customize.previewer.previewUrl(url);
  }

  return (
    <VStack align="flex-start" gap={5}>
      <Toggle label={__('Show Remember Me', 'themeisle-companion')} id="show_remember_me" />
      
      {OBFXData.hasLanguageSwitcher && (
        <Toggle label={__('Show Language Switcher', 'themeisle-companion')} id="show_language_switcher" />
      )}

      <Button colorPalette="red" variant="surface" w="full" size="xs" onClick={handleReset}>
        <Icon as={Undo2} />
        {__('Reset All', 'themeisle-companion')}
      </Button>
    </VStack>
  );
};

export default AdditionalOptions; 