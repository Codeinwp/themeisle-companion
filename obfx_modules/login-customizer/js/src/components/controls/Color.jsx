import { ColorPicker, HStack, Portal, parseColor, Flex, ButtonGroup, VStack, Box, Center, Button, Icon, IconButton, ColorPickerChannelSlider } from '@chakra-ui/react';
import { useState } from '@wordpress/element';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import { Tooltip } from '../ui/tooltip';
import { __ } from '@wordpress/i18n';
import { Pipette, RefreshCcw, RotateCcw } from 'lucide-react';
import ResetButton from './ResetButton';
import Picker from '../ui/colorPicker';

const Color = ({label, id}) => {
  const { getOption, setOption, resetOption } = useLoginCustomizerOptions();

  const handleChange = ({valueAsString}) => {
    setOption(id, valueAsString);
  };

  const value = getOption(id);
  const color = parseColor(value);

  const handleReset = () => {
    resetOption(id);
  };

  return (
    <Center w="full" gap={1}>
      <ResetButton id={id} />
      <Picker label={label} value={value} handleChange={handleChange} w="full" size="xs"/>
    </Center> 
  );
};

export default Color;