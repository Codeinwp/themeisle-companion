import { Box, Button, IconButton, Image, Text, Icon, ButtonGroup, Skeleton, SimpleGrid, Center, Flex } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { MediaUpload } from '@wordpress/media-utils';
import { 
  MoveUpLeft,
  MoveUp,
  MoveUpRight,
  MoveLeft,
  Move,
  MoveRight,
  MoveDownLeft,
  MoveDown,
  MoveDownRight,
  PencilIcon,
  TrashIcon,
  Dot,
  Maximize,
  Minimize,
} from 'lucide-react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import { Tooltip } from '../ui/tooltip';
import { useState } from '@wordpress/element';
import ResetButton from './ResetButton';

const ALIGNMENTOPTIONS = {
  'top left': { label: __('Top Left', 'themeisle-companion'), icon: MoveUpLeft },
  'top center': { label: __('Top Center', 'themeisle-companion'), icon: MoveUp },
  'top right': { label: __('Top Right', 'themeisle-companion'), icon: MoveUpRight },
  'center left': { label: __('Center Left', 'themeisle-companion'), icon: MoveLeft },
  'center': { label: __('Center', 'themeisle-companion'), icon: Dot },
  'center right': { label: __('Center Right', 'themeisle-companion'), icon: MoveRight },
  'bottom left': { label: __('Bottom Left', 'themeisle-companion'), icon: MoveDownLeft },
  'bottom center': { label: __('Bottom Center', 'themeisle-companion'), icon: MoveDown },
  'bottom right': { label: __('Bottom Right', 'themeisle-companion'), icon: MoveDownRight },
}

const BackgroundAlignment = ({label, id}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const handleChange = (value) => {
    setOption(id, value);
  };

  const roundnessMap = {
    0: 'roundedTopLeft',
    2: 'roundedTopRight',
    6: 'roundedBottomLeft',
    8: 'roundedBottomRight',
  }

  const getRoundedProps = (idx) => {
    if( ! roundnessMap[idx] ) {
      return {};
    }

    return {
      [roundnessMap[idx]]: "md",
    }
  }

  return (
    <Box>
      <Flex alignItems="center" gap={1} mb={2}>
        <ResetButton id={id} />
        <Text fontSize="sm" color="gray.600" fontWeight="medium" m={0}>{label}</Text>
      </Flex>


      <Flex position="relative">
        <SimpleGrid columns={3} gap={0} w="auto">
          {Object.entries(ALIGNMENTOPTIONS).map(([value, {label, icon}], idx) => (
            <Tooltip content={label} key={value} skipAnimationOnMount>
              <IconButton 
                aspectRatio={"1/1"}
                key={value} 
                size="xs"
                variant={ getOption(id) === value ? "solid" : "surface" }
                colorPalette="purple" 
                _hover={{bg: 'purple.500', color: 'white'}}
                onClick={() => handleChange(value)}
                rounded="none"
                {...getRoundedProps(idx)}
              >
                <Icon as={icon} size="sm" />
              </IconButton>
            </Tooltip>
          ))}
        </SimpleGrid>      
      </Flex>
    </Box>
  );
};

export default BackgroundAlignment;