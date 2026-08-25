<?php
/**
 * The docs landing (/docs) — the same sidebar, with a short intro.
 *
 * @var array{handle:string,name:string} $collection
 * @var list<array<string,mixed>> $nav
 * @var callable $partial
 * @var callable $e
 */
?>
<div class="docs">
  <div class="wrap docs-grid">
    <?= $partial('docnav', ['nav' => $nav ?? [], 'current' => '']) ?>
    <article class="prose">
      <p class="kicker">Docs <span class="version-badge">v0.1</span></p>
      <h1>Documentation</h1>
      <div class="body">
        <p>Everything you need to install NimbusCMS, model your content, and drive it — from the admin, the API, or an agent. Pick a topic from the list, or start with <a href="/docs/installation">Installation</a>.</p>
      </div>
    </article>
    <div class="docs-toc" aria-hidden="true"></div>
  </div>
</div>
