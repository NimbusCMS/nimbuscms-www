---
title: Installation
slug: installation
section: Getting Started
order: 1
summary: Get NimbusCMS running with Docker or plain PHP.
---

# Installation

NimbusCMS runs on **PHP 8.2+** with the `pdo`, `json`, and `mbstring` extensions — and nothing else. There are no third-party packages to install at runtime.

## With Docker

```
git clone https://github.com/NimbusCMS/nimbus
cd nimbus
docker compose up
```

This starts PHP and MySQL, runs the migrations, and serves the site. Open the admin and create your first administrator when prompted.

## On your own PHP host

- Point a web server at the project's public entry point.
- Copy `.env.example` to `.env` and set your database credentials and `APP_URL`.
- Run the migrations with `nimbus migrate`.

That is the whole install: a database, a `.env`, and the migrations.
