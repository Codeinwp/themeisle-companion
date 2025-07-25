"use client";

import {
  ChakraProvider,
  createSystem,
  defaultConfig,
  defineConfig,
} from "@chakra-ui/react";

const mainBrandColor = "#6366f1";

const config = defineConfig({
  theme: {
    tokens: {
      colors: {
        purple : {
          50: { value: "#eef2ff" },
          100: { value: "#e0e7ff" },
          200: { value: "#c7d2fe" },
          300: { value: "#a5b4fc" },
          400: { value: "#818cf8" },
          500: { value: "#6366f1" },
          600: { value: mainBrandColor }, // #6366f1
          700: { value: "#4f46e5" },
          800: { value: "#4338ca" },
          900: { value: "#3730a3" },
          950: { value: "#1e1b4b" },
        },
      },
    },
  },
});


const system = createSystem(defaultConfig, config);

export function Provider(props) {
  return <ChakraProvider value={system} {...props} />;
}
