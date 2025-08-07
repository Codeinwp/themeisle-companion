const js = require("@eslint/js");
const react = require("eslint-plugin-react");
const reactHooks = require("eslint-plugin-react-hooks");
const importPlugin = require("eslint-plugin-import");

module.exports = [
  {
    files: ["dashboard/**/*.{js,jsx}", "obfx_modules/**/*.{js,jsx}"],
    languageOptions: {
      ecmaVersion: 2020,
      sourceType: "module",
      parserOptions: {
        ecmaFeatures: {
          jsx: true,
        },
      },
    },
    ...js.configs.recommended,
    plugins: {
      react,
      "react-hooks": reactHooks,
      import: importPlugin,
    },
    settings: {
      react: {
        version: "detect",
      },
      "import/resolver": {
        webpack: {
          config: "wp-scripts.config.js",
        },
        typescript: {
          alwaysTryTypes: true,
        },
        node: {
          extensions: [".js", ".jsx"],
        },
      },
    },
    rules: {
      ...react.configs.recommended.rules,
      "react/react-in-jsx-scope": "off",
      "react/prop-types": "off",
      indent: ["error", 2],
    },
  },
  {
    ignores: [
      "node_modules/",
      "build/",
      "*.json",
      "wp-scripts.config.js",
      "webpack.config.js",
      "*.config.js",
      "vendor/",
    ],
  },
];
