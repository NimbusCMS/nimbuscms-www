---
title: Deployment
slug: deployment
section: Getting Started
order: 4
summary: Ship it as one small PHP container behind a CDN.
---

# Deployment

Nimbus deploys as a single, small PHP container. It server-renders HTML and needs no Node, no build step, and no client runtime.

- Put a CDN in front for TLS and caching. Set your trusted-proxy configuration so the real client IP reaches the rate limiter and audit log.
- Turn on the filesystem page cache (`PAGE_CACHE_TTL`) for anonymous traffic. It is flushed automatically on every content write.
- This very site runs exactly this way.
