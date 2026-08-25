---
title: First content
slug: first-content
section: Getting Started
order: 2
summary: Collections, fields, and entries.
---

# First content

Content in Nimbus is organised into **collections** (content types), each with a set of **fields**, holding **entries**.

1. Create a collection — a `handle` (its URL-safe id) and a name.
2. Add fields — a `type`, a label, and whether it is required.
3. Create entries and publish them.

You can do all of this three ways, over one backend:

- in the **admin**,
- over the **JSON API**,
- or by asking an **agent** over MCP.

A collection can be a **singleton** — exactly one entry, for a homepage or an about page — or hold many entries. Point `site.home` at a singleton to make it the front page.
