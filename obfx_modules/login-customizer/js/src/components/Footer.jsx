import { Button, ButtonGroup, Center, Flex, Icon, IconButton, Span, Text, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { Key, LogIn, UserPlus } from "lucide-react";
import { Tooltip } from "./ui/tooltip";

const PREVIEW_OPTIONS = {
  'login': {
    label: __('Login', 'themeisle-companion'),
    url: window.OBFXData.loginUrl,
    icon: LogIn,
    show: true
  },
  'register': {
    label: __('Register', 'themeisle-companion'),
    url: window.OBFXData.registerUrl,
    icon: UserPlus,
    show: window.OBFXData.registrationEnabled === '1',
  },
  'lost-password': {
    label: __('Forgot', 'themeisle-companion'),
    url: window.OBFXData.lostPasswordUrl,
    icon: Key,
    show: true
  },
}

const Footer = () => {
  const handlePreview = (url, key) => {
    window.wp.customize.previewer.previewUrl(url);
  }

  return (
    <VStack align="flex-start" gap={2} p={4} w="full" bg="gray.100" borderTop="1px solid" borderColor="gray.200"> 
      
      <Text fontSize="xs" color="gray.700" m={0} flexShrink="0" fontWeight="medium">{
        __('Preview', 'themeisle-companion') + ': '}
      </Text>

      <ButtonGroup
        attached
        position="relative"
        size="2xs"
        colorPalette="purple"
        variant="outline"
        w="full"
      >
        {Object.entries(PREVIEW_OPTIONS).map(([key, option]) => {
          if( ! option.show ) {
            return null;
          }

          return (
            <Button
              key={key}
              flex={1}
              onClick={() => handlePreview(option.url)}
            >
              <Icon as={option.icon} />
              {option.label}
            </Button>
          );
        })}
      </ButtonGroup>

    </VStack>
  );
};

export default Footer; 