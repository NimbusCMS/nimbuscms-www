---
title: The CLI
slug: the-cli
section: Reference
order: 2
summary: The nimbus command.
---

# The CLI

`nimbus` is the command-line entry point.

- `nimbus migrate` — apply pending migrations.
- `nimbus token:create` / `token:list` / `token:revoke` — manage API tokens.
- `nimbus mcp` — serve MCP over stdio for a local agent (needs a scoped token).
- `nimbus prune` — run maintenance tasks (throttle cleanup, plugin retention).
- `nimbus mail:test` — send a test email to check your mailer.
