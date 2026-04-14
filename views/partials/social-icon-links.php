<?php

declare(strict_types=1);

$waUrl = $waUrl ?? '';
$socialIconsWrapperClass = $socialIconsWrapperClass ?? 'mt-8';
$links = social_icon_links($waUrl);
if ($links === []) {
    return;
}
?>
<div class="<?= esc($socialIconsWrapperClass) ?>">
  <p class="mb-3 font-heading text-xs font-semibold uppercase tracking-[0.25em] text-muted-foreground">
    <?= esc($socialIconsHeading ?? 'Follow us') ?>
  </p>
  <ul class="flex flex-wrap items-center gap-2 sm:gap-3" role="list">
    <?php foreach ($links as $item): ?>
      <li>
        <a
          href="<?= esc($item['url']) ?>"
          class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background text-foreground shadow-sm transition-colors hover:border-primary hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 focus-visible:ring-offset-2 focus-visible:ring-offset-card"
          aria-label="<?= esc($item['label']) ?>"
          target="_blank"
          rel="noopener noreferrer"
        ><?= social_icon_svg($item['network']) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
