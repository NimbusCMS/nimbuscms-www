// Progressive enhancement only: adds a Copy button to each command/prompt block.
// With JavaScript off, the blocks are still plain selectable text — nothing breaks.
(function () {
  if (!navigator.clipboard) return;
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('pre.install').forEach(function (pre) {
      var code = pre.querySelector('code');
      if (!code) return;
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'copy-btn';
      btn.textContent = 'Copy';
      btn.setAttribute('aria-label', 'Copy to clipboard');
      btn.addEventListener('click', function () {
        // Strip a leading "$ " shell prompt from each line so commands paste clean.
        var text = code.innerText.replace(/^\s*\$\s?/gm, '').trim();
        navigator.clipboard.writeText(text).then(function () {
          btn.textContent = 'Copied';
          setTimeout(function () { btn.textContent = 'Copy'; }, 1500);
        });
      });
      pre.appendChild(btn);
    });
  });
})();
