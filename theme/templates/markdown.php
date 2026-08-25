<?php
/**
 * A tiny, dependency-free Markdown → HTML renderer — enough for docs and prose
 * (headings, paragraphs, lists, fenced code, inline code, bold/italic, links).
 * Everything is escaped first, so author content can never inject HTML; the
 * markup we add back is a fixed, safe set. Included via
 * `$partial('markdown', ['text' => $source])`.
 *
 * @var string   $text  the Markdown source
 * @var callable $e     escape helper
 */
$src   = str_replace(["\r\n", "\r"], "\n", (string) ($text ?? ''));
$lines = explode("\n", $src);
$out   = '';
$i     = 0;
$n     = count($lines);

/** Inline spans on an already-HTML-escaped string. */
$inline = static function (string $s): string {
    // links [text](url) — url is attribute-escaped and scheme-limited
    $s = preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', static function (array $m): string {
        $url = $m[2];
        if (!preg_match('#^(https?:|/|#|mailto:)#', $url)) {
            return $m[0];
        }
        return '<a href="' . htmlspecialchars($url, ENT_QUOTES) . '">' . $m[1] . '</a>';
    }, $s) ?? $s;
    $s = preg_replace('/`([^`]+)`/', '<code>$1</code>', $s) ?? $s;
    $s = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $s) ?? $s;
    $s = preg_replace('/(?<![\w*])\*([^*\n]+)\*(?![\w*])/', '<em>$1</em>', $s) ?? $s;
    return $s;
};

while ($i < $n) {
    $line = $lines[$i];

    // fenced code block
    if (preg_match('/^```/', $line)) {
        $i++;
        $code = [];
        while ($i < $n && !preg_match('/^```/', $lines[$i])) {
            $code[] = $e($lines[$i]);
            $i++;
        }
        $i++; // closing fence
        $out .= "<pre><code>" . implode("\n", $code) . "</code></pre>\n";
        continue;
    }

    // heading (## → h2, ### → h3, #### → h4; a doc's h1 is its title)
    if (preg_match('/^(#{1,6})\s+(.*)$/', $line, $m)) {
        $level = min(max(strlen($m[1]) + 1, 2), 4);
        $out  .= "<h{$level}>" . $inline($e(trim($m[2]))) . "</h{$level}>\n";
        $i++;
        continue;
    }

    // list (unordered - / * , or ordered 1. )
    if (preg_match('/^\s*([-*]|\d+\.)\s+/', $line)) {
        $ordered = (bool) preg_match('/^\s*\d+\.\s+/', $line);
        $tag     = $ordered ? 'ol' : 'ul';
        $out    .= "<{$tag}>\n";
        while ($i < $n && preg_match('/^\s*([-*]|\d+\.)\s+(.*)$/', $lines[$i], $lm)) {
            $out .= '<li>' . $inline($e(trim($lm[2]))) . "</li>\n";
            $i++;
        }
        $out .= "</{$tag}>\n";
        continue;
    }

    // blank line
    if (trim($line) === '') {
        $i++;
        continue;
    }

    // paragraph: gather until a blank line or a block starter
    $para = [];
    while ($i < $n && trim($lines[$i]) !== '' && !preg_match('/^(#{1,6}\s|```|\s*([-*]|\d+\.)\s)/', $lines[$i])) {
        $para[] = $inline($e(trim($lines[$i])));
        $i++;
    }
    $out .= '<p>' . implode('<br>', $para) . "</p>\n";
}

echo $out;
