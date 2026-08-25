<?php

declare(strict_types=1);

/**
 * Idempotent content seed for a NimbusCMS site, applied entirely over the MCP
 * control surface (ADR 0009 / 0013) — the same surface an agent uses. It dogfoods
 * Nimbus: the site's content is created by driving the CMS's own tools, not by
 * touching its database.
 *
 * Usage:
 *   NIMBUS_MCP_TOKEN=nbt_... php seed/seed.php [seed/site.json]
 *   NIMBUS_MCP_URL=https://nimbuscms.dev/api/v1/mcp NIMBUS_MCP_TOKEN=... php seed/seed.php
 *
 * The token needs schema:write, settings:write and content read+write for the
 * seeded collections (e.g. *:read,*:write). Mint one with `nimbus token:create`
 * and revoke it after seeding.
 *
 * Idempotent: collections are created only if absent; entries are matched by slug
 * (updated if present, created if not); settings are upserts. Re-running makes no
 * duplicates. Zero dependencies — just curl.
 */

$url   = getenv('NIMBUS_MCP_URL') ?: 'http://localhost:8080/api/v1/mcp';
$token = getenv('NIMBUS_MCP_TOKEN') ?: '';
$file  = $argv[1] ?? __DIR__ . '/site.json';

if ($token === '') {
    fwrite(STDERR, "NIMBUS_MCP_TOKEN is required.\n");
    exit(1);
}
$seed = json_decode((string) file_get_contents($file), true);
if (!is_array($seed)) {
    fwrite(STDERR, "Could not read seed file: {$file}\n");
    exit(1);
}

$rpcId = 0;

/**
 * One JSON-RPC call. Returns the decoded `result`. Exits on a transport or
 * protocol error (a tool *outcome* — including isError — is returned as-is for
 * the caller to interpret).
 *
 * @param array<string,mixed> $params
 * @return array<string,mixed>
 */
function rpc(string $method, array $params): array
{
    global $url, $token, $rpcId;
    $body = json_encode(['jsonrpc' => '2.0', 'id' => ++$rpcId, 'method' => $method, 'params' => $params], JSON_THROW_ON_ERROR);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $body,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . $token],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $raw  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($raw === false) {
        fwrite(STDERR, 'HTTP error: ' . curl_error($ch) . "\n");
        exit(1);
    }
    curl_close($ch);

    $decoded = json_decode((string) $raw, true);
    if (!is_array($decoded) || isset($decoded['error'])) {
        $msg = is_array($decoded) && isset($decoded['error']['message']) ? $decoded['error']['message'] : "HTTP {$code}: {$raw}";
        fwrite(STDERR, "RPC {$method} failed: {$msg}\n");
        exit(1);
    }
    return is_array($decoded['result'] ?? null) ? $decoded['result'] : [];
}

/**
 * A tools/call, returning [structuredContent, isError].
 *
 * @param array<string,mixed> $args
 * @return array{0: array<string,mixed>, 1: bool}
 */
function call(string $name, array $args): array
{
    $result = rpc('tools/call', ['name' => $name, 'arguments' => $args]);
    return [is_array($result['structuredContent'] ?? null) ? $result['structuredContent'] : [], (bool) ($result['isError'] ?? false)];
}

function say(string $line): void
{
    fwrite(STDOUT, $line . "\n");
}

/**
 * Parse a Markdown file with `--- key: value ---` frontmatter into an entry:
 * `{slug, title, status, fields:{section, order, summary, body}}`. Frontmatter
 * keys title/slug/status are entry-level; the rest become fields; the body after
 * the frontmatter is `fields.body`.
 *
 * @return array<string,mixed>
 */
function entryFromMarkdown(string $file): array
{
    $raw = (string) file_get_contents($file);
    $fm  = [];
    $body = $raw;
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n?(.*)$/s', $raw, $m)) {
        foreach (explode("\n", $m[1]) as $line) {
            if (preg_match('/^([A-Za-z0-9_]+):\s*(.*)$/', trim($line), $kv)) {
                $fm[$kv[1]] = trim($kv[2], " \"'");
            }
        }
        $body = $m[2];
    }
    $fields = ['body' => rtrim($body) . "\n"];
    foreach (['section', 'summary'] as $k) {
        if (isset($fm[$k])) {
            $fields[$k] = $fm[$k];
        }
    }
    if (isset($fm['order'])) {
        $fields['order'] = (int) $fm['order'];
    }
    return [
        'slug'   => $fm['slug'] ?? basename($file, '.md'),
        'title'  => $fm['title'] ?? basename($file, '.md'),
        'status' => $fm['status'] ?? 'published',
        'fields' => $fields,
    ];
}

