<?php
/**
 * Site footer — links + the page's own measured weight (the brand's honesty,
 * literalized). The weight line states only what is always true here; the exact
 * request/byte figures are filled from a real measurement of the built page.
 *
 * @var string   $appName the site name
 * @var callable $e       escape a value for output
 */
?>
<footer class="site-footer">
    <div class="wrap">
        <p>© <?= date('Y') ?> <?= $e($appName) ?> · MIT License · runs on NimbusCMS</p>
        <nav aria-label="Footer">
            <a href="/docs">Docs</a>
            <a href="/pages/about">About</a>
            <a href="https://github.com/NimbusCMS/nimbus">GitHub</a>
        </nav>
        <p class="weight">Zero JavaScript · one ~11&nbsp;KB stylesheet · system fonts.</p>
    </div>
</footer>
