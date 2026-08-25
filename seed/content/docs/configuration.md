---
title: Configuration
slug: configuration
section: Getting Started
order: 3
summary: The .env file and the config/ directory.
---

# Configuration

Runtime configuration lives in `.env`; site wiring lives in small PHP files under `config/`.

## .env

- `APP_URL` — the site's absolute base URL. Generated links are built from this, never from a request header, so they cannot be poisoned.
- `DB_*` — database connection.
- `PAGE_CACHE_TTL` — the optional filesystem page cache, off by default.
- Mail and OAuth settings, when you use them.

## config/

- `config/theme.php` — the active theme.
- `config/plugins.php` — which plugins are enabled.
- `config/menus.php` — named menus a theme renders.
- `config/site.php` — site-wide defaults.

Each is a plain PHP file returning an array — no bespoke format to learn.
