import { Show, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { useLoginCustomizerOptions } from "../../hooks/useLoginCustomizerOptions";
import Input from "../controls/Input";
import Toggle from "../controls/Toggle";

const UrlSettings = () => {
  const {getOption} = useLoginCustomizerOptions();
  return (
    <VStack align="flex-start" gap={5}>
      <Toggle label={__('Change Login URL', 'themeisle-companion')} id="change_login_url" />
      <Show when={getOption('change_login_url')}>
        <Toggle 
          label={__('Redirect to Login', 'themeisle-companion')} 
          id="redirect_to_login" 
          help={__('Redirect the user to the login if they attempt to access an admin page without being logged in.', 'themeisle-companion')}
        />
        <Input 
          label={__('Login URL', 'themeisle-companion')}
          id="login_url" 
          help={__('Example: `secure-login`. The login URL will be available at `https://your-domain.com/secure-login`', 'themeisle-companion')}
        />
      </Show>
    </VStack>
  );
};

export default UrlSettings; 