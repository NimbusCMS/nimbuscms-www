<?php
/**
 * The docs sidebar — built from `$nav` (the collection's live entries), grouped
 * by the `section` field and ordered by the `order` field. Zero JS: a native
 * <details> that collapses on small screens and opens as a sticky column on
 * wide ones (see app.css).
 *
 * @var list<array<string,mixed>> $nav  the collection nav list
 * @var string                    $current  the current entry's slug (or '')
 * @var callable                  $e
 */
$current   = $current ?? '';
$bySection = [];
foreach (($nav ?? []) as $n) {
    $section = trim((string) ($n['fields']['section'] ?? '')) ?: 'Docs';
    $bySection[$section][] = $n;
}
ksort($bySection); // Getting Started · Guides · Reference sort naturally
foreach ($bySection as &$items) {
    usort($items, static fn (array $a, array $b): int => ((int) ($a['fields']['order'] ?? 0)) <=> ((int) ($b['fields']['order'] ?? 0)));
}
unset($items);
?>
<details class="docs-nav" open>
    <summary>Documentation</summary>
    <?php foreach ($bySection as $section => $items): ?>
        <p class="kicker"><?= $e($section) ?></p>
        <ul>
            <?php foreach ($items as $n): ?>
                <li><a href="/docs/<?= $e($n['slug']) ?>"<?= $current === $n['slug'] ? ' aria-current="page"' : '' ?>><?= $e($n['title']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
</details>
