---
title: MCP tools
slug: mcp-tools
section: Reference
order: 3
summary: What an agent can call over MCP.
---

# MCP tools

Over MCP, an agent sees only the tools its token's scopes allow. The surface mirrors the admin:

- **Content** — `list_collections`, and per collection `list_/get_/create_/update_/delete_{handle}`.
- **Schema** — `create_collection`, `update_collection`, `add_field`, `remove_field`, `set_fields`, `delete_collection`.
- **Media** — `list_media`, `get_media`, `media_usage`, `upload_media`, `delete_media`.
- **Users & roles** — `list_users`, `list_roles`, `create_user`, `set_role`.
- **Tokens** — `list_tokens`, `mint_token`, `revoke_token`, `pause_token`, `resume_token`.
- **Settings** — `get_settings`, `set_settings`.

The server also serves a full operating guide as an MCP resource, `nimbus://guide/core`, so an agent learns the rules — read-before-write, subset-only granting, the validation shape — without trial and error.
