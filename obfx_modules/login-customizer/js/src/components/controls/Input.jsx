import { Field, HStack, Input } from "@chakra-ui/react"
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import ResetButton from "./ResetButton";

const InputControl = ({label, help = '', placeholder = '', id}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const handleChange = (e) => {
    let value = e.target.value;

    // Only allow end paths with - or _
    if( id === 'login_url' ) {
      value = value.trim().replace(/^\/+/, '').replace(/[^a-zA-Z0-9_-]+/g, '');
    }

    setOption(id, value);
  };

  return (
    <Field.Root>
      <HStack>
        <ResetButton id={id} />
        <Field.Label>
          {label} 
        </Field.Label>
      </HStack>
      <Input
        onChange={handleChange}
        value={getOption(id)} 
        size="xs" 
        colorPalette="purple" 
        variant="outline"
        bg="white"
        borderColor="gray.300"
        borderRadius="md"
        placeholder={placeholder}
      />
      {help && <Field.HelperText color="gray.600" fontSize="xs">{help}</Field.HelperText>}
    </Field.Root>
  );
};

export default InputControl;