<?php
/**
 * A single entry, centered reading column — used for /pages/{slug} (prose/legal)
 * and any collection without its own entry-{handle} template.
 *
 * @var array{handle:string,name:string} $collection
 * @var array{title:string,published_at:?string,fields:array<string,mixed>} $entry
 * @var callable $e escape helper
 */
$renderField = static function (mixed $value) use ($e): string {
    if ($value === null || $value === '' || $value === []) {
        return '';
    }
    if (is_array($value) && isset($value['url'])) {
        return '<img src="' . $e((string) $value['url']) . '" alt="' . $e((string) ($value['alt'] ?? '')) . '">';
    }
    if (is_array($value) && isset($value[0]) && is_array($value[0])) {
        return implode(', ', array_map(static fn (array $t): string => $e((string) ($t['title'] ?? '')), $value));
    }
    if (is_bool($value)) {
        return $value ? 'Yes' : 'No';
    }
    if (is_scalar($value)) {
        return nl2br($e((string) $value));
    }
    return '';
};
$fields = $entry['fields'] ?? [];
$body   = (string) ($fields['body'] ?? '');
unset($fields['body'], $fields['summary']); // summary is meta; body renders as Markdown last
?>
<article class="prose">
  <div class="measure">
    <p class="kicker"><?= $e($collection['name']) ?></p>
    <h1><?= $e($entry['title']) ?></h1>
    <?php if (!empty($entry['published_at'])): ?>
      <p class="meta">Last updated <?= $e(date('j M Y', strtotime($entry['published_at']))) ?></p>
    <?php endif; ?>
    <div class="body">
      <?php foreach ($fields as $handle => $value): ?>
        <?php $r = $renderField($value); ?>
        <?php if ($r !== ''): ?><div class="field field-<?= $e($handle) ?>"><?= $r ?></div><?php endif; ?>
      <?php endforeach; ?>
      <?= $partial('markdown', ['text' => $body]) ?>
    </div>
    <a class="back-link" href="/">← Home</a>
  </div>
</article>
