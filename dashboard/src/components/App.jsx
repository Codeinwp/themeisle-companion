import { Box } from "@chakra-ui/react";
import { useState } from "@wordpress/element";
import "../style.scss";
import { getTabHash } from "../utils/common";
import Header from "./Header";
import { Provider as ChakraProvider } from "./ui/provider";
import { Toaster } from "./ui/toaster";

import AvailableModules from "./AvailableModules";
import RecommendedPlugins from "./RecommendedPlugins";

const App = () => {
  const hash = getTabHash();
  const [activeTab, setActiveTab] = useState(hash ? hash : "modules");

  return (
    <ChakraProvider>
      <Header activeTab={activeTab} setActiveTab={setActiveTab} />

      <Box pt="8" pb="16" bg="bg.muted" flexGrow={1}>
        {activeTab === "modules" && <AvailableModules />}
        {activeTab === "plugins" && <RecommendedPlugins />}
      </Box>

      <Toaster />
    </ChakraProvider>
  );
};

export default App;
