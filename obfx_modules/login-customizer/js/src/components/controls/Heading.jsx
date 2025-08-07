import { HStack, Separator, Text, Box } from "@chakra-ui/react";

const Heading = ({label}) => {
  return (
    <Box w="full">
      <HStack>
        <Text flexShrink="0" m={0} textTransform="uppercase" fontSize="xs" color="grey.800" fontWeight="semibold">{label}</Text>
        <Separator flex="1" borderColor="gray.300"/>
      </HStack>
    </Box>
  );
};

export default Heading;