# Agents Workflow

## Project Overview

**Orbit Fox Companion** (`themeisle-companion`) is a modular WordPress plugin by ThemeIsle. It provides 18+ independent feature modules (SVG uploads, social sharing, menu icons, Elementor/Beaver Builder widgets, template directory, login customizer, etc.) that users can enable/disable individually from a React-based admin dashboard.

## Common Commands

### JavaScript
```bash
npm run build              # Production build (all JS bundles)
npm run build:dash         # Build dashboard only
npm run start              # Dev servers with HMR (all modules, parallel)
npm run start:dash         # Dashboard dev server (port 8887)
npm run lint               # ESLint check
npm run lint:fix           # ESLint auto-fix
npm run dist               # Create distribution ZIP
```

### PHP
```bash
composer run lint           # PHPCS check (WordPress-VIP-Go + WordPress-Core standards)
composer run format         # PHPCBF auto-fix
```

### Testing
```bash
phpunit                    # Run PHP tests (requires WordPress test suite)
bin/install-wp-tests.sh    # Install WP test suite (DB name, user, pass, host, WP version)
```

## Architecture

### Module System (Core Pattern)

The plugin's central design pattern is a module factory system:

1. **Bootstrap** (`themeisle-companion.php`) → registers autoloader, creates `Orbit_Fox` instance
2. **Autoloader** (`class-autoloader.php`) → maps `Module_Slug_OBFX_Module` classes to `obfx_modules/{module-slug}/init.php`
3. **Core orchestrator** (`core/includes/class-orbit-fox.php`) → loads all modules via `Orbit_Fox_Module_Factory`
4. **Module abstract** (`core/app/abstract/class-orbit-fox-module-abstract.php`) → base class all modules extend

Each module in `obfx_modules/{slug}/init.php` defines a class `{Module_Name}_OBFX_Module` extending `Orbit_Fox_Module_Abstract` with these required methods:
- `enable_module()` — return `true`/`false` to control visibility (e.g., check if a theme/plugin exists)
- `load()` — init-hook logic
- `hooks()` — register WP actions/filters via `$this->loader->add_action()` / `add_filter()`
- `options()` — return array of module settings (auto-generates admin UI)
- `admin_enqueue()` / `public_enqueue()` — return arrays of JS/CSS to enqueue

Module properties set in `__construct()`: `$name`, `$description`, `$auto` (autoload flag), `$active_default`, `$beta`, `$no_save`. Always call `parent::__construct()`.

### Key Directories

- `core/` — Plugin core: loader, admin, global settings, abstract classes, helpers, models, views
- `obfx_modules/` — All feature modules (each self-contained with init.php, css/, js/, views/)
- `dashboard/` — React admin dashboard (Chakra UI v3, built with @wordpress/scripts)
- `vendor/` — Composer deps: ThemeIsle SDK, SVG sanitizer, MailerLite SDK

### Frontend Bundles

Four separate webpack entry points, each with its own dev server port:
- **Dashboard** (`dashboard/src/dashboard.js`) → `dashboard/build/` (port 8887)
- **Template Directory** (`obfx_modules/template-directory/src/`) → port 8081
- **MyStock Import** (`obfx_modules/mystock-import/js/src/`) → port 8082
- **Login Customizer** (`obfx_modules/login-customizer/js/src/`) → port 8083

### Hook/Filter Extension Points

- `obfx_modules` filter — register third-party modules by appending slugs to the modules array
- `themeisle_sdk_products` filter — register with ThemeIsle SDK
- `obfx_post_duplicator_skip_meta_keys` filter — customize post duplication behavior

## Code Standards

- **PHP:** WordPress-VIP-Go + WordPress-Core + WordPress-Docs standards via PHPCS. Text domain: `themeisle-companion`. PHP compatibility target: 5.6+.
- **JS:** ESLint with WordPress plugin rules. React with hooks. 2-space indentation.
- **Commits:** Conventional Commits (`feat:`, `fix:`, `BREAKING CHANGE:`) for semantic-release automation on master.
