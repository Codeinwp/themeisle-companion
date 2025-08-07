import { Box, Flex, Separator, Show, VStack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Color from '../controls/Color';
import Image from '../controls/Image';
import Range from '../controls/Range';
import { useLoginCustomizerOptions } from '../../hooks/useLoginCustomizerOptions';
import BackgroundAlignment from '../controls/BackgroundAlignment';
import Select from '../controls/Select';

const Background = () => {  

  const {getOption} = useLoginCustomizerOptions();

  return (
    <VStack align="flex-start" gap={5}>
      {! getOption('page_bg_image') && (
        <Color
          label={__('Page Background Color', 'themeisle-companion')}
          id="page_bg_color"/>
      )}
      <Image label={__('Image', 'themeisle-companion')} id="page_bg_image"/> 
      
      <Show when={getOption('page_bg_image')}>
        <Color label={__('Overlay Color', 'themeisle-companion')} id="page_bg_color" format="rgba"/>
        <Range label={__('Overlay Blur', 'themeisle-companion')} id="page_bg_overlay_blur" max={100}/>
        <Flex gap={5} w="full">
          <BackgroundAlignment label={__('Alignment', 'themeisle-companion')} id="page_bg_image_position" flexGrow={1}/>

          <Separator orientation="vertical" />

          <VStack flexGrow={1} gap={2} align="flex-start">
            <Select 
              label={__('Size', 'themeisle-companion')} id="page_bg_image_size" 
              options={[
                {label: __('Cover', 'themeisle-companion'), value: 'cover'}, 
                {label: __('Contain', 'themeisle-companion'), value: 'contain'}
              ]}
            />

            <Select 
              label={__('Repeat', 'themeisle-companion')} id="page_bg_image_repeat" 
              options={[
                {label: __('No Repeat', 'themeisle-companion'), value: 'no-repeat'}, 
                {label: __('Repeat', 'themeisle-companion'), value: 'repeat'},
                {label: __('Repeat X', 'themeisle-companion'), value: 'repeat-x'},
                {label: __('Repeat Y', 'themeisle-companion'), value: 'repeat-y'},
              ]}
              disabled={getOption('page_bg_image_size') === 'cover'}
            />
          </VStack>
        </Flex>
      </Show>

    </VStack>
  );
};

export default Background; 