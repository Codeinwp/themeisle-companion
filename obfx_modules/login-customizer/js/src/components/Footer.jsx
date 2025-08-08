import { Button, ButtonGroup, Center, Field, Flex, Icon, IconButton, NativeSelect, Span, Text, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { Key, LogIn, UserPlus } from "lucide-react";
import { Tooltip } from "./ui/tooltip";
import { useEffect, useState } from "@wordpress/element";

const PREVIEW_OPTIONS = {
  'login': {
    label: __('Login Form', 'themeisle-companion'),
    url: window.OBFXData.loginUrl,
    icon: LogIn,
    show: true
  },
  'register': {
    label: __('Register Form', 'themeisle-companion'),
    url: window.OBFXData.registerUrl,
    icon: UserPlus,
    show: window.OBFXData.registrationEnabled === '1',
  },
  'lostpassword': {
    label: __('Forgot Password Form', 'themeisle-companion'),
    url: window.OBFXData.lostPasswordUrl,
    icon: Key,
    show: true
  },
}

const Footer = () => {
  const [currentPreview, setCurrentPreview] = useState('login');

  const handlePreview = (url, key) => {
    window.wp.customize.previewer.previewUrl(url);
  }

  useEffect(() => {
    const onPreviewReady = ({currentUrl}) => {
      if( ! currentUrl.includes('obfx-login') ) {
        setCurrentPreview('');
      }

      const key = currentUrl.split('=')[1];

      if( ! key ) {
        return;
      }

      if( ! key ) {
        setCurrentPreview('login');
        return;
      }

      setCurrentPreview(key);
    }

    window.wp.customize.previewer.bind('ready', onPreviewReady);

    return () => {
      window.wp.customize.previewer.unbind('ready', onPreviewReady);
    }
  }, []);

  const onPreviewSelectChange = (e) => {
    const key = e.target.value;

    if( ! PREVIEW_OPTIONS[key] ) {
      setCurrentPreview('');
      return;
    }

    setCurrentPreview(key);
    handlePreview(PREVIEW_OPTIONS[key].url);
  }

  return (
    <VStack align="flex-start" gap={2} px={3} py={2} w="full" bg="gray.100" borderTop="1px solid" borderColor="gray.200"> 
      <Field.Root>
        <Center gap={1} w="full">
          <Field.Label>{__('Preview', 'themeisle-companion')}:</Field.Label>
          <NativeSelect.Root size="xs" width="full" colorPalette="purple" bgImage="none">
            <NativeSelect.Field
              value={currentPreview}
              onChange={onPreviewSelectChange}
              bg="!transparent"
              _focus={{
                shadow: '!none',
                borderColor: '!purple.500',
              }}
            >
              { currentPreview === '' && <option value="">{__('Select Form to Preview', 'themeisle-companion')}</option> }
              {Object.entries(PREVIEW_OPTIONS).map(([key, option]) => (
                <option key={key} value={key}>{option.label}</option>
              ))}
            </NativeSelect.Field>
            <NativeSelect.Indicator />
          </NativeSelect.Root> 
        </Center>
      </Field.Root>
    </VStack>
  );
};

export default Footer; 