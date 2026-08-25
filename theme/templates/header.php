<?php
/**
 * Site header — the broom mark ("Swift") + wordmark and the main menu.
 *
 * @var string   $appName the site name
 * @var array<string,list<array{label:string,url:string}>> $menus named menus
 * @var callable $e       escape a value for output
 */
$main = ($menus ?? [])['main'] ?? [];
?>
<header class="site-header">
    <div class="wrap">
        <a class="brand" href="/">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20.5 3.5 12.5 11.5"/><path d="M13.2 8.6 15.4 10.8"/><path d="M12.5 11.5 5 14.4"/><path d="M12.5 11.5 9.3 19.2"/><path d="M5 14.4Q6.1 17.9 9.3 19.2"/><path d="M3 5.6h3.2"/><path d="M2.6 9.2h2.2"/>
            </svg>
            <?= $e($appName) ?>
        </a>
        <?php if ($main !== []): ?>
            <nav class="site-nav" aria-label="Site">
                <?php foreach ($main as $item): ?>
                    <a href="<?= $e($item['url']) ?>"><?= $e($item['label']) ?></a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>
    </div>
</header>
