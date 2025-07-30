/* global obfxDash */
import { PluginsContext } from "./DashboardContext";
import PluginCard from "./PluginCard";
import { useState } from "@wordpress/element";
import { Container, SimpleGrid } from "@chakra-ui/react";

const RecommendedPlugins = () => {
  const { plugins } = obfxDash;

  const [pluginsData, setPluginsData] = useState(plugins);

  if (!plugins || Object.keys(plugins).length === 0) {
    return null;
  }

  return (
    <PluginsContext.Provider value={{ pluginsData, setPluginsData }}>
      <Container>
        <SimpleGrid columns={{ base: 1, lg: 2, xl: 3 }} gap={5}>
          {Object.keys(plugins).map((slug) => {
            return <PluginCard key={slug} slug={slug} data={plugins[slug]} />;
          })}
        </SimpleGrid>
      </Container>
    </PluginsContext.Provider>
  );
};

export default RecommendedPlugins;
