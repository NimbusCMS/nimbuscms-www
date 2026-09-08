<?php

declare(strict_types=1);

/**
 * Extra content appended to this site's /llms.txt (core reads it via
 * Config::llmsExtra(), ADR: site-supplied llms.txt guidance). This is the
 * marketing site, so the guidance an agent needs from this URL is how to install
 * and drive NimbusCMS itself. Keep the commands in sync with the repo README.
 */

return <<<'MD'
## Install and run NimbusCMS

NimbusCMS is open-source PHP with zero third-party runtime dependencies. The
fastest path is Docker (a MySQL 8 database is included):

```
git clone --depth 1 https://github.com/NimbusCMS/nimbus.git && cd nimbus
cp .env.example .env
docker compose up -d --build
docker compose exec app php bin/nimbus install
```

`install` runs the migrations and creates the first admin (it prints the
credentials). If the database refuses the connection on a cold first boot, wait a
few seconds and re-run `install` (the DB is still starting). The site is then live
at http://localhost:8080 (admin at /admin).

If a port is already in use, set `APP_PORT`, `ADMINER_PORT` or `DB_HOST_PORT` in
`.env` before `docker compose up` (and match `APP_URL` to `APP_PORT`) — the compose
file reads those. No `composer install` is needed in development: the runtime has
no third-party packages.

## Model content and drive it as an agent

Content lives in collections you define. You can model and fill them entirely
over the API/MCP with a scoped token (no admin UI required):

```
docker compose exec app php bin/nimbus token:create --name=agent --scopes='schema:write,*:read,*:write'
```

(Quote the scopes so your shell doesn't expand the `*`.)

Then connect to the MCP endpoint at `<your-site>/api/v1/mcp` (or stdio
`php bin/nimbus mcp`) and read the built-in operating guide, the MCP resource
`nimbus://guide/core`, which explains the whole workflow. In short:

- `create_collection` (with `schema:write`) defines a content type and its fields.
- Per-collection tools (`create_<handle>`, `list_<handle>`, `get_<handle>`, ...)
  appear automatically once the collection exists, generated from your fields.
- Published entries are served over the theme (`/<handle>`, `/<handle>/<slug>`)
  and the read API (`/api/v1/collections/<handle>/entries`, which also needs the token).

Every surface (admin, API, MCP) runs on the same audited, scope-checked services,
so an agent can operate the whole CMS with nothing installed but a token.

## Learn more

- Install and configuration: https://nimbuscms.dev/docs/installation
- Reading content over the API: https://nimbuscms.dev/docs/the-headless-api
- Driving it with an agent (MCP): https://nimbuscms.dev/docs/driving-with-an-agent
- Source: https://github.com/NimbusCMS/nimbus
MD;
