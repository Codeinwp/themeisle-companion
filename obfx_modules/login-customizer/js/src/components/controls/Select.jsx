import { Center, Field, NativeSelect } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import ResetButton from './ResetButton';

const Select = ({label, id, options, disabled}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const handleChange = (e) => {
    setOption(id, e.currentTarget.value);
  };

  return (
    <Field.Root>
      <Center justifyContent="start" gap={1}>
        <ResetButton id={id} disabled={disabled}/>
        <Field.Label>{label}</Field.Label>
      </Center>
      <NativeSelect.Root size="sm" width="full" colorPalette="purple" bgImage="none">
        <NativeSelect.Field
          value={getOption(id)}
          onChange={handleChange}
          bg="!transparent"
          disabled={disabled}
          _focus={{
            shadow: '!none',
            borderColor: '!purple.500',
          }}
        >
          {options.map((option, idx) => (
            <option key={option.value} value={option.value}>{option.label}</option>
          ))}
        </NativeSelect.Field>
        <NativeSelect.Indicator />
      </NativeSelect.Root> 
    </Field.Root>
  );
};

export default Select;