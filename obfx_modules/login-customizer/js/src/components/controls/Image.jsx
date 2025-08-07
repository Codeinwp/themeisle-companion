import { Box, Button, ButtonGroup, Icon, IconButton, Image, Skeleton, Text } from '@chakra-ui/react';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { MediaUpload } from '@wordpress/media-utils';
import { ImagePlus, PencilIcon, TrashIcon } from 'lucide-react';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import { Tooltip } from '../ui/tooltip';

const ImageControl = ({label, id}) => {
  const { getOption, setOption } = useLoginCustomizerOptions();

  const [imageLoaded, setImageLoaded] = useState(false);

  const imageURL = getOption(id);

  const handleChange = ({url}) => {
    setOption(id, url);
  };
  
  const handleRemove = () => {
    setOption(id, '');
    wp.customize.previewer.refresh();
  };

  return (
    <Box w="full">
      <Text
        fontSize="sm"
        color="gray.600" 
        fontWeight="medium"
        m={0}
        mb={2}
      >{label}</Text>

      {imageURL && (
        <Box position="relative">
          <Image
            visibility={imageLoaded ? 'visible' : 'hidden'}
            onLoad={() => {
              setImageLoaded(true);
            }}
            src={imageURL}
            alt={label}
            w="full"
            rounded="md"
            border="1px solid"
            borderColor="gray.200" 
            aspectRatio={"2/1"}
            objectFit="contain"
            bg="white"
          />

          {! imageLoaded && (
            <Skeleton w="full" rounded="md" aspectRatio={"2/1"} />
          )}

          <ButtonGroup 
            attached
            position="absolute"
            bottom={2}
            right={2}
            colorPalette="purple"
            size="xs" 
            zIndex={10}
          >

            <MediaUpload
              onSelect={handleChange}
              allowedTypes={['image']}
              render={({ open }) => (
                <IconButton onClick={open} roundedRight="none">
                  <Tooltip content={__('Replace Image', 'themeisle-companion')}>
                    <Icon as={PencilIcon} />
                  </Tooltip>
                </IconButton>
              )}/>
            
            
            <IconButton colorPalette="red" onClick={handleRemove}>
              <Tooltip content={__('Remove Image', 'themeisle-companion')}>
                <Icon as={TrashIcon} />
              </Tooltip>
            </IconButton>
          </ButtonGroup>
        </Box>
      )}
      
      {! imageURL && (
        <MediaUpload
          onSelect={handleChange}
          allowedTypes={['image']}
          render={({ open }) => (
            <Button
              onClick={open}
              variant="outline"
              size="sm"
              colorPalette="purple"
              w="full"
              _hover={{bg: 'purple.500', color: 'white'}}
            >
              <Icon as={ImagePlus}/>
              {__('Add Image', 'themeisle-companion')}
            </Button>
          )}
        />
      )}
    </Box>
  );
};

export default ImageControl;