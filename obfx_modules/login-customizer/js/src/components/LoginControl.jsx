import { Box, Flex, Spinner } from '@chakra-ui/react';
import { OptionsProvider } from '../hooks/useLoginCustomizerOptions';
import { useSectionLoaded } from '../hooks/useSectionLoaded';
import Footer from './Footer';
import Sections from './Sections';

const { sectionID } = window.OBFXData;

const LoginControl = () => {
  const { sectionLoaded } = useSectionLoaded(sectionID);

  if (!sectionLoaded) {
    return (
      <Flex justifyContent="center" alignItems="center" height="100%">
        <Spinner />
      </Flex>
    ); 
  }

  return (
    <OptionsProvider>    
      <Sections/>

      <Box mt="auto" mb="-12px">
        <Footer />
      </Box>

    </OptionsProvider>
  );
};

export default LoginControl;