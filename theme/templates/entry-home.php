<?php
/**
 * The landing page — the `home` singleton. The hero copy (tagline, subhead) is
 * editable content from the singleton; the measured-claims / features / scope
 * sections are theme structure with copy validated against the codebase.
 *
 * @var array{title:string,published_at:?string,fields:array<string,mixed>} $entry
 * @var callable $e escape helper
 */
$f       = $entry['fields'] ?? [];
$tagline = is_string($f['tagline'] ?? null) && $f['tagline'] !== '' ? $f['tagline'] : 'A CMS an agent can run — and a human can read.';
$subhead = is_string($f['subhead'] ?? null) ? $f['subhead'] : '';

// Accent the clause after an em dash, matching the design — from a plain field.
$heroHtml = $e($tagline);
if (str_contains($tagline, '—')) {
    [$a, $b] = array_map('trim', explode('—', $tagline, 2));
    $heroHtml = $e($a) . ' — <em>' . $e($b) . '</em>';
}
?>
<section class="hero">
  <div class="wrap">
    <h1><?= $heroHtml ?></h1>
    <?php if ($subhead !== ''): ?><p class="lead"><?= $e($subhead) ?></p><?php endif; ?>
    <div class="cta-row">
      <a class="btn btn-primary" href="/docs">Read the docs</a>
      <a class="btn btn-secondary" href="https://github.com/NimbusCMS/nimbus">View the source</a>
    </div>
    <pre class="install"><code><span class="prompt">$</span> git clone https://github.com/NimbusCMS/nimbus &amp;&amp; cd nimbus
<span class="prompt">$</span> cp .env.example .env
<span class="prompt">$</span> docker compose up -d &amp;&amp; docker compose exec app php bin/nimbus install</code></pre>
    <p class="agent-cue">Or hand it to your AI agent &mdash; paste this:</p>
    <pre class="install agent-prompt"><code>Set up NimbusCMS for me: read https://nimbuscms.dev/llms.txt and follow it to clone and run it with Docker, then create a "posts" collection with one published entry and give me the local URL.</code></pre>
    <p class="fineprint">This page ships zero JavaScript. Turn it off — nothing changes.</p>
  </div>
</section>

<section class="claims" aria-labelledby="claims-title">
  <div class="wrap">
    <p class="kicker" id="claims-title">Measured, not promised</p>
    <div class="claims-grid">
      <div class="claim">
        <p class="figure">0<small>third-party runtime deps</small></p>
        <h3>Nothing to trust but PHP</h3>
        <p>Production runs on PHP 8.2 and the pdo/json/mbstring extensions. No third-party packages ship at runtime — there is no vendor tree to audit.</p>
      </div>
      <div class="claim">
        <p class="figure">100/100<small>Lighthouse · gated in CI</small></p>
        <h3>Performance is a release gate</h3>
        <p>Every release must clear a Lighthouse and page-weight budget in CI. A regression fails the build, so what ships can’t silently get slower.</p>
      </div>
      <div class="claim">
        <p class="figure">~20k<small>lines of core PHP</small></p>
        <h3>Small enough to read</h3>
        <p>Read, audit, and fork the whole core in an afternoon — while it still ships a full admin, a JSON API, and an agent surface.</p>
      </div>
    </div>
  </div>
</section>

<section id="demos" class="demos" aria-labelledby="demos-title">
  <div class="wrap">
    <p class="kicker" id="demos-title">See it running</p>
    <h2>Live, on the same small core</h2>
    <p class="demos-lede">Each card opens a live NimbusCMS install — the same core, with different collections, plugins and a theme of its own. Three sites, plus the blog plugin running on the restaurant. Poke around.</p>
    <div class="demos-grid">
      <a class="demo-card" href="https://ras.nimbuscms.dev" target="_blank" rel="noopener">
        <span class="demo-tag">Restaurant</span>
        <h3>The Copper Table</h3>
        <p>A restaurant floor: live tables, kitchen tickets, orders and reservations, a public menu and online ordering. Sign in as any role to watch the capability model in action.</p>
        <span class="demo-go">Open demo &rarr;</span>
      </a>
      <a class="demo-card" href="https://foodmart.nimbuscms.dev" target="_blank" rel="noopener">
        <span class="demo-tag">Grocery</span>
        <h3>Foodmart</h3>
        <p>An online grocery: inventory as the source of truth, a shoppable storefront with search, filters and availability, and a working cart through to checkout.</p>
        <span class="demo-go">Open demo &rarr;</span>
      </a>
      <a class="demo-card" href="https://demo.nimbuscms.dev" target="_blank" rel="noopener">
        <span class="demo-tag">Coffee shop</span>
        <h3>Fern &amp; Kettle</h3>
        <p>A neighbourhood caf&eacute; site: menu, opening hours and story, rendered by a plain-PHP theme over the very same core.</p>
        <span class="demo-go">Open demo &rarr;</span>
      </a>
      <a class="demo-card" href="https://ras.nimbuscms.dev/blog" target="_blank" rel="noopener">
        <span class="demo-tag">Blog plugin</span>
        <h3>The Copper Table Journal</h3>
        <p>The official Blog plugin on the restaurant demo: per-post SEO, an RSS feed and tag archives, and cross-posting to Dev.to and Hashnode driven over MCP.</p>
        <span class="demo-go">Open demo &rarr;</span>
      </a>
    </div>
  </div>
</section>

<section class="features-section" aria-labelledby="features-title">
  <div class="wrap">
    <p class="kicker">Built in</p>
    <h2 id="features-title">Everything a content site needs. Nothing it doesn’t.</h2>
    <div class="features">
      <div class="feature">
        <h3>Collections &amp; fields</h3>
        <p>Define structured content — posts, menus, products — with plain field types and relations. Your schema, not ours.</p>
      </div>
      <div class="feature">
        <h3>Themes in plain PHP</h3>
        <p>Server-rendered templates handed a data-only view model. No build step, no client runtime.</p>
      </div>
      <div class="feature">
        <h3>Headless API <code>/api/v1</code></h3>
        <p>Every collection is a scoped JSON endpoint with read/write tokens and optimistic-concurrency writes.</p>
      </div>
      <div class="feature">
        <h3>One rule-set, agents included</h3>
        <p>The admin, the API, and the agent (MCP) surface are three transports over one audited service layer — never a second set of rules.</p>
      </div>
      <div class="feature">
        <h3>Per-plugin agent guides</h3>
        <p>The CMS and each installed plugin ship their own guide, so a connecting agent knows how to drive every capability it touches.</p>
      </div>
      <div class="feature">
        <h3>Roles &amp; scoped tokens</h3>
        <p>Per-collection permissions for people and tokens alike — subset-only, deny-by-default. Give an agent exactly the verbs it needs.</p>
      </div>
    </div>
  </div>
</section>

<section class="scope">
  <div class="wrap">
    <aside class="scope-box">
      <p class="kicker">Honest scope</p>
      <h2>What Nimbus is not</h2>
      <p>Nimbus is pre-1.0 and self-hosted. It is not a page builder, an app framework, or a marketplace of a thousand plugins. It stores structured content, renders it fast, and serves it over a clean API. If you need more than that, you probably want something bigger — and that’s fine.</p>
    </aside>
  </div>
</section>
