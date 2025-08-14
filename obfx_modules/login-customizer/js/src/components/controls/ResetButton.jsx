import { Button, Icon, IconButton } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { RotateCcw, Undo, Undo2, UndoDot } from 'lucide-react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';

const ResetButton = ({id, disabled}) => {
  const { getOption, resetOption } = useLoginCustomizerOptions();

  const handleReset = () => {
    resetOption(id);
  };

  if(getOption(id) === window.OBFXData.defaultValues[id]) {
    return null;
  }

  return (
    <Button 
      colorPalette='purple'
      size='icon'
      variant='ghost'
      p={0.5}
      onClick={handleReset}
      aria-label={__('Reset', 'themeisle-companion')}
      disabled={disabled}
    > 
      <Icon as={Undo2} strokeWidth={3} size={"xs"}/>
    
    </Button>
  )
};

export default ResetButton;