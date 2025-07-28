/* global obfxDash */
import ModuleCard from "./ModuleCard";
import { ModulesContext } from "./DashboardContext";
import { useState } from "@wordpress/element";
import { Container, SimpleGrid, VStack } from "@chakra-ui/react";

const { modules, data } = obfxDash;

const AvailableModules = () => {
  const [modulesData, setModulesData] = useState(
    data === "" ? { module_status: {}, module_settings: {} } : { ...data }
  );

  if (!modulesData.module_settings) {
    setModulesData({ ...modulesData, module_settings: {} });
  }

  if (!modulesData.module_status) {
    setModulesData({ ...modulesData, module_status: {} });
  }

  const renderModules = () => {
    return;
  };

  return (
    <ModulesContext.Provider value={{ modulesData, setModulesData }}>
      <Container>
        <SimpleGrid columns={{ base: 1, lg: 2 }} gap="5">
          <VStack columns={{ base: 1, lg: 2 }} gap="5" w="full" alignItems="stretch">
            {Object.entries(modules).map(([slug, details], idx) => {
              if (idx % 2 === 0) {
                return <ModuleCard slug={slug} details={details} key={slug} />;
              }
              return null;
            })}
          </VStack>
          <VStack columns={{ base: 1, lg: 2 }} gap="5" w="full" alignItems="stretch">
            {Object.entries(modules).map(([slug, details], idx) => {
              if (idx % 2 === 0) {
                return null;
              }
              return <ModuleCard slug={slug} details={details} key={slug} />;
            })}
          </VStack>
        </SimpleGrid>
      </Container>
    </ModulesContext.Provider>
  );
};

export default AvailableModules;
