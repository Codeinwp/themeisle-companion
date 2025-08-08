import { Badge, Center, Field, Flex, HStack, NumberInput } from '@chakra-ui/react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import ResetButton from './ResetButton';

const Range = ({label, id, max=800, min=0, help='', unit=''}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const handleNumberChange = ({value}) => {
    setOption(id, value);
  };

  return (
    <Field.Root>
      <HStack width="full" justifyContent="space-between">
        <Flex gap={1} align="center" mb={2}>
          <ResetButton id={id} />
          <Field.Label> 
            {label} 
          </Field.Label>

          {unit && (
            <Badge colorPalette="gray" variant="surface" size="xs" ml='auto' textTransform="uppercase">
              {unit}
            </Badge>
          )}
        </Flex>
      
        <NumberInput.Root
          size="xs"
          value={getOption(id)}
          onValueChange={handleNumberChange}
          max={max}
          min={min}
          w="100px"
        >
          <NumberInput.Control />
          <NumberInput.Input 
            _focus={{
              shadow: '!none',
              borderColor: '!purple.500',
            }}
            rounded="md"/>
        </NumberInput.Root>
      </HStack>
      {help && <Field.HelperText color="gray.600" fontSize="xs">{help}</Field.HelperText>}
    </Field.Root>
  );
};

export default Range;