import { Badge, Box, Button, Flex, HStack, Icon, InputGroup, NumberInput, Span, Text, VStack } from '@chakra-ui/react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import ResetButton from './ResetButton';
import { useState } from '@wordpress/element';
import { Link2, Link2Off } from 'lucide-react'
import { __ } from '@wordpress/i18n';

const LABELS = {
  top: __('Top', 'themeisle-companion'),
  right: __('Right', 'themeisle-companion'),
  bottom: __('Bottom', 'themeisle-companion'),
  left: __('Left', 'themeisle-companion'),
}

const LINKED_LABELS = {
  top: __('Vertical', 'themeisle-companion'),
  right: __('Horizontal', 'themeisle-companion'),
}

const Range = ({label, id, max=800, min=0, unit='px'}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();
  const [top,right,bottom,left] = getOption(id).split(' ').map(value => value.replace(unit, ''));

  const values = {
    top: top ? top : 0,
    right: right ? right : top,
    bottom: bottom ? bottom : top,
    left: left ? left : right || top,
  }

  const [linked, setLinked] = useState(values.top === values.bottom && values.left === values.right);

  const handleChange = ({value}) => {
    setOption(id, value[0]);
  };

  const toggleLinked = () => {
    setLinked(!linked);
  }

  const onValueChange = (value, key) => {
    const newValues = {
      ...values,
      [key]: value
    }
    

    if( linked ) {
      if( key === 'top' ) {
        newValues.bottom = newValues.top;
      } else if( key === 'right' ) {
        newValues.left = newValues.right;
      } else if( key === 'bottom' ) {
        newValues.top = newValues.bottom;
      } else if( key === 'left' ) {
        newValues.right = newValues.left;
      }
    }

    setOption(id, Object.values(newValues).map(value => value + unit).join(' '));
  }

  return (
    <Box w="full">
      <Flex gap={1} align="center" w="full" mb={2}>
        <ResetButton id={id} />  
        <Text
          fontSize="sm"
          color="gray.600" 
          fontWeight="medium"
          m={0}
        >{label}
        </Text>
        {unit &&( 
          <Badge colorPalette="gray" variant="surface" size="xs" ml='auto' textTransform="uppercase">
            {unit}
          </Badge>
        )}
      </Flex>
      
      <HStack gap={0} align="start" spaceX="-1px">
        {
          Object.entries(values).map(([key, value]) => {
            if(linked && ['bottom', 'left'].includes(key)) {
              return null;
            }

            return (
              <VStack key={key} gap={0}>
                <NumberInput.Root
                  size="xs"
                  key={key}
                  value={value}
                  onValueChange={({ value: newValue }) => onValueChange(newValue, key)}
                  max={max}
                  min={min}
                >
                  <NumberInput.Control />
                  <NumberInput.Input
                    rounded="!none"
                    roundedLeft={key === 'top' ? '!md' : '!none'}
                    colorPalette={'!purple'}
                    _focus={{
                      shadow: '!none',
                      borderColor: '!purple.500',
                    }} />

                </NumberInput.Root>
                <Span
                  fontSize="2xs"
                  textTransform="uppercase"
                  color="gray.600"
                  fontWeight="medium"
                  m={0}>
                  {linked && !!LINKED_LABELS[key] ? LINKED_LABELS[key] : LABELS[key]}
                </Span>
              </VStack>
            );
          })
        }

        <Button
          size="xs"
          p={1}
          colorPalette="purple"
          variant={"outline"}
          onClick={toggleLinked} 
          roundedLeft="none"
          borderColor="gray.400"
          background={linked ? 'purple.50' : 'transparent'}
        >
          {linked && <Link2 />}
          {!linked && <Link2Off />}
        </Button>
      </HStack>
 
      
    

    </Box>
  );
};

export default Range;