---
title: Error codes
slug: error-codes
section: Reference
order: 4
summary: The machine-readable codes the API and MCP return.
---

# Error codes

A rejected call returns a machine code so a client can react without parsing prose:

- `unauthorized` — no or invalid token.
- `forbidden` — authenticated, but not allowed.
- `not_found` — no such entry or resource.
- `invalid` — validation failed; a per-field `{ code, message }` map says which.
- `precondition_required` — a version was needed and not supplied.
- `precondition_failed` — the version was stale; re-read and retry.
- `rate_limited` — slow down and retry later.

The same shape is returned over HTTP and over MCP.
