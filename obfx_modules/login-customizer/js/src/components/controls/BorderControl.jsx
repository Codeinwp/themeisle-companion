import { Center, Flex, HStack, NativeSelect, NumberInput, Text, VStack } from "@chakra-ui/react";
import { __ } from "@wordpress/i18n";
import { useLoginCustomizerOptions } from "../../hooks/useLoginCustomizerOptions";
import Picker from "../ui/colorPicker";
import ResetButton from "./ResetButton";

const BorderControl = ({ label, id }) => {
  const {getOption, setOption} = useLoginCustomizerOptions();
  const value = getOption(id);
  const parsedValue = value.split(' ');
  const [borderWidthSuffixed, borderStyle, borderColor] = parsedValue;
  const borderWidth = parseInt(borderWidthSuffixed.replace('px', ''));

  const handleStyleChange = (e) => {
    setOption(id, `${borderWidth}px ${e.currentTarget.value} ${borderColor}`);
  }

  const handleWidthChange = ({value}) => {
    setOption(id, `${value}px ${borderStyle} ${borderColor}`);
  }

  const handleColorChange = ({valueAsString}) => {
    const colorValue = valueAsString.replaceAll(' ', '');
    setOption(id, `${borderWidth}px ${borderStyle} ${colorValue}`);
  }

  const BORDER_TYPES = {
    'solid': __('Solid', 'themeisle-companion'),
    'dashed': __('Dashed', 'themeisle-companion'),
    'dotted': __('Dotted', 'themeisle-companion'),
    'double': __('Double', 'themeisle-companion'),
    'groove': __('Groove', 'themeisle-companion'),
    'ridge': __('Ridge', 'themeisle-companion'),
    'inset': __('Inset', 'themeisle-companion'),
  }

  return (
    <HStack gap={5} w="full">
      <Flex gap={1} align="center">
        <ResetButton id={id} />
        <Text
          fontWeight="medium"
          color="gray.600" 
          fontSize="sm"
          m={0}
        >
          {label}
        </Text>
      </Flex>

      <HStack gap={0} align="center" spaceX="-1px" ml="auto">
        <NumberInput.Root 
          onValueChange={handleWidthChange}
          disabled={borderStyle === 'none'}
          colorPalette="purple"
          value={borderWidth}
          bgImage="none"
          size="xs"
          w="100px"
        >
          <NumberInput.Control />
          <NumberInput.Input
            rounded="md"
            roundedRight="!none"
            _focus={{
              shadow: '!none',
              borderColor: '!purple.500',
            }}
          />
      
        </NumberInput.Root>


        <NativeSelect.Root 
          disabled={parseInt(borderWidth) === 0}
          colorPalette="purple"
          size="sm" 
        >
          <NativeSelect.Field
            value={borderStyle}
            onChange={handleStyleChange}
            bgImage="!none"
            rounded="!none"
            borderX="!none"
            _focus={{
              shadow: '!none',
              borderColor: '!purple.500',
            }}
          >
            {Object.entries(BORDER_TYPES).map(([value, label]) => (
              <option key={value} value={value}>{label}</option>
            ))}
          </NativeSelect.Field>
          <NativeSelect.Indicator />
        </NativeSelect.Root>


        <Picker 
          attached
          disabled={parseInt(borderWidth) === 0 || borderStyle === 'none'}
          handleChange={handleColorChange} 
          value={borderColor}
          alpha={false}
          size="xs"
        />
      </HStack>
    </HStack>
  );
};

export default BorderControl;