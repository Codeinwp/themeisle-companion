/**
 * Social Sharing Module Public Script
 *
 * @since	1.0.0
 * @package obfx_modules/social-sharing/js
 *
 * @author	ThemeIsle
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const sharingLinks = document.querySelectorAll(
      ".obfx-sharing a, .obfx-sharing-inline a"
    );

    sharingLinks.forEach(function (link) {
      // Skip whatsapp, mail, and viber links
      if (
        link.classList.contains("whatsapp") ||
        link.classList.contains("mail") ||
        link.classList.contains("sms")
      ) {
        return;
      }

      link.addEventListener("click", function (e) {
        e.preventDefault();
        const linkHref = this.getAttribute("href");
        const windowHeight = window.innerHeight;
        const windowWidth = window.innerWidth;

        window.open(
          linkHref,
          "obfxShareWindow",
          "height=450, width=550, top=" +
            (windowHeight / 2 - 275) +
            ", left=" +
            (windowWidth / 2 - 225) +
            ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0"
        );
        return true;
      });
    });
  });
})();
