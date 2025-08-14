import { Field, Switch } from '@chakra-ui/react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';

const Toggle = ({label, id, help=''}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const handleChange = ({checked}) => {
    setOption(id, checked);

    if( id === 'button_display_below' ) {
      wp.customize.previewer.refresh();
    }
  };

  const isChecked = getOption(id);

  return ( 
    <Field.Root>
      <Switch.Root colorPalette="purple" size="sm" w="full" onCheckedChange={handleChange} checked={isChecked}>
        <Switch.HiddenInput />
        <Switch.Label>{label}</Switch.Label>
        <Switch.Control bg={isChecked ? "purple.500" : "gray.500"} borderRadius="full" ml="auto">
          <Switch.Thumb bg="white" />
        </Switch.Control>
      
      </Switch.Root>
      <Field.HelperText color="gray.600" fontSize="xs">{help}</Field.HelperText>
    </Field.Root>
  );
};

export default Toggle;