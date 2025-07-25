import { Badge, Icon } from "@chakra-ui/react";
import { MoveUpRight } from "lucide-react";

const ObfxBadge = ({ href, text, icon = MoveUpRight, blank = true, ...props }) => {
  const BadgeIcon = icon;
  return (
    <Badge
      variant="plain"
      colorPalette="purple"
      color="purple.500"
      border="1px solid"
      borderColor="purple.200"
      _hover={{
        bg: "purple.500",
        color: "purple.50",
        borderColor: "purple.500",
      }}
      _focus={{
        outline: "2px solid",
        outlineColor: "purple.500",
        outlineOffset: "2px",
        shadow: "none",
        bg: "purple.500",
        color: "purple.50",
        borderColor: "purple.500",
      }}
      transition="all 0.2s ease-in-out"
      textTransform="uppercase"
      href={href}
      as="a"
      target={blank ? "_blank" : undefined}
      size="sm"
      fontSize={"xs"}
      {...props}
    >
      {text}

      {!!icon && (
        <Icon size={"xs"}>
          <BadgeIcon />
        </Icon>
      )}
    </Badge>
  );
};

export default ObfxBadge;
