/* global obfxDash */
import {
  Box,
  Container,
  Flex,
  Image,
  Text,
  Tabs,
  Badge,
  VStack,
} from "@chakra-ui/react";
import { tabs } from "../utils/common";
import { useEffect, useState } from "@wordpress/element";

const Title = () => {
  return null;
};

const Navigation = ({ activeTab, setActiveTab }) => {
  const onTabChange = ({ value }) => {
    window.location.hash = value;
    setActiveTab(value);
  };

  return (
    <Tabs.Root
      size="lg"
      onValueChange={onTabChange}
      value={activeTab}
      colorPalette="purple"
    >
      <Tabs.List borderBottom="none">
        {Object.entries(tabs).map(([tab, label]) => {
          const isActive = activeTab === tab;
          return (
            <Tabs.Trigger
              key={tab}
              value={tab}
              fontSize="sm"
              fontWeight="semibold"
              color={isActive ? "purple.600" : "fg.muted"}
              _hover={{ color: "purple.700" }}
            >
              {label}
            </Tabs.Trigger>
          );
        })}
      </Tabs.List>
      <Tabs.Content />
    </Tabs.Root>
  );
};

const Header = ({ activeTab, setActiveTab }) => {
  const [neveNotice, setNeveNotice] = useState(null);

  const repositionNeveNotice = () => {
    const neveNotice = document.querySelector(".neve-notice-upsell");
    if (!neveNotice) {
      return;
    }

    const html = neveNotice.outerHTML;
    setNeveNotice(html);
    neveNotice.remove();
  };

  useEffect(() => {
    repositionNeveNotice();
  }, []);

  return (
    <Flex direction="column" gap="8">
      <Box as="header" bg="bg">
        <Box borderBottom="1px solid" borderColor="border">
          <Container maxW="container">
            <Flex alignItems="center" justifyContent="space-between">
              <Flex alignItems="center" gap="5" py="3">
                <Image
                  src={obfxDash.path + "assets/orbit-fox.svg"}
                  alt="logo"
                  w="14"
                />
                <Text as="h1" fontSize="xl" fontWeight="semibold">
                  Orbit Fox
                </Text>
              </Flex>

              <Badge variant="surface" size="sm" color="fg.muted">
                v{obfxDash.version}
              </Badge>
            </Flex>
          </Container>
        </Box>

        <Box borderBottom="1px solid" borderColor="border">
          <Container>
            <Navigation activeTab={activeTab} setActiveTab={setActiveTab} />
          </Container>
        </Box>
      </Box>

      {window.tsdk_reposition_notice && (
        <Container id="tsdk_banner" className="obfx-banner" />
      )}

      {!window.tsdk_reposition_notice && neveNotice && (
        <Container
          className="obfx-banner"
          id="neve-notice-upsell"
          dangerouslySetInnerHTML={{ __html: neveNotice }}
        />
      )}
    </Flex>
  );
};

export default Header;
