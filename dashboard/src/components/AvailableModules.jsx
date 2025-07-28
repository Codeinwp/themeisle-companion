/* global obfxDash */
import ModuleCard from "./ModuleCard";
import { ModulesContext } from "./DashboardContext";
import { useMemo, useState } from "@wordpress/element";
import { Container, For, SimpleGrid, VStack } from "@chakra-ui/react";

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

  const leftColumn = useMemo(
    () => Object.entries(modules).filter((_, idx) => idx % 2 === 0),
    []
  );
  const rightColumn = useMemo(
    () => Object.entries(modules).filter((_, idx) => idx % 2 === 1),
    []
  );

  return (
    <ModulesContext.Provider value={{ modulesData, setModulesData }}>
      <Container>
        <SimpleGrid columns={{ base: 1, lg: 2 }} gap="5">
          <VStack
            columns={{ base: 1, lg: 2 }}
            gap="5"
            w="full"
            alignItems="stretch"
          >
            <For each={leftColumn}>
              {([slug, details]) => {
                return <ModuleCard slug={slug} details={details} key={slug} />;
              }}
            </For>
          </VStack>
          <VStack
            columns={{ base: 1, lg: 2 }}
            gap="5"
            w="full"
            alignItems="stretch"
          >
            <For each={rightColumn}>
              {([slug, details]) => {
                return <ModuleCard slug={slug} details={details} key={slug} />;
              }}
            </For>
          </VStack>
        </SimpleGrid>
      </Container>
    </ModulesContext.Provider>
  );
};

export default AvailableModules;
