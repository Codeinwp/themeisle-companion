/* global obfxDash */
import {
  Alert,
  Button,
  Card,
  Flex,
  HStack,
  Spinner,
  Switch,
  Text
} from "@chakra-ui/react";
import { requestData } from "../utils/rest";
import { ModulesContext } from "./DashboardContext";
import ModuleSettings from "./ModuleSettings";

import { renderToString, useContext, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { ExternalLinkIcon } from "lucide-react";
import ExternalLink from "./ExternalLink";
import ObfxBadge from "./ObfxBadge";
import { toaster } from "./ui/toaster";

const { root, toggleStateRoute, options, menusSupport } = obfxDash;

const ModuleCard = ({ slug, details }) => {
  const refreshAfterEnabled = details.refresh_after_enabled;
  const activeDefault = details.active_default;
  const documentationUrl = details.documentation_url;
  const [loading, setLoading] = useState(false);
  const { modulesData, setModulesData } = useContext(ModulesContext);
  const moduleStatus = modulesData.module_status;

  const updateModuleStatus = ({ checked }) => {
    setLoading(true);

    const dataToSend = { slug, value: checked };
    requestData(root + toggleStateRoute, false, dataToSend).then((r) => {
      toggleStatusCallback(r, checked);
    });
  };

  const toggleStatusCallback = (r, value) => {
    if (r.type !== "success") {
      setLoading(false);
      toaster.error({
        title: __(
          "Could not activate module. Please try again.",
          "themeisle-companion"
        ),
      });
      return;
    }

    if (refreshAfterEnabled) {
      window.location.reload();
    }

    if (!moduleStatus[slug]) {
      moduleStatus[slug] = {};
    }

    moduleStatus[slug].active = value;
    setModulesData(modulesData);
    setLoading(false);
    toaster.success({
      title:
        (value
          ? __("Module activated", "themeisle-companion")
          : __("Module deactivated", "themeisle-companion")) +
        ` (${details.name})`,
    });
  };

  const renderDescription = (description) => {
    const elements = [];

    while (description.indexOf("<a") >= 0) {
      const start = description.indexOf("<a");
      const end = description.indexOf("</a>");

      elements.push(description.slice(0, start));

      const hrefStart = description.indexOf('href="') + 'href="'.length;
      const hrefEnd = description.indexOf('"', hrefStart);
      const href = description.slice(hrefStart, hrefEnd);

      const anchorText = description.slice(
        description.indexOf(">", start) + 1,
        end
      );

      if (description.includes("neve-pro-notice")) {
        elements.push(
          renderToString(
            <Button
              asChild
              variant="outline"
              colorPalette="purple"
              size="xs"
              color="purple.500"
              _hover={{
                color: "white",
                bg: "purple.500",
              }}
              _focus={{
                outline: "2px solid",
                outlineColor: "purple.500",
                outlineOffset: "2px",
                shadow: "none",
                bg: "purple.500",
                color: "white",
              }}
            >
              <a href={href}>
                {anchorText} <ExternalLinkIcon />{" "}
              </a>
            </Button>
          )
        );
      } else {
        elements.push(
          renderToString(<ExternalLink href={href}>{anchorText}</ExternalLink>)
        );
      }


      description = description.slice(end + "</a>".length);
    }

    elements.push(description);
    return (
      <Text
        fontSize="sm"
        lineHeight="1.6"
        m="0"
        color="fg.muted"
        dangerouslySetInnerHTML={{ __html: elements.join(" ") }}
      />
    );
  };

  const isActive =
    moduleStatus[slug] && moduleStatus[slug].active !== undefined
      ? moduleStatus[slug].active
      : activeDefault;

  let notices = details.notices || [];

  return (
    <Card.Root size="sm" overflow="hidden" variant="outline">
      <Card.Title
        fontSize="base"
        m="0"
        borderBottom="1px solid"
        borderColor="border"
        p="4"
      >
        <Flex justifyContent="space-between" alignItems="center">
          <HStack>
            {details.name}

            {documentationUrl && (
              <ObfxBadge
                href={documentationUrl}
                text={__("Docs", "themeisle-companion")}
              />
            )}
          </HStack>
          <HStack>
            {loading && <Spinner size="sm" color="purple.300" />}

            <Switch.Root
              colorPalette="purple"
              size="md"
              disabled={loading}
              checked={isActive}
              onCheckedChange={updateModuleStatus}
            >
              <Switch.HiddenInput />
              <Switch.Control>
                <Switch.Thumb />
              </Switch.Control>
            </Switch.Root>
          </HStack>
        </Flex>
      </Card.Title>
      <Card.Body>
        {renderDescription(details.description)}

        {isActive && details.module_main_action && (
          <Flex mt="3">
            <Button
              asChild
              variant="outline"
              colorPalette="purple"
              size="xs"
              color="purple.500"
              _hover={{
                color: "white",
                bg: "purple.500",
              }}
              _focus={{
                outline: "2px solid",
                outlineColor: "purple.500",
                outlineOffset: "2px",
                shadow: "none",
                bg: "purple.500",
                color: "white",
              }}
            >
              <a href={details.module_main_action.url}>
                {details.module_main_action.text}
              </a>
            </Button>
          </Flex>
        )}

        {notices.length > 0 && (
          <Flex mt="3">
            {notices.map((notice, idx) => {
              return (
                <Alert.Root
                  size="sm"
                  key={idx}
                  status={notice.type}
                  variant="surface"
                  display="flex"
                  alignItems="center"
                  p="2"
                >
                  <Alert.Indicator/>
                  <Alert.Content>
                    <Alert.Description>{notice.message}</Alert.Description>
                  </Alert.Content>
                </Alert.Root>
              );
            })}
          </Flex>
        )}
      </Card.Body>

      {isActive && options[slug].length > 0 && <ModuleSettings slug={slug} />}
    </Card.Root>
  );
};

export default ModuleCard;
