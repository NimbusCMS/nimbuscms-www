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

Give an agent the narrowest token for the task, and it can build you a whole site.
