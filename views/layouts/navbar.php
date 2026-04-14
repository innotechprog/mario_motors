<?php

declare(strict_types=1);

$current = $currentNav ?? '';

$linkDesktop = 'font-heading uppercase tracking-wider text-sm px-3 py-2 lg:px-4 rounded-md transition-all duration-200 hover:bg-muted active:scale-[0.97]';
$linkMobile = 'block min-h-12 px-4 py-3 text-sm font-heading uppercase tracking-wider rounded-md transition-colors duration-200 hover:bg-muted active:bg-muted/80';
$contactBtnDesktop = 'inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 font-heading text-sm uppercase tracking-wider text-primary-foreground shadow-sm transition-all duration-200 hover:opacity-90 hover:shadow-md active:scale-[0.98] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 focus-visible:ring-offset-2 focus-visible:ring-offset-background lg:px-5';
$contactBtnDesktopCurrent = 'ring-2 ring-offset-2 ring-offset-background ring-primary/40';
$contactBtnMobile = 'mx-2 my-1 flex min-h-12 items-center justify-center rounded-md bg-primary px-4 py-3 text-sm font-heading uppercase tracking-wider text-primary-foreground transition-all duration-200 hover:opacity-90 active:scale-[0.98]';
$contactBtnMobileCurrent = 'ring-2 ring-inset ring-primary-foreground/30';
?>
<nav id="site-nav" class="fixed top-0 left-0 right-0 z-50 border-b border-border bg-background/90 backdrop-blur-md">
  <div class="mx-auto flex min-h-[4.25rem] max-w-[1400px] items-center justify-between px-4 py-2 sm:min-h-[4.5rem] sm:py-2.5">
    <a href="./" class="flex min-h-11 min-w-0 shrink items-center transition-transform duration-200 hover:opacity-95 active:scale-[0.98]">
      <?php if (has_site_logo()): ?>
        <img
          src="<?= esc(logo_url()) ?>"
          alt="<?= esc((string) config('app_name')) ?>"
          class="h-11 w-auto max-w-[240px] object-contain object-left sm:h-12 md:h-14 md:max-w-[280px]"
          width="280"
          height="56"
          decoding="async"
        >
      <?php else: ?>
        <span class="font-heading text-lg font-bold uppercase tracking-wider text-primary sm:text-xl md:text-2xl">
          <?= esc((string) config('app_name')) ?>
        </span>
      <?php endif; ?>
    </a>

    <div class="hidden items-center gap-1 md:flex lg:gap-2">
      <a href="cars" class="<?= esc($linkDesktop) ?> <?= $current === 'cars' ? 'text-primary' : '' ?>">Cars</a>
      <a href="parts" class="<?= esc($linkDesktop) ?> <?= $current === 'parts' ? 'text-primary' : '' ?>">Spare Parts</a>
      <a
        href="contact"
        class="<?= esc($contactBtnDesktop) ?> <?= $current === 'contact' ? esc($contactBtnDesktopCurrent) : '' ?>"
      >Contact</a>
    </div>

    <div id="nav-mobile-menu" class="relative md:hidden">
      <button
        id="nav-mobile-toggle"
        type="button"
        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md border border-border bg-card text-foreground shadow-sm transition-colors hover:bg-muted focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 focus-visible:ring-offset-2 focus-visible:ring-offset-background"
        aria-label="Open navigation menu"
        aria-controls="nav-mobile-panel"
        aria-expanded="false"
      >
        <span class="relative block h-[14px] w-6" aria-hidden="true">
          <span class="nav-toggle-line absolute left-0 top-0 block h-0.5 w-6 rounded-full bg-foreground transition duration-200 ease-out"></span>
          <span class="nav-toggle-line absolute left-0 top-[6px] block h-0.5 w-6 rounded-full bg-foreground transition duration-200 ease-out"></span>
          <span class="nav-toggle-line absolute left-0 top-[12px] block h-0.5 w-6 rounded-full bg-foreground transition duration-200 ease-out"></span>
        </span>
      </button>
      <div
        id="nav-mobile-panel"
        class="mm-nav-panel absolute right-0 top-[calc(100%+0.5rem)] z-[60] w-[min(calc(100vw-2rem),20rem)] rounded-lg border border-border bg-background/95 py-2 shadow-xl backdrop-blur-md"
        role="navigation"
        aria-label="Mobile"
        hidden
      >
        <a href="cars" class="<?= esc($linkMobile) ?> <?= $current === 'cars' ? 'text-primary' : '' ?>">Cars</a>
        <a href="parts" class="<?= esc($linkMobile) ?> <?= $current === 'parts' ? 'text-primary' : '' ?>">Spare Parts</a>
        <a
          href="contact"
          class="<?= esc($contactBtnMobile) ?> <?= $current === 'contact' ? esc($contactBtnMobileCurrent) : '' ?>"
        >Contact</a>
      </div>
    </div>
  </div>
</nav>