/**
 * All entries authored as Markdown files under a directory, ordered by their
 * `order` field then filename.
 *
 * @return list<array<string,mixed>>
 */
function entriesFromDir(string $dir): array
{
    $files = glob(rtrim($dir, '/') . '/*.md') ?: [];
    sort($files);
    return array_map('entryFromMarkdown', $files);
}

// --- handshake (also surfaces a bad token early) -----------------------------
rpc('initialize', []);
say('Connected to ' . $url);

// --- 1. collections (create if absent) ---------------------------------------
[$listed] = call('list_collections', []);
$existing = array_column($listed['collections'] ?? [], 'handle');

foreach ($seed['collections'] ?? [] as $collection) {
    $handle = $collection['handle'];
    if (in_array($handle, $existing, true)) {
        say("collection {$handle}: exists");
        continue;
    }
    [$res, $err] = call('create_collection', [
        'handle'      => $handle,
        'name'        => $collection['name'],
        'icon'        => $collection['icon'] ?? '',
        'description' => $collection['description'] ?? '',
        'kind'        => $collection['kind'] ?? 'collection',
        'fields'      => $collection['fields'] ?? [],
    ]);
    if ($err) {
        fwrite(STDERR, "collection {$handle}: FAILED — " . ($res['error']['message'] ?? 'unknown') . "\n");
        exit(1);
    }
    say("collection {$handle}: created");
}

// --- 2. entries (match by slug: update if present, else create) --------------
// Field values live under `fields`; title/slug/status/published_at are top-level
// (the create_/update_ tool contract). `version` comes back as a sibling of
// `data` on a read, and update_ needs it (optimistic concurrency). Entries come
// from `entries` (inline, structured) and `content` (dirs of Markdown files).
$upsert = static function (string $handle, array $entry): void {
    $slug   = (string) ($entry['slug'] ?? '');
    $fields = is_array($entry['fields'] ?? null) ? $entry['fields'] : [];
    $status = $entry['status'] ?? 'published';

    [$found, $missing] = call("get_{$handle}", ['slug' => $slug]);
    if (!$missing && isset($found['version'])) {
        [$res, $err] = call("update_{$handle}", ['slug' => $slug, 'version' => $found['version'], 'status' => $status, 'fields' => $fields]);
        $verb = 'updated';
    } else {
        $args = ['title' => $entry['title'] ?? $slug, 'slug' => $slug, 'status' => $status, 'fields' => $fields];
        if (isset($entry['published_at'])) {
            $args['published_at'] = $entry['published_at'];
        }
        [$res, $err] = call("create_{$handle}", $args);
        $verb = 'created';
    }
    if ($err) {
        fwrite(STDERR, "entry {$handle}/{$slug}: FAILED — " . ($res['error']['message'] ?? 'unknown') . "\n");
        exit(1);
    }
    say("entry {$handle}/{$slug}: {$verb}");
};

foreach ($seed['entries'] ?? [] as $handle => $entries) {
    foreach ($entries as $entry) {
        $upsert($handle, $entry);
    }
}
foreach ($seed['content'] ?? [] as $handle => $dir) {
    foreach (entriesFromDir(dirname($file) . '/' . $dir) as $entry) {
        $upsert($handle, $entry);
    }
}

// --- 3. settings, then site.home last (needs the home collection to exist) ---
if (!empty($seed['settings'])) {
    [$res, $err] = call('set_settings', ['settings' => $seed['settings']]);
    if ($err) {
        fwrite(STDERR, 'settings: FAILED — ' . ($res['error']['message'] ?? 'unknown') . "\n");
        exit(1);
    }
    say('settings: ' . implode(', ', array_keys($seed['settings'])));
}
if (!empty($seed['home_setting'])) {
    [$res, $err] = call('set_settings', ['settings' => $seed['home_setting']]);
    if ($err) {
        fwrite(STDERR, 'site.home: FAILED — ' . ($res['error']['message'] ?? 'unknown') . "\n");
        exit(1);
    }
    say('site.home: ' . implode(', ', array_values($seed['home_setting'])));
}

say('Seed complete.');
