---
title: Driving Nimbus with an agent
slug: driving-with-an-agent
section: Guides
order: 2
summary: The MCP control surface, and the guide that ships with it.
---

# Driving Nimbus with an agent

Nimbus speaks the **Model Context Protocol**. Point any MCP client at it with a scoped token and it can define content types, write content, and manage the site — bounded by the token's scopes.

- Over HTTP at `POST /api/v1/mcp`, or locally over stdio with `nimbus mcp`.
- The server ships its **own operating guide**: `initialize` returns a brief, and `resources/read` serves `nimbus://guide/core`. Installed plugins ship their own guides too, so an agent knows how to drive each capability it touches.
- Every tool re-checks the token's scopes, and granting is subset-only — an agent can only ever do what its token already allows.

## Connecting

Mint a scoped token, then talk to the endpoint over JSON-RPC 2.0 with a bearer header (quote the scopes so your shell doesn't expand the `*`):

```
nimbus token:create --name=agent --scopes='schema:write,*:read,*:write'

curl -X POST https://your-site/api/v1/mcp \
  -H "Authorization: Bearer nbt_your_token" \
  -H "Content-Type: application/json" \
  -d '{"jsonrpc":"2.0","id":1,"method":"tools/list"}'
```

Call a tool the same way. For example, publish an entry once a `posts` collection exists:

```
{"jsonrpc":"2.0","id":2,"method":"tools/call","params":{
  "name":"create_posts",
  "arguments":{"title":"Hello","status":"published","fields":{"body":"..."}}
}}
```

`initialize` returns the operating brief, and `resources/read` with `nimbus://guide/core` returns the full guide — so a connecting agent learns the workflow from the server itself. Both HTTP and the stdio transport (`nimbus mcp`) speak the same protocol.

Give an agent the narrowest token for the task, and it can build you a whole site.
