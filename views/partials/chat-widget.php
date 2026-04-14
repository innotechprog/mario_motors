<?php

declare(strict_types=1);

$wa = (string) config('whatsapp_number');
$waDigits = preg_replace('/\D/', '', $wa);
$waBase = $waDigits !== '' ? 'https://wa.me/' . $waDigits : '';
$prefill = (string) config('chat_whatsapp_prefill', 'Hi Mario Motors, ');
$waUrl = $waBase !== '' ? $waBase . ($prefill !== '' ? '?text=' . rawurlencode($prefill) : '') : '';
$email = (string) config('app_email');
$mailto = $email !== '' ? 'mailto:' . $email . '?subject=' . rawurlencode('Enquiry — ' . (string) config('app_name')) : '';

if ($waUrl === '' && $mailto === '') {
    return;
}
?>
<div class="fixed bottom-5 right-5 z-[100] flex flex-col items-end gap-0 sm:bottom-6 sm:right-6" id="site-chat-widget">
  <details class="group relative">
    <summary
      class="flex h-14 w-14 cursor-pointer list-none items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg shadow-primary/40 ring-2 ring-white/90 transition-transform hover:scale-105 hover:opacity-95 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brandyellow focus-visible:ring-offset-2 [&::-webkit-details-marker]:hidden"
      aria-label="Open chat options"
    >
      <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
        <path d="M7 9h10v2H7zm0-3h10v2H7zm0 6h7v2H7z"/>
      </svg>
    </summary>
    <div
      class="absolute bottom-[calc(100%+0.75rem)] right-0 w-[min(calc(100vw-2.5rem),18rem)] origin-bottom-right rounded-xl border border-border bg-card p-4 shadow-xl"
      role="region"
      aria-label="Chat with us"
    >
      <p class="font-heading text-sm font-semibold uppercase tracking-wider text-foreground">Chat with us</p>
      <p class="mt-1 text-xs text-muted-foreground">Choose how you’d like to reach <?= esc((string) config('app_name')) ?>.</p>
      <ul class="mt-4 space-y-2">
        <?php if ($waUrl !== ''): ?>
        <li>
          <a
            href="<?= esc($waUrl) ?>"
            class="flex min-h-11 items-center justify-center gap-2 rounded-lg bg-[#25D366] px-4 py-2.5 text-sm font-semibold text-white transition-opacity hover:opacity-90"
            target="_blank"
            rel="noopener noreferrer"
          >
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.883 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            WhatsApp
          </a>
        </li>
        <?php endif; ?>
        <?php if ($mailto !== ''): ?>
        <li>
          <a
            href="<?= esc($mailto) ?>"
            class="flex min-h-11 items-center justify-center gap-2 rounded-lg border-2 border-primary bg-transparent px-4 py-2.5 text-sm font-semibold text-primary transition-colors hover:bg-primary/10"
          >
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            Email us
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </details>
</div>
<script>
(function () {
  var root = document.getElementById('site-chat-widget');
  if (!root) return;
  var d = root.querySelector('details');
  if (!d) return;
  document.addEventListener('click', function (e) {
    if (!d.open) return;
    if (root.contains(e.target)) return;
    d.open = false;
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') d.open = false;
  });
})();
</script>
