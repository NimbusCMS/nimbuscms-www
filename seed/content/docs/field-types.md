---
title: Field types
slug: field-types
section: Reference
order: 1
summary: The built-in field types, and how plugins add more.
---

# Field types

Fields declare a `type`. The built-ins cover text, longer text, numbers, dates, media, and relations to other collections. Unknown types are rejected rather than silently treated as text.

A field type owns how its value is rendered, normalised, validated, and serialised to the API. Plugins add new types through the same contract — the official **Markdown** plugin is the reference example, adding a `markdown` field in a few dozen lines.
