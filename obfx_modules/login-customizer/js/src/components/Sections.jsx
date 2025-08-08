import { Flex, Text, Collapsible, SimpleGrid, Icon, Button, Box } from '@chakra-ui/react';
import { ChevronDownIcon } from 'lucide-react';
import { SECTIONS } from '../common/constants';
import { useState} from '@wordpress/element';

import Logo from './settings/Logo';
import Background from './settings/Background';
import FormStyling from './settings/FormStyling';
import LoginButton from './settings/LoginButton';
import UrlSettings from './settings/UrlSettings';
import AdditionalOptions from './settings/AdditionalOptions';
import FormFooter from './settings/FormFooter';

const Sections = () => {
  const [openId, setOpenId] = useState(null);

  return (
    <SimpleGrid>
      {Object.entries(SECTIONS).map(([id, section], index) => <Section key={id} id={id} first={index === 0} openId={openId} setOpenId={setOpenId} />)}
    </SimpleGrid>
  ); 
};

const Section = ({ id, first = false, openId, setOpenId }) => {

  const ToggleAccordion = () => {
    setOpenId(openId === id ? null : id);
  };

  const open = openId === id;

  return (
    <Collapsible.Root open={open}>
      <Collapsible.Trigger 
        onClick={ToggleAccordion} 
        borderLeftWidth="4px"
        borderBottomWidth="1px"
        borderStyle="solid"
        borderColor="purple.300"
        bg="white"
        px={4} 
        py={2.5} 
        w="full" 
        borderBottomColor="gray.200"
        cursor="pointer"
        _hover={{
          borderLeftColor: 'purple.500',
          bg: 'gray.100',
        }}
      >
        <Flex justifyContent="space-between" alignItems="center">
          <Text fontSize="sm" fontWeight="medium" m={0}>
            {SECTIONS[id]}
          </Text>
          <Icon as={ChevronDownIcon} w={4} h={4} transform={open ? 'rotate(-180deg)' : 'rotate(0deg)'} transition="transform 0.2s ease-in-out" />
        </Flex>
      </Collapsible.Trigger>
      <Collapsible.Content borderBottom="1px solid" borderColor="gray.200" bg="gray.50">
        <Box p={4}>
          <SectionContent id={id} />
        </Box>
      </Collapsible.Content>
    </Collapsible.Root>
  );
};

const SectionContent = ({ id }) => {
  switch (id) {
  case 'logo':
    return <Logo />;
  case 'background':
    return <Background />;
  case 'formStyling':
    return <FormStyling />;
  case 'loginButton':
    return <LoginButton />;
  case 'formFooter':
    return <FormFooter />;
  case 'urlSettings':
    return <UrlSettings />;
  case 'additionalOptions':
    return <AdditionalOptions />;
  default:
    return null;
  }
};

export default Sections;