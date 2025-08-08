import { Box, Button, Center, Color, ColorPicker, ColorPickerChannelSlider, Flex, parseColor } from '@chakra-ui/react';
import { Pipette } from 'lucide-react';

const Picker = ({label, value, handleChange, alpha=true, eyedropperInside=true, attached=false, ...props}) => {

  const color = parseColor(value);
  
  return (    
    <ColorPicker.Root
      value={color}
      format="rgba"
      onValueChange={handleChange}
      size="2xs"
      colorPalette={'purple'}
      {...props}
    >
      <ColorPicker.HiddenInput />
      <ColorPicker.Control>
        <Center inline w="full" gap={2}>
          <Center inline gap={1}>
            <ColorPicker.Trigger asChild>
              <Button
                bg={value}
                size="icon"
                variant="outline"
                borderColor="gray.400"
                colorPalette="gray"
                rounded='md'
                {...(attached ? {
                  roundedLeft: '!none',
                } : {})}
              />
            </ColorPicker.Trigger>
            {! eyedropperInside && (
              <ColorPicker.EyeDropper variant="outline" size="icon" p={1.5}>
                <Pipette size={15}/>
              </ColorPicker.EyeDropper>
            )}

          </Center>
        </Center>

      </ColorPicker.Control>
      
      <ColorPicker.Positioner>
        <ColorPicker.Content>
          <ColorPicker.Area />

          <Flex gap={2} align="center">
            {eyedropperInside && (
              <ColorPicker.EyeDropper variant="outline" size="icon" p={1.5}>
                <Pipette size={15}/>
              </ColorPicker.EyeDropper> 
            )}
            <Flex direction="column" w="full" gap={1.5}>
              <ColorPickerChannelSlider channel="hue"/>
              {alpha && <ColorPickerChannelSlider channel="alpha"/>}
            </Flex>
          </Flex>
          <ColorPicker.Input />
        </ColorPicker.Content>
      </ColorPicker.Positioner>
    </ColorPicker.Root>
  );
};

export default Picker;