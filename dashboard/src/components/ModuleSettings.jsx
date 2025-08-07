/* global obfxDash */
import {
  Button,
  ButtonGroup,
  Collapsible,
  HStack,
  Icon,
  SimpleGrid,
  Text,
  VStack,
} from "@chakra-ui/react";
import { ChevronDown } from "lucide-react";

import { requestData } from "../utils/rest";
import { ModulesContext } from "./DashboardContext";

import classnames from "classnames";
import { isEqual } from "lodash";

import { Dashicon } from "@wordpress/components";
import { useContext, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import ModuleControl from "./ModuleControl";
import { toaster } from "./ui/toaster";

const { options, root, setSettingsRoute } = obfxDash;

const ModuleSettings = ({ slug }) => {
  const { modulesData, setModulesData } = useContext(ModulesContext);
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const moduleSettings = modulesData.module_settings[slug] || {};
  const [tempData, setTempData] = useState({
    ...moduleSettings,
  });

  const loadingIcon = (
    <Dashicon size={18} icon="update" className="is-loading" />
  );

  const changeOption = (name, newValue) => {
    const newTemp = tempData;
    newTemp[name] = newValue;
    setTempData({ ...newTemp });
  };

  const sendData = () => {
    setLoading(true);

    const dataToSend = {
      slug,
      value: tempData,
    };

    requestData(root + setSettingsRoute, false, dataToSend).then((r) => {
      if (r.type !== "success") {
        setTempData({ ...moduleSettings });
        setLoading(false);
        toaster.error({
          title: __(
            "Could not update options. Please try again.",
            "themeisle-companion"
          ),
        });
        return;
      }

      modulesData.module_settings[slug] = { ...tempData };
      setModulesData({ ...modulesData });
      setLoading(false);
      toaster.success({
        title: __("Options updated successfully.", "themeisle-companion"),
      });
    });
  };

  const getContent = () => {
    const content = [];
    for (let i = 0; i < options[slug].length; i++) {
      let element = options[slug][i];

      if (element.title && element.label) {
        content.push(
          <Text
            key={`${element.id}-title`}
            m="0"
            fontWeight="medium"
            fontSize="sm"
          >
            {element.title}
          </Text>
        );
      }

      if (element.hasOwnProperty("before_wrap")) {
        const row = [];

        while (true) {
          row.push(
            <ModuleControl
              key={`${element.id}-control`}
              setting={element}
              tempData={tempData}
              changeOption={changeOption}
            />
          );
          if (element.hasOwnProperty("after_wrap")) break;
          element = options[slug][++i];
        }

        content.push(
          <HStack
            key={`${element.id}-hstack`}
            alignItems="start"
            gap="5"
            borderBottom="1px solid"
            borderColor="border"
            pb="3"
            w="full"
          >
            {row}
          </HStack>
        );
        continue;
      }

      if (element.type === "checkbox") {
        const row = [];
        while (element.type === "checkbox") {
          row.push(
            <ModuleControl
              key={element.id}
              setting={element}
              tempData={tempData}
              changeOption={changeOption}
            />
          );
          element = options[slug][++i];
        }
        content.push(
          <SimpleGrid
            w="full"
            columns={{ base: 3, md: 4, lg: 2, "2xl": 4 }}
            key={`${element.id}-hstack`}
            alignItems="start"
            gap="5"
          >
            {row}
          </SimpleGrid>
        );
        i--;
        continue;
      }

      content.push(
        <ModuleControl
          key={element.id}
          setting={element}
          tempData={tempData}
          changeOption={changeOption}
        />
      );
    }

    return content;
  };

  return (
    <Collapsible.Root open={open}>
      <Button
        variant="ghost"
        border="0"
        borderTop="1px solid"
        borderX="0"
        w="full"
        borderColor="border"
        rounded="none"
        justifyContent="space-between"
        fontWeight="medium"
        fontSize="sm"
        onClick={() => setOpen(!open)}
      >
        {__("Settings", "themeisle-companion")}
        <Icon
          rotate={open ? "-180deg" : "0deg"}
          transition={"all 0.2s ease-in-out"}
        >
          <ChevronDown />
        </Icon>
      </Button>

      <Collapsible.Content>
        <VStack
          padding="4"
          borderTop="1px solid"
          borderColor="border"
          alignItems="start"
          className={`obfx-settings-${slug} obfx-module-settings-wrap`}
          gap="4"
        >
          {getContent()}

          <ButtonGroup
            size="sm"
            colorPalette="purple"
            justifyContent="end"
            w="full"
          >
            <Button variant="outline" onClick={() => setOpen(false)}>
              {__("Close", "themeisle-companion")}
            </Button>
            <Button
              loading={loading}
              disabled={isEqual(tempData, moduleSettings)}
              onClick={sendData}
            >
              {__("Save", "themeisle-companion")}
            </Button>
          </ButtonGroup>
        </VStack>
      </Collapsible.Content>
    </Collapsible.Root>
  );

  return (
    <div className={classnames(["module-settings", open ? "open" : "closed"])}>
      <button
        aria-expanded={open}
        className="accordion-header"
        onClick={() => setOpen(!open)}
      >
        <div className="accordion-title"> Settings </div>
        <Dashicon icon={open ? "arrow-up-alt2" : "arrow-down-alt2"} />
      </button>
      {open && (
        <div
          className={classnames([
            "accordion-content",
            loading ? "loading" : "",
          ])}
        >
          {getContent()}
          <div className="buttons-container">
            <Button
              isSecondary
              className="obfx-button"
              onClick={() => setOpen(false)}
            >
              {__("Close", "themeisle-companion")}
            </Button>
            <Button
              isPrimary
              disabled={isEqual(tempData, moduleSettings)}
              className="obfx-button"
              onClick={sendData}
            >
              {loading ? loadingIcon : __("Save", "themeisle-companion")}
            </Button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ModuleSettings;
