<?php

declare(strict_types=1);

/** @var list<array{title: string, desc: string, icon: string}> $why */
/** @var string $hero */
/** @var list<array<string, mixed>> $previewCars */

$brandsBanner = brands_banner_url();
$brandList = featured_car_brands();
$brandsAlt = $brandList === [] ? 'Vehicle brands' : implode(', ', $brandList);
$siteWeb = (string) config('public_website', '');
$siteWebDisplay = (string) config('public_website_display', '');
$wa = (string) config('whatsapp_number');
$waUrl = $wa !== '' ? 'https://wa.me/' . preg_replace('/\D/', '', $wa) : '';
?>
<div class="min-h-screen bg-background flex flex-col">
  <section class="relative min-h-[min(100svh,56rem)] overflow-hidden rounded-b-[2rem] shadow-xl shadow-primary/10 sm:min-h-[min(100svh,60rem)] sm:rounded-b-[2.75rem] md:rounded-b-[3.25rem] pt-[4.75rem] sm:pt-20">
    <img
      src="<?= esc($hero) ?>"
      alt=""
      class="absolute inset-0 h-full w-full object-cover brightness-[0.5]"
      width="1920"
      height="1080"
    >
    <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/35 to-black/80"></div>

    <div class="relative z-10 flex min-h-[min(100svh,56rem)] flex-col sm:min-h-[min(100svh,60rem)]">
      <div class="flex flex-1 flex-col justify-center px-4 pb-44 text-center sm:pb-48 md:px-8">
        <div class="mm-hero-seq">
        <p class="mb-3 font-heading text-xs font-semibold uppercase tracking-[0.35em] text-brandyellow sm:text-sm">
          Mayville · Pretoria
        </p>
        <h1 class="mx-auto max-w-[22rem] font-heading text-3xl font-bold uppercase leading-snug tracking-wider text-white drop-shadow-md sm:max-w-none sm:text-4xl sm:leading-tight md:text-5xl lg:text-6xl">
          Mario Motors and Spare Parts
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-white/95 sm:text-lg md:text-xl">
          We <strong class="font-semibold text-white">buy and sell</strong> used cars and spare parts.
          We stock <strong class="font-semibold text-brandyellow">new</strong> and <strong class="font-semibold text-brandyellow">used</strong> batteries — everything under one roof.
        </p>

        <div class="mx-auto mt-10 flex max-w-lg flex-col gap-3 sm:max-w-none sm:flex-row sm:justify-center sm:gap-4">
          <a
            href="cars"
            class="inline-flex min-h-12 items-center justify-center rounded-md bg-primary px-10 py-3.5 font-heading text-base uppercase tracking-wider text-primary-foreground shadow-lg shadow-black/20 transition-all duration-200 hover:opacity-90 hover:shadow-xl hover:shadow-black/25 active:scale-[0.98] sm:px-12 sm:text-lg"
          >Browse cars</a>
          <a
            href="parts"
            class="inline-flex min-h-12 items-center justify-center rounded-md border-2 border-brandyellow bg-white/10 px-10 py-3.5 font-heading text-base uppercase tracking-wider text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20 active:scale-[0.98] sm:px-12 sm:text-lg"
          >Spare parts &amp; batteries</a>
        </div>
        </div>
      </div>

      <div class="absolute bottom-0 left-0 right-0 bg-primary px-3 py-3 text-center text-xs text-white shadow-[0_-12px_40px_rgba(0,0,0,0.25)] sm:px-6 sm:text-sm md:text-base">
        <div class="mx-auto flex max-w-[1400px] flex-col flex-wrap items-center justify-center gap-x-4 gap-y-2 sm:flex-row sm:gap-x-8">
          <a href="tel:<?= esc(preg_replace('/[^\d+]/', '', (string) config('business_phone'))) ?>" class="font-heading font-semibold tracking-wide text-white transition-opacity hover:opacity-90"><?= esc((string) config('business_phone_display')) ?></a>
          <span class="hidden text-white/40 sm:inline" aria-hidden="true">|</span>
          <a href="tel:<?= esc(preg_replace('/[^\d+]/', '', (string) config('business_mobile'))) ?>" class="font-heading font-semibold tracking-wide text-white transition-opacity hover:opacity-90"><?= esc((string) config('business_mobile_display')) ?></a>
          <?php if ($waUrl !== ''): ?>
            <span class="hidden text-white/40 sm:inline" aria-hidden="true">|</span>
            <a href="<?= esc($waUrl) ?>" class="font-heading font-semibold tracking-wide text-white underline-offset-2 transition-opacity hover:opacity-90 hover:underline" target="_blank" rel="noopener noreferrer">WhatsApp</a>
          <?php endif; ?>
          <?php if ($siteWeb !== '' && $siteWebDisplay !== ''): ?>
            <span class="hidden text-white/40 md:inline" aria-hidden="true">|</span>
            <a href="<?= esc($siteWeb) ?>" class="truncate font-heading font-semibold tracking-wide text-white underline-offset-2 transition-opacity hover:opacity-90 hover:underline max-w-[18rem] sm:max-w-none" rel="noopener noreferrer"><?= esc($siteWebDisplay) ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="relative z-[1] -mt-6 bg-card px-4 pb-16 pt-12 shadow-[0_-12px_48px_rgba(0,0,0,0.06)] sm:-mt-8 sm:rounded-t-3xl sm:pb-20 sm:pt-16 md:-mt-10 md:pt-20" aria-labelledby="brands-heading">
    <div class="mx-auto max-w-[1400px]">
      <div class="mb-8 text-center md:mb-10" data-reveal>
        <h2 id="brands-heading" class="font-heading text-3xl font-bold uppercase tracking-wider text-foreground md:text-4xl">
          Brands we <span class="text-primary">stock</span>
        </h2>
        <p class="mx-auto mt-2 max-w-2xl text-sm text-muted-foreground md:text-base">
          OEM and quality parts for the makes South Africans drive every day — visit us or browse online.
        </p>
      </div>

      <?php if ($brandList !== [] || $brandsBanner !== null): ?>
        <div class="overflow-hidden rounded-xl border border-border bg-white shadow-sm md:mb-0" data-reveal>
          <?php if ($brandList !== []): ?>
            <ul class="flex flex-wrap justify-center gap-2 px-4 py-8 sm:gap-3 sm:px-6 sm:py-10 md:gap-3 md:py-12" role="list">
              <?php foreach ($brandList as $b): ?>
                <li>
                  <span class="inline-flex min-h-10 items-center rounded-full border-2 border-primary/80 bg-card px-3 py-2 font-heading text-xs font-semibold uppercase tracking-wider text-foreground shadow-sm sm:min-h-11 sm:px-5 sm:text-sm">
                    <?= esc($b) ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if ($brandsBanner !== null): ?>
            <div class="<?= $brandList !== [] ? 'border-t border-border bg-muted/25' : '' ?> px-4 py-6 sm:px-8 sm:py-8">
              <img
                src="<?= esc($brandsBanner) ?>"
                alt="<?= esc($brandsAlt) ?>"
                class="mx-auto w-full max-w-5xl object-contain object-center max-h-[min(28rem,55vh)] sm:max-h-[min(32rem,60vh)]"
                width="1200"
                height="320"
                loading="lazy"
                decoding="async"
              >
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <?php if ($previewCars !== []): ?>
  <section class="border-t border-border bg-background py-16 sm:py-20" aria-labelledby="featured-cars-heading">
    <div class="mx-auto max-w-[1400px] px-4">
      <div class="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-end sm:justify-between" data-reveal>
        <div>
          <h2 id="featured-cars-heading" class="font-heading text-3xl font-bold uppercase tracking-wider text-foreground md:text-4xl">
            Featured <span class="text-primary">cars</span>
          </h2>
          <p class="mt-2 max-w-xl text-sm text-muted-foreground md:text-base">
            A sample of our stock — visit us in Mayville or browse the full list online.
          </p>
        </div>
        <a
          href="cars"
          class="inline-flex min-h-11 shrink-0 items-center justify-center self-start rounded-md border-2 border-primary bg-transparent px-6 py-2.5 font-heading text-sm uppercase tracking-wider text-primary transition-all duration-200 hover:bg-primary/10 active:scale-[0.98] sm:self-auto"
        >View more</a>
      </div>
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6" data-reveal-stagger>
        <?php foreach ($previewCars as $car): ?>
          <a href="car?id=<?= (int) $car['id'] ?>" class="group block overflow-hidden rounded-lg border border-border bg-card transition-all duration-300 hover:-translate-y-1 hover:border-primary/50 hover:shadow-lg">
            <img src="<?= esc(car_primary_image_url($car)) ?>" alt="<?= esc((string) $car['name']) ?>" class="aspect-[4/3] w-full object-cover object-center" loading="lazy" width="800" height="512">
            <div class="p-4 sm:p-5">
              <span class="text-xs font-medium uppercase tracking-wider text-primary"><?= esc((string) $car['category']) ?></span>
              <h3 class="mt-1 font-heading text-lg font-semibold text-foreground transition-colors group-hover:text-primary sm:text-xl"><?= esc((string) $car['name']) ?></h3>
              <p class="mt-1 text-sm text-muted-foreground"><?= esc((string) $car['mileage']) ?> · <?= (int) $car['year'] ?></p>
              <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <span class="font-heading text-xl font-bold text-primary sm:text-2xl"><?= esc((string) $car['price']) ?></span>
                <span class="shrink-0 text-sm font-medium text-primary">View details →</span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php $partTiles = home_part_category_tiles(); ?>
  <?php if ($partTiles !== []): ?>
  <section class="border-t border-border bg-card py-16 sm:py-20" aria-labelledby="featured-parts-heading">
    <div class="mx-auto max-w-[1400px] px-4">
      <div class="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-end sm:justify-between" data-reveal>
        <div>
          <h2 id="featured-parts-heading" class="font-heading text-3xl font-bold uppercase tracking-wider text-foreground md:text-4xl">
            Parts by <span class="text-primary">category</span>
          </h2>
          <p class="mt-2 max-w-xl text-sm text-muted-foreground md:text-base">
            Shop brakes, engine parts, exhaust, lighting &amp; more — open a category or browse the full catalogue.
          </p>
        </div>
        <a
          href="parts"
          class="inline-flex min-h-11 shrink-0 items-center justify-center self-start rounded-md border-2 border-primary bg-transparent px-6 py-2.5 font-heading text-sm uppercase tracking-wider text-primary transition-all duration-200 hover:bg-primary/10 active:scale-[0.98] sm:self-auto"
        >View more</a>
      </div>
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6" data-reveal-stagger>
        <?php foreach ($partTiles as $tile): ?>
          <?php $tileHref = 'parts?cat=' . rawurlencode($tile['category']); ?>
          <a
            href="<?= esc($tileHref) ?>"
            class="group relative flex min-h-[16rem] overflow-hidden rounded-xl border border-border shadow-md transition-all duration-300 hover:-translate-y-1 hover:border-primary/60 hover:shadow-xl sm:min-h-[18rem] md:min-h-[20rem]"
          >
            <img
              src="<?= esc(asset('part-categories/' . $tile['image'])) ?>"
              alt="<?= esc($tile['title']) ?> parts"
              class="absolute inset-0 h-full w-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-105"
              width="480"
              height="600"
              loading="lazy"
              decoding="async"
            >
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/88 via-black/45 to-black/20"
              aria-hidden="true"
            ></div>
            <div class="relative z-10 mt-auto flex w-full flex-col justify-end p-5 pt-16 text-white md:p-6 md:pt-20">
              <h3 class="font-heading text-xl font-bold uppercase tracking-wider text-white drop-shadow-sm transition-colors group-hover:text-brandyellow md:text-2xl">
                <?= esc($tile['title']) ?>
              </h3>
              <?php if ($tile['blurb'] !== ''): ?>
                <p class="mt-2 text-sm leading-relaxed text-white/90 drop-shadow-sm md:text-base">
                  <?= esc($tile['blurb']) ?>
                </p>
              <?php endif; ?>
              <span class="mt-4 inline-flex items-center text-sm font-semibold text-brandyellow drop-shadow-sm">
                Browse <?= esc($tile['title']) ?> →
              </span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="py-24 bg-background">
    <div class="max-w-4xl mx-auto px-4 text-center" data-reveal>
      <h2 class="font-heading text-4xl md:text-5xl font-bold uppercase tracking-wider text-foreground mb-6">
        About <span class="text-primary"><?= esc((string) config('app_name')) ?></span>
      </h2>
      <p class="text-muted-foreground text-lg leading-relaxed mb-6">
        Founded in 2010, <?= esc((string) config('app_name')) ?> is your local partner in Mayville, Pretoria: we buy and sell used cars, stock spare parts, and supply new and used batteries — alongside genuine parts and friendly service.
      </p>
      <p class="text-muted-foreground text-lg leading-relaxed">
        Our team hand-selects vehicles, sources quality spares, and helps you find the right battery for your budget. Whether you're selling a car, buying your next ride, or keeping your vehicle on the road, <?= esc((string) config('app_name')) ?> has you covered.
      </p>
    </div>
  </section>

  <section class="py-24 flex-1 bg-card">
    <div class="max-w-[1400px] mx-auto px-4">
      <h2 class="font-heading text-4xl md:text-5xl font-bold uppercase tracking-wider text-foreground text-center mb-16" data-reveal>
        Why Choose <span class="text-primary">Us</span>
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" data-reveal-stagger>
        <?php foreach ($why as $item): ?>
          <div class="group text-center p-6 transition-transform duration-300 hover:-translate-y-1">
            <?php if (($item['icon'] ?? '') !== ''): ?>
            <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-2xl transition-transform duration-300 group-hover:scale-105" aria-hidden="true"><?= esc((string) $item['icon']) ?></div>
            <?php endif; ?>
            <h3 class="font-heading text-xl font-semibold uppercase tracking-wider text-foreground mb-3"><?= esc($item['title']) ?></h3>
            <p class="text-muted-foreground text-sm leading-relaxed"><?= esc($item['desc']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</div>
