import { Link } from "@chakra-ui/react";
import { ExternalLinkIcon } from "lucide-react";

const ExternalLink = ({ href, children, ...props }) => {
  return (
    <Link
      color="purple.500"
      _hover={{ color: "purple.700" }}
      href={href}
      target="_blank"
      rel="noopener noreferrer"
      {...props}
    >
      {children}
      <ExternalLinkIcon size={14} />
    </Link>
  );
};

export default ExternalLink;
