---
title: The headless API
slug: the-headless-api
section: Guides
order: 1
summary: Read and write every collection over JSON.
---

# The headless API

Every collection is a JSON endpoint under `/api/v1`, gated by **scoped tokens**.

- Mint a token with `nimbus token:create --name="my app" --scopes=posts:read,posts:write`.
- Read a list or a single entry; write with `POST`/`PATCH`/`DELETE`.
- Writes use **optimistic concurrency**: send the entry's version with `If-Match`; a stale write is rejected with `412` so two clients never silently overwrite each other.
- A generated **OpenAPI** document describes exactly the collections your token can see.

The API calls the same services the admin does — it adds a transport, never a second set of rules.
