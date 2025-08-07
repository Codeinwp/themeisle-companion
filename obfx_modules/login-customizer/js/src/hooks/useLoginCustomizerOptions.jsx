import { createContext, useState, useContext, useRef } from "@wordpress/element";

const LoginCustomizerOptionsContext = createContext({});

const {defaultValues, optionKey, loginUrl} = window.OBFXData;

const OptionsProvider = ({ children }) => {
  
  const initialValue = { ...defaultValues, ...window.wp.customize.control(optionKey).setting.get() };

  const [options, setOptions] = useState(initialValue);
  const debounceTimeoutRef = useRef(null);

  const updateRootOption = (data) => {
    if (debounceTimeoutRef.current) {
      clearTimeout(debounceTimeoutRef.current);
    }
    
    debounceTimeoutRef.current = setTimeout(() => {
      window.wp.customize.control(optionKey).setting.set(data);
    }, 100);
  };

  const setOption = (key, value) => {
    const newData = { ...options, [key]: value };

    setOptions(newData);
    updateRootOption(newData);
  };

  const resetOptions = () => {
    setOptions(window.OBFXData.defaultValues);
    updateRootOption(window.OBFXData.defaultValues);
    window.wp.customize.previewer.refresh();
  };

  const resetOption = (key) => {
    const newData = { ...options, [key]: defaultValues[key] };
    setOptions(newData);
    updateRootOption(newData);
  };

  const getOption = (key) => {
    return options[key];
  };

  return (
    <LoginCustomizerOptionsContext.Provider value={{ options, setOption, resetOptions, getOption, resetOption }}>
      {children}
    </LoginCustomizerOptionsContext.Provider>
  );
};
 
const useLoginCustomizerOptions = () => {
  const context = useContext(LoginCustomizerOptionsContext);
  
  if (!context) {
    throw new Error('useLoginCustomizerOptions must be used within an OptionsProvider');
  }
  
  return context;
};

export { useLoginCustomizerOptions, OptionsProvider };