import {
  Button,
  Card,
  HStack,
  Image,
  Text,
  Span,
  Link,
  Badge,
  Skeleton,
} from "@chakra-ui/react";
import { ExternalLinkIcon } from "lucide-react";
import ExternalLink from "./ExternalLink";
import { DashboardContext, PluginsContext } from "./DashboardContext";
import { get } from "../utils/rest";

import classnames from "classnames";
import { useContext, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { toaster } from "./ui/toaster";

const PluginCard = ({ slug, data }) => {
  const [imageLoaded, setImageLoaded] = useState(false);
  const { banner, name, description, version, author, url, premium } = data;
  const { pluginsData, setPluginsData } = useContext(PluginsContext);
  const [inProgress, setInProgress] = useState(false);
  const pluginState = pluginsData[slug].action;

  const setPluginState = (newStatus) => {
    const newPluginsData = {
      ...pluginsData,
      [slug]: {
        ...pluginsData[slug],
        action: newStatus,
      },
    };
    setPluginsData(newPluginsData);
  };

  const errorMessage = __(
    "Something went wrong. Please try again.",
    "themeisle-companion"
  );

  const stringMap = {
    install: {
      static: __("Install", "themeisle-companion"),
      progress: __("Installing", "themeisle-companion"),
    },
    activate: {
      static: __("Activate", "themeisle-companion"),
      progress: __("Activating", "themeisle-companion"),
    },
    deactivate: {
      static: __("Deactivate", "themeisle-companion"),
      progress: __("Deactivating", "themeisle-companion"),
    },
    external: __("See more details", "themeisle-companion"),
  };

  const handleActionButton = () => {
    setInProgress(true);
    if ("install" === pluginState) {
      installPlugin(slug).then((r) => {
        if (!r.success) {
          setInProgress(false);
          toaster.error({
            title: errorMessage,
          });
          return;
        }
        setPluginState("activate");
        setInProgress(false);
      });
      return;
    }

    get(data[pluginState], true).then((r) => {
      if (!r.ok) {
        setInProgress(false);
        toaster.error({
          title: errorMessage,
        });
        return;
      }

      if ("activate" === pluginState) {
        setPluginState("deactivate");
      } else {
        setPluginState("activate");
      }

      setInProgress(false);
    });
  };

  return (
    <Card.Root size="sm" overflow="hidden" variant="outline">
      <Image
        hidden={!imageLoaded}
        src={banner}
        alt={__("Banner Image", "themeisle-companion")}
        onLoad={() => setImageLoaded(true)}
      />

      {!imageLoaded && <Skeleton w="100%" aspectRatio="3.088/1" />}

      {premium && (
        <Badge
          colorPalette="purple"
          size="lg"
          rounded="none"
          px="4"
          py="2"
          roundedBottomLeft="lg"
          fontWeight="semibold"
          textTransform="uppercase"
          position="absolute"
          top="0"
          right="0"
          variant="subtle"
        >
          {__("Premium", "themeisle-companion")}
        </Badge>
      )}

      <Card.Body gap="2">
        <Card.Title fontSize="base" fontWeight="semibold" m="0">
          {name}
        </Card.Title>
        <Card.Description fontSize="sm" lineHeight="1.6" m="0" color="fg.muted">
          {description}
        </Card.Description>
      </Card.Body>

      <Card.Footer
        gap="2"
        borderTop="1px solid"
        borderColor="border"
        p="4"
        justifyContent="space-between"
      >
        <HStack fontSize="sm" color="fg.muted" fontWeight="medium">
          {version && <Span>v{version}</Span>}
          {version && author && <Span>•</Span>}
          {author && <Span>{author}</Span>}
        </HStack>
        {pluginState !== "external" && (
          <Button
            rounded="sm"
            variant={
              ["install", "activate"].includes(pluginState)
                ? "solid"
                : "outline"
            }
            loading={inProgress}
            loadingText={stringMap[pluginState].progress + "..."}
            onClick={handleActionButton}
            colorPalette="purple"
            size="sm"
          >
            {stringMap[pluginState].static}
          </Button>
        )}
        {pluginState === "external" && (
          <ExternalLink href={url} fontSize="sm" fontWeight="medium">
            {stringMap[pluginState]}
          </ExternalLink>
        )}
      </Card.Footer>
    </Card.Root>
  );
};

const installPlugin = (slug) => {
  return new Promise((resolve) => {
    wp.updates.ajax("install-plugin", {
      slug,
      success: () => {
        resolve({ success: true });
      },
      error: (e) => {
        resolve({ success: false });
      },
    });
  });
};

export default PluginCard;
