---
title: Security model
slug: security
section: Security
summary: The controls, and the review discipline behind them.
---

# Security model

Security is described here as **controls and discipline**, never as a guarantee.

- **One capability model** for people, API tokens, and agents: deny-by-default, subset-only granting, non-enumerating errors.
- **Env-authoritative URLs** — generated links come from `APP_URL`, never a spoofable request header.
- **Optimistic-concurrency writes** so concurrent clients cannot silently clobber each other.
- **Escape-by-default** output and parameter-bound queries.
- **A visible review discipline** — changes are reviewed through an adversarial security pass before they merge, and the ledger of decisions lives in the repository.

To report a vulnerability, see `SECURITY.md` in the source repository.
