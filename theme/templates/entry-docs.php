<?php
/**
 * A documentation page — sidebar (from $nav) + the doc body.
 *
 * @var array{handle:string,name:string} $collection
 * @var array{slug:string,title:string,published_at:?string,fields:array<string,mixed>} $entry
 * @var list<array<string,mixed>> $nav
 * @var callable $partial
 * @var callable $e
 */
$body = (string) ($entry['fields']['body'] ?? '');
$paras = $body === '' ? [] : (preg_split('/\n{2,}/', trim($body)) ?: []);
?>
<div class="docs">
  <div class="wrap docs-grid">
    <?= $partial('docnav', ['nav' => $nav ?? [], 'current' => $entry['slug'] ?? '']) ?>
    <article class="prose">
      <p class="kicker">Docs <span class="version-badge">v0.1</span></p>
      <h1><?= $e($entry['title']) ?></h1>
      <div class="body">
        <?php foreach ($paras as $p): ?><p><?= nl2br($e($p)) ?></p><?php endforeach; ?>
      </div>
      <p class="back-link"><a href="/docs">← All docs</a></p>
    </article>
    <div class="docs-toc" aria-hidden="true"></div>
  </div>
</div>
