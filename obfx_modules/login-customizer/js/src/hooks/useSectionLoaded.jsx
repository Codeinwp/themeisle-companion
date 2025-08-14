import { useState, useEffect } from '@wordpress/element';
import { isNull } from 'lodash';

const {loginUrl} = window.OBFXData;

/**
 * This hook is used to check if a section is loaded.
 *
 * @param {string} section - The section to check if it is loaded.
 * @return {boolean} - A boolean value indicating if the section is loaded.
 */
export const useSectionLoaded = (section) => {
  const [isLoaded, setIsLoaded] = useState(false);

  const handleSectionLoaded = (sectionData) => {
    if( sectionData === false ) {
      setIsLoaded(false);
      
      return;
    }

    const loaded = sectionData.id === section; 
    if(loaded) {
      wp.customize.previewer.previewUrl.set(loaded ? loginUrl : '/');  
    } 

    setIsLoaded(loaded);
  };

  useEffect(() => {
    wp.customize.state('expandedSection').bind(handleSectionLoaded);

    return () => {
      wp.customize.state('expandedSection').unbind(handleSectionLoaded);
    };
  }, [section]);

  return {
    sectionLoaded: isLoaded,
  };
};