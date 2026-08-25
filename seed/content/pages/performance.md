---
title: Performance
slug: performance
section: Performance
summary: Fast by construction — and gated in CI.
---

# Performance

NimbusCMS is fast because of what it *doesn't* do: no client framework, no build step, no web fonts, no mandatory JavaScript. Pages are server-rendered HTML with a few kilobytes of hand-written CSS, and an optional filesystem page cache in front.

On the starter theme this measures **100/100** on Lighthouse (mobile). More important than the number is that it is a **CI gate**: every release must clear a performance and page-weight budget, or the build fails — so what ships can't quietly get slower.

The numbers depend on your theme's own images, fonts, and scripts. Nimbus gives you a fast floor; what you build on it is yours to keep fast. This site, for instance, ships zero JavaScript and a single stylesheet.
