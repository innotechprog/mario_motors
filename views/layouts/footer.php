<?php

declare(strict_types=1);

$y = (int) date('Y');
$appName = (string) config('app_name');
$wa = (string) config('whatsapp_number');
$waUrl = $wa !== '' ? 'https://wa.me/' . preg_replace('/\D/', '', $wa) : '';
$tagline = business_services_tagline() ?: 'Mario Motors & Spare Parts';
$addrPlain = trim(str_replace(["\r\n", "\n", "\r"], ', ', business_address_display()));
$mapsQuery = $addrPlain !== '' ? rawurlencode($addrPlain) : '';
$mapsUrl = $mapsQuery !== '' ? 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery : '';
$addrCfg = config('business_address');
$hours = business_hours();
$footerLocLine = '';
if (is_array($addrCfg)) {
    $bits = array_filter([
        trim((string) ($addrCfg['street'] ?? '')),
        trim((string) ($addrCfg['suburb'] ?? '')),
        trim((string) ($addrCfg['city'] ?? '')),
    ], static fn (string $s): bool => $s !== '');
    $footerLocLine = implode(' · ', $bits);
}
?>
<footer class="mt-auto border-t-2 border-primary bg-card">
  <div class="mx-auto max-w-[1400px] px-4 py-12 sm:px-6 sm:py-14 lg:py-16">
    <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:gap-10 lg:gap-y-14">
      <div class="lg:col-span-5">
        <a href="./" class="mb-5 inline-block">
          <?php if (has_site_logo()): ?>
            <img
              src="<?= esc(logo_url()) ?>"
              alt="<?= esc($appName) ?>"
              class="h-12 w-auto max-w-[220px] object-contain object-left sm:h-14 sm:max-w-[260px]"
              width="260"
              height="56"
              decoding="async"
            >
          <?php else: ?>
            <span class="font-heading text-2xl font-bold uppercase tracking-wider text-primary sm:text-3xl">
              <?= esc($appName) ?>
            </span>
          <?php endif; ?>
        </a>
        <p class="font-heading text-sm font-medium uppercase tracking-[0.2em] text-primary">
          <?= esc($tagline) ?>
        </p>
        <p class="mt-4 max-w-md text-base leading-relaxed text-muted-foreground">
          We buy and sell used cars, spare parts, and new &amp; used batteries. Based in Mayville, Pretoria.
        </p>
        <?php if ($waUrl !== ''): ?>
        <p class="mt-6">
          <a
            href="<?= esc($waUrl) ?>"
            class="inline-flex min-h-11 items-center justify-center rounded-md border-2 border-primary bg-transparent px-6 py-2.5 font-heading text-sm uppercase tracking-wider text-primary transition-colors hover:bg-primary hover:text-primary-foreground"
            target="_blank"
            rel="noopener noreferrer"
          >WhatsApp us</a>
        </p>
        <?php endif; ?>
        <?php
        $socialIconsWrapperClass = 'mt-8';
        require VIEW_PATH . '/partials/social-icon-links.php';
        ?>
      </div>

      <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:col-span-4 lg:grid-cols-2 lg:gap-8">
        <div>
          <p class="mb-4 font-heading text-xs font-semibold uppercase tracking-[0.25em] text-muted-foreground">Explore</p>
          <ul class="space-y-1 text-sm">
            <li><a href="./" class="inline-flex min-h-10 items-center text-foreground transition-colors hover:text-primary">Home</a></li>
            <li><a href="cars" class="inline-flex min-h-10 items-center text-foreground transition-colors hover:text-primary">Browse cars</a></li>
            <li><a href="parts" class="inline-flex min-h-10 items-center text-foreground transition-colors hover:text-primary">Spare parts</a></li>
            <li><a href="contact" class="inline-flex min-h-10 items-center font-medium text-primary transition-colors hover:text-primary/80">Contact</a></li>
          </ul>
        </div>
        <div>
          <p class="mb-4 font-heading text-xs font-semibold uppercase tracking-[0.25em] text-muted-foreground">Hours</p>
          <ul class="space-y-2 text-sm text-muted-foreground">
            <?php foreach ($hours as $index => $hour): ?>
            <li class="flex justify-between gap-4 <?= $index < count($hours) - 1 ? 'border-b border-border/80 pb-2' : 'pt-1' ?>">
              <span class="text-foreground"><?= esc($hour['label']) ?></span>
              <span><?= esc($hour['value']) ?></span>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <div class="rounded-xl border border-border bg-background/60 p-6 shadow-sm sm:p-7 lg:col-span-3">
        <p class="mb-5 font-heading text-xs font-semibold uppercase tracking-[0.25em] text-muted-foreground">Visit &amp; call</p>
        <dl class="space-y-4 text-sm">
          <div>
            <dt class="sr-only">Phone</dt>
            <dd class="flex flex-col gap-1">
              <a href="tel:<?= esc(preg_replace('/[^\d+]/', '', (string) config('business_phone'))) ?>" class="font-medium text-foreground transition-colors hover:text-primary"><?= esc((string) config('business_phone_display')) ?></a>
              <a href="tel:<?= esc(preg_replace('/[^\d+]/', '', (string) config('business_mobile'))) ?>" class="font-medium text-foreground transition-colors hover:text-primary"><?= esc((string) config('business_mobile_display')) ?></a>
            </dd>
          </div>
          <div>
            <dt class="sr-only">Email</dt>
            <dd>
              <a href="mailto:<?= esc((string) config('app_email')) ?>" class="break-all text-muted-foreground transition-colors hover:text-primary"><?= esc((string) config('app_email')) ?></a>
            </dd>
          </div>
          <div>
            <dt class="sr-only">Address</dt>
            <dd class="whitespace-pre-line leading-relaxed text-muted-foreground"><?= esc(business_address_display()) ?></dd>
            <?php if ($mapsUrl !== ''): ?>
            <dd class="mt-3">
              <a href="<?= esc($mapsUrl) ?>" class="inline-flex text-sm font-medium text-primary underline-offset-4 transition-colors hover:underline" target="_blank" rel="noopener noreferrer">Open in Maps</a>
            </dd>
            <?php endif; ?>
          </div>
        </dl>
      </div>
    </div>

    <div class="mt-12 space-y-4 border-t border-border pt-8 text-xs text-muted-foreground">
      <div class="flex flex-col items-center justify-between gap-4 text-center sm:flex-row sm:text-left">
        <p>© <?= $y ?> <?= esc($appName) ?>. All rights reserved.</p>
        <?php if ($footerLocLine !== ''): ?>
        <p class="max-w-md sm:text-right"><?= esc($footerLocLine) ?></p>
        <?php endif; ?>
      </div>
      <p class="text-center sm:text-left">
        Website developed by
        <a
          href="https://ib-innovativesolutions.com/it-solutions"
          class="font-medium text-primary/90 underline-offset-2 transition-colors hover:text-primary hover:underline"
          target="_blank"
          rel="noopener noreferrer"
        >IB Innovative Solutions</a>
      </p>
    </div>
  </div>
</footer>
