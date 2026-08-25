# nimbuscms-www

The **[NimbusCMS](https://github.com/NimbusCMS/nimbus) marketing + docs website**
([nimbuscms.dev](https://nimbuscms.dev)) — and a piece of dogfooding: the site's
content is **seeded and served by NimbusCMS itself**, not a static generator.

- **Content** is declared in [`seed/site.json`](seed/site.json) and applied by
  [`seed/seed.php`](seed/seed.php) **entirely over the MCP control surface** — the
  same tools an agent uses (ADR 0009 / 0013). No database is touched directly.
- **Serving** is a live NimbusCMS install behind Cloudflare (Shape A of the
  deploy platform, [ADR 0010](https://github.com/NimbusCMS/nimbus/blob/main/docs/adr/0010-deployment.md)).

## Content model

| URL | Source | Kind |
|---|---|---|
| `/` | `home` collection | **singleton** (`site.home` → `home`) |
| `/docs`, `/docs/{slug}` | `docs` collection | docs, grouped by `section`, ordered |
| `/pages/{slug}` | `pages` collection | prose / legal |

Nimbus public routing is two-segment (`/`, `/{collection}`, `/{collection}/{slug}`),
so versioned docs (`/docs/{version}/…`) aren't expressible yet — at launch there is
one version (v0.1) served at `/docs`. Versioning is revisited when a second version
exists.

## Seeding

The seed is **idempotent** (collections created if absent; entries matched by slug
— updated if present, created if not; settings upserted). Re-running makes no
duplicates.

```bash
# Mint a scoped token on the target install, then:
NIMBUS_MCP_TOKEN=nbt_... NIMBUS_MCP_URL=https://nimbuscms.dev/api/v1/mcp \
  php seed/seed.php seed/site.json
```

The token needs `schema:write`, `settings:write`, and content `*:read,*:write`
(`nimbus token:create --scopes=...`). Revoke it after seeding. Locally, point
`NIMBUS_MCP_URL` at your dev server's `/api/v1/mcp`.

## Status

- **Slice 1 (this):** repo + declarative seed + idempotent MCP-seed runner,
  verified against dev — every page renders with seeded content.
- **Slice 2 (next):** the bespoke theme (plain PHP + tiny CSS, mobile-first),
  which is also when `docs`/`pages` body fields switch to the Markdown plugin's
  `markdown` type.
- **Slice 3:** real marketing copy + the human-docs suite.

## Dogfood findings (surfaced building this, filed against core)

- **create_collection singleton parity** — MCP couldn't create a singleton; fixed
  in core ([nimbus #173](https://github.com/NimbusCMS/nimbus/pull/173)).
- **create_{handle} ignored an explicit `slug`** — the tool advertised a `slug`
  input but always derived the slug from the title. Fixed in core
  ([nimbus #174](https://github.com/NimbusCMS/nimbus/pull/174)): an explicit slug
  is now honored (normalized + made unique), so seed slugs can be set
  independently of titles.
