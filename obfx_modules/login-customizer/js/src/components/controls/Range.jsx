import { Badge, Box, Center, Flex, NumberInput, Slider } from '@chakra-ui/react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import ResetButton from './ResetButton';

const Range = ({label, id, max=800, min=0, unit='px'}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const handleChange = ({value}) => {
    setOption(id, value[0]);
  };

  const handleNumberChange = ({value}) => {
    setOption(id, value);
  };

  return (
    <Box w="full">
      <Slider.Root
        colorPalette="purple"
        value={[getOption(id)]}
        size="sm"
        onValueChange={handleChange}
        flexGrow={1}
        max={max}
        min={min}
        gap={0}
      >
        <Flex gap={1} align="center" w="full" mb={2}>
          <ResetButton id={id} />
          <Slider.Label>{label}</Slider.Label>
          {unit &&( 
            <Badge colorPalette="gray" variant="surface" size="xs" ml='auto' textTransform="uppercase">
              {unit}
            </Badge>
          )}
        </Flex>
        <Flex gap={5}>
          <Slider.Control flexGrow={1}>
            <Slider.Track>
              <Slider.Range />
            </Slider.Track>
            <Slider.Thumbs />
          </Slider.Control>
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
        </Flex>
      </Slider.Root>

      
    

    </Box>
  );
};

export default Range;