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
cp .env.example .env
docker compose up -d
docker compose exec app php bin/nimbus install
```

`docker compose up` starts PHP and MySQL; **`nimbus install` runs the migrations and creates the first administrator** — until you run it, the site returns 503. In local development it seeds `admin@nimbus.test` / `password` (change it); on a real host it refuses a default and asks for `--email` and a strong `--password`. The site is then live at `http://localhost:8080`, admin at `/admin`.

**A port already in use?** The stack publishes `8080` (app), `8081` (Adminer) and `3307` (MySQL). If any clash on your machine, set `APP_PORT`, `ADMINER_PORT` or `DB_HOST_PORT` in `.env` before `docker compose up` (and match `APP_URL` to `APP_PORT`).

## On your own PHP host

- Point a web server at the project's `public/` entry point.
- Copy `.env.example` to `.env` and set your database credentials and `APP_URL`.
- Run `nimbus install` (migrations + first admin), or `nimbus migrate` if you only need the schema.

That is the whole install: a database, a `.env`, and `nimbus install`.
