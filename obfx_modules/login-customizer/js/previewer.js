class OBFXLoginCustomizerPreview {
  constructor() {
    document.addEventListener("DOMContentLoaded", () => {
      this.init();
      this.listen();
    });
  }

  init() {
    this.css = document.createElement("style");
    this.css.id = "obfx-login-customizer-css";
    document.head.appendChild(this.css);
  }

  update(values) {
    this.css.innerHTML = this.generateCSS(values);
    this.updateMarkup(values);
  }

  generateCSS(values) {
    const {
      disable_logo,
      custom_logo_url,
      logo_width,
      logo_height,
      logo_bottom_margin,
      page_bg_color,
      page_bg_image,
      page_bg_image_position,
      page_bg_image_size,
      page_bg_image_repeat,
      page_bg_overlay_blur,
     
      form_bg_color,
      form_border_radius,
      form_text_color,
      form_border,
      form_width,
      form_padding,

      form_field_bg_color,
      form_field_text_color,
      form_field_border,
      form_field_border_radius,
      form_field_margin_bottom,
      form_field_padding,
      form_field_font_size,

      form_label_font_size,
      form_label_margin_bottom,
      
      button_display_below,
      button_alignment,
      button_width,
      button_margin_top,
      button_padding,
      button_font_size,

      button_border,
      button_border_radius,
      button_background,
      button_text_color,
      button_hover_background,
      button_hover_text_color,
      button_hover_border_color,

      show_navigation_links,
      nav_font_size,
      nav_color,
      nav_hover_color,

      show_link_to_homepage,
      homepage_link_font_size,
      homepage_link_color,
      homepage_link_hover_color,

      show_privacy_policy,
      privacy_policy_link_font_size,
      privacy_policy_link_color,
      privacy_policy_link_hover_color,
           
      show_remember_me,
      show_language_switcher,
    } = values;

    let css = `
html body.login {
  --obfx-login-bg-color: ${page_bg_color};
  ${page_bg_image ? `--obfx-login-bg-image: url(${page_bg_image});` : ""}
  --obfx-login-bg-position: ${page_bg_image_position};
  --obfx-login-bg-size: ${page_bg_image_size};
  --obfx-login-bg-repeat: ${page_bg_image_repeat};
  --obfx-login-bg-overlay-blur: ${page_bg_overlay_blur}px;
  --obfx-login-logo-height: ${logo_height}px;
  --obfx-login-logo-width: ${logo_width}px;
  --obfx-login-logo-margin-bottom: ${logo_bottom_margin}px;
  
  --obfx-login-form-bg-color: ${form_bg_color};
  --obfx-login-form-border-radius: ${form_border_radius}px;
  --obfx-login-form-text-color: ${form_text_color};
  --obfx-login-form-border: ${form_border};
  --obfx-login-form-width: ${form_width}px;
  --obfx-login-form-padding: ${form_padding};
  
  --obfx-login-form-field-bg-color: ${form_field_bg_color};
  --obfx-login-form-field-text-color: ${form_field_text_color};
  --obfx-login-form-field-border: ${form_field_border};
  --obfx-login-form-field-border-radius: ${form_field_border_radius}px;
  --obfx-login-form-label-font-size: ${form_label_font_size}px;
  --obfx-login-form-label-margin-bottom: ${form_label_margin_bottom}px;
  --obfx-login-form-field-margin-bottom: ${form_field_margin_bottom}px;
  --obfx-login-form-field-padding: ${form_field_padding};
  --obfx-login-form-field-font-size: ${form_field_font_size}px;
  
  --obfx-login-button-padding: ${button_padding};
  --obfx-login-button-font-size: ${button_font_size}px;
  --obfx-login-button-border-radius: ${button_border_radius}px;
  --obfx-login-button-border: ${button_border};
  --obfx-login-button-background: ${button_background};
  --obfx-login-button-text-color: ${button_text_color};
  --obfx-login-button-hover-background: ${button_hover_background};
  --obfx-login-button-hover-text-color: ${button_hover_text_color};
  --obfx-login-button-hover-border-color: ${button_hover_border_color};
  
  --obfx-login-nav-font-size: ${nav_font_size}px;
  --obfx-login-nav-color: ${nav_color};
  --obfx-login-nav-hover-color: ${nav_hover_color};
  
  --obfx-login-homepage-link-font-size: ${homepage_link_font_size}px;
  --obfx-login-homepage-link-color: ${homepage_link_color};
  --obfx-login-homepage-link-hover-color: ${homepage_link_hover_color};

  --obfx-login-privacy-policy-link-font-size: ${privacy_policy_link_font_size}px;
  --obfx-login-privacy-policy-link-color: ${privacy_policy_link_color};
  --obfx-login-privacy-policy-link-hover-color: ${privacy_policy_link_hover_color};
  
`
    if( button_display_below === true ) {
      css += `
  --obfx-login-button-width: ${button_width}%;
  --obfx-login-button-alignment: ${button_alignment};
  --obfx-login-button-margin-top: ${button_margin_top}px;
  `;
    }

    css += ` }    

html .login h1 a, html .login .wp-login-logo a {
  ${disable_logo ? "display: none;" : "display: block;"}
  ${!!custom_logo_url ? `background-image: url(${custom_logo_url});` : ""}
}`;

    css += `
html .forgetmenot { ${show_remember_me === false ? "display: none;" : "display: flex;"} }
html .language-switcher { ${show_language_switcher === false ? "display: none;" : "display: block;"} }
html .login #nav { ${show_navigation_links === false ? "display: none;" : "display: block;"} }
html #backtoblog { ${show_link_to_homepage === false ? "display: none;" : "display: block;"} }
html .privacy-policy-link { ${show_privacy_policy === false ? "display: none;" : "display: block;"} }`;

    

    if( button_display_below === true ) {
      css += `.login form .forgetmenot, p.submit { float: none; }`;
    }
      
    return css;
  }

  updateMarkup(values) {
    const {
      logo_url,
      logo_title,
    } = values;


    const logo = document.querySelector(".login .wp-login-logo a");

    if( logo ) {
      logo.href = logo_url;
      logo.innerHTML = logo_title;
    }
  }

  listen() {
    const self = this;

    wp.customize("obfx_login_customizer_options", function (value) {
      value.bind(function (to) {
        self.update(to);
      });
    });
  }
}

new OBFXLoginCustomizerPreview();