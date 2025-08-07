import { Box, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { OptionsProvider } from '../hooks/useLoginCustomizerOptions';
import { useSectionLoaded } from '../hooks/useSectionLoaded';
import Footer from './Footer';
import Sections from './Sections';

const { sectionID } = window.OBFXData;

const LoginControl = () => {
  const { sectionLoaded } = useSectionLoaded(sectionID);

  if (!sectionLoaded) {
    // return (
    //   <Flex justifyContent="center" alignItems="center" height="100%">
    //     <Spinner />
    //   </Flex>
    // ); 
  }

  return (
    <OptionsProvider>    
      {/* <Box p={4} borderBottom="1px solid" borderColor="gray.200" bg="gray.50">
        <Text fontSize="xs" color="gray.700" m={0}>{
          __('The Login Page Customizer allows you to customize the appearance of your WordPress login page. You can change colors, upload a custom logo, modify form styling, and configure security settings.', 'themeisle-companion')}</Text>
      </Box> */}

      <Sections/>


      <Box mt="auto" mb="-12px">
        <Footer />
      </Box>

    </OptionsProvider>
  );
};

export default LoginControl;