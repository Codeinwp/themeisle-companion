
import { post } from "./rest";

import { __ } from "@wordpress/i18n";

export const tabs = {
  modules: __("Available Modules", "themeisle-companion"),
  plugins: __("Recommended Plugins", "themeisle-companion"),
};

export const getTabHash = () => {
  let hash = window.location.hash;

  if ("string" !== typeof window.location.hash) {
    return null;
  }

  hash = hash.substring(1);

  if (!Object.keys(tabs).includes(hash)) {
    return null;
  }

  return hash;
};

export const unregister = (url) => {
  post(url, "deactivate=unregister").then((r) => {
    if (r === false) {
      return;
    }
    window.location.reload();
  });
};

/**
 * Decodes a html encoded string while preserving tags
 *
 * @param {string} html encoded string
 * @return {string} decoded string
 */
export const decodeHtml = (html) => {
  const txt = document.createElement("textarea");
  txt.innerHTML = html;
  return txt.value;
};
