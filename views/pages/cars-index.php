<?php

declare(strict_types=1);

/** @var string $search */
/** @var string|null $activeCategory */
/** @var int|null $activeMinYear */
/** @var int|null $activeMaxYear */
/** @var int|null $activeMinPrice */
/** @var int|null $activeMaxPrice */
/** @var list<array<string, mixed>> $filtered */
/** @var array<string, mixed> $queryGet */
/** @var int $page */
/** @var int $totalPages */
/** @var int $totalItems */

$total = $totalItems;
$hasFilters = $search !== '' || $activeCategory || $activeMinYear || $activeMaxYear || $activeMinPrice || $activeMaxPrice;
$yearOptions = car_years();
$priceOptions = car_price_values();
?>
<div class="min-h-screen bg-background flex flex-col">
  <div class="border-b border-border bg-gradient-to-b from-muted/40 to-background">
    <div class="mx-auto max-w-[1400px] px-4 pb-10 pt-24 sm:pb-12 sm:pt-28">
      <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between" data-reveal>
        <div>
          <h1 class="font-heading text-4xl font-bold uppercase tracking-wider text-foreground md:text-5xl lg:text-6xl">
            Browse <span class="text-primary">cars</span>
          </h1>
          <p class="mt-3 max-w-xl text-base text-muted-foreground md:text-lg">
            Used vehicles in Pretoria — filter by brand, body type, or year, or search by model.
          </p>
        </div>
        <div class="shrink-0 rounded-xl border border-border bg-card/80 px-4 py-3 text-center shadow-sm backdrop-blur-sm md:text-right">
          <p class="font-heading text-3xl font-bold text-primary md:text-4xl"><?= $total ?></p>
          <p class="text-xs font-medium uppercase tracking-wider text-muted-foreground"><?= $total === 1 ? 'vehicle' : 'vehicles' ?> listed</p>
        </div>
      </div>
    </div>
  </div>

  <div class="mx-auto w-full max-w-[1400px] flex-1 px-4 py-10 sm:py-12 md:py-14">
    <div class="mb-10 rounded-2xl border border-border bg-card p-5 shadow-[0_8px_30px_rgba(0,0,0,0.06)] sm:p-6 md:p-8">
      <form method="get" action="cars" class="mb-8">
        <label for="cars-search" class="sr-only">Search cars</label>
        <div class="relative flex flex-col gap-3 sm:flex-row sm:items-stretch">
          <div class="relative min-w-0 flex-1">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground" aria-hidden="true">
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
            </span>
            <input
              id="cars-search"
              type="search"
              name="q"
              value="<?= esc($search) ?>"
              placeholder="Search brand, model, or year…"
              class="h-12 w-full rounded-xl border-2 border-border bg-background pl-12 pr-4 text-foreground shadow-inner placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 sm:h-14 sm:text-base"
            >
            <?php if ($activeCategory): ?><input type="hidden" name="type" value="<?= esc($activeCategory) ?>"><?php endif; ?>
            <?php if ($activeMinYear): ?><input type="hidden" name="min_year" value="<?= esc((string) $activeMinYear) ?>"><?php endif; ?>
            <?php if ($activeMaxYear): ?><input type="hidden" name="max_year" value="<?= esc((string) $activeMaxYear) ?>"><?php endif; ?>
            <?php if ($activeMinPrice): ?><input type="hidden" name="min_price" value="<?= esc((string) $activeMinPrice) ?>"><?php endif; ?>
            <?php if ($activeMaxPrice): ?><input type="hidden" name="max_price" value="<?= esc((string) $activeMaxPrice) ?>"><?php endif; ?>
          </div>
          <button type="submit" class="inline-flex h-12 shrink-0 items-center justify-center rounded-xl bg-primary px-8 font-heading text-sm font-semibold uppercase tracking-wider text-primary-foreground shadow-md transition-all duration-200 hover:opacity-90 hover:shadow-lg active:scale-[0.98] sm:h-14 sm:px-10">
            Search
          </button>
        </div>
      </form>

      <div class="mb-8">
        <p class="mb-3 font-heading text-[11px] font-semibold uppercase tracking-[0.22em] text-muted-foreground">Categories</p>
        <div class="flex flex-wrap gap-2">
          <?php foreach (car_categories() as $c):
              $on = $activeCategory === $c;
              $href = cars_query_href(['type' => $on ? '' : $c], $queryGet);
              ?>
            <a
              href="<?= esc($href) ?>"
              class="inline-flex min-h-9 items-center rounded-full border-2 px-4 py-1.5 font-heading text-xs font-semibold uppercase tracking-wider transition-all sm:text-sm <?= $on ? 'border-primary bg-primary text-primary-foreground shadow-sm' : 'border-border bg-background text-foreground shadow-sm hover:border-primary/45 hover:text-primary' ?>"
            ><?= esc($c) ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="rounded-xl border border-border/80 bg-gradient-to-b from-muted/50 via-muted/20 to-transparent p-5 shadow-inner sm:p-6 md:p-7">
        <p class="mb-6 flex items-center gap-3 font-heading text-xs font-bold uppercase tracking-[0.28em] text-foreground">
          <span class="inline-block h-1 w-7 shrink-0 rounded-full bg-primary" aria-hidden="true"></span>
          Filters
        </p>

        <form method="get" action="cars">
          <input type="hidden" name="q" value="<?= esc($search) ?>">
          <?php if ($activeCategory): ?><input type="hidden" name="type" value="<?= esc($activeCategory) ?>"><?php endif; ?>

          <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="min-w-0">
              <label for="cars-min-year" class="mb-2 block font-heading text-xs font-semibold uppercase tracking-[0.16em] text-foreground">Min Year</label>
              <div class="relative">
                <select
                  name="min_year"
                  id="cars-min-year"
                  class="h-12 w-full cursor-pointer appearance-none rounded-xl border border-border bg-background px-4 pr-10 font-heading text-sm font-semibold text-foreground shadow-sm transition-colors hover:border-primary/35 focus:outline-none focus-visible:border-primary/50 focus-visible:ring-2 focus-visible:ring-primary/15"
                >
                  <option value="">Select</option>
                  <?php foreach ($yearOptions as $y): ?>
                    <option value="<?= (int) $y ?>" <?= $activeMinYear === $y ? ' selected' : '' ?>><?= (int) $y ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground" aria-hidden="true">
                  <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                  </svg>
                </span>
              </div>
            </div>

            <div class="min-w-0">
              <label for="cars-max-year" class="mb-2 block font-heading text-xs font-semibold uppercase tracking-[0.16em] text-foreground">Max Year</label>
              <div class="relative">
                <select
                  name="max_year"
                  id="cars-max-year"
                  class="h-12 w-full cursor-pointer appearance-none rounded-xl border border-border bg-background px-4 pr-10 font-heading text-sm font-semibold text-foreground shadow-sm transition-colors hover:border-primary/35 focus:outline-none focus-visible:border-primary/50 focus-visible:ring-2 focus-visible:ring-primary/15"
                >
                  <option value="">Select</option>
                  <?php foreach ($yearOptions as $y): ?>
                    <option value="<?= (int) $y ?>" <?= $activeMaxYear === $y ? ' selected' : '' ?>><?= (int) $y ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground" aria-hidden="true">
                  <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                  </svg>
                </span>
              </div>
            </div>

            <div class="min-w-0">
              <label for="cars-min-price" class="mb-2 block font-heading text-xs font-semibold uppercase tracking-[0.16em] text-foreground">Min Price</label>
              <div class="relative">
                <select
                  name="min_price"
                  id="cars-min-price"
                  class="h-12 w-full cursor-pointer appearance-none rounded-xl border border-border bg-background px-4 pr-10 font-heading text-sm font-semibold text-foreground shadow-sm transition-colors hover:border-primary/35 focus:outline-none focus-visible:border-primary/50 focus-visible:ring-2 focus-visible:ring-primary/15"
                >
                  <option value="">Select</option>
                  <?php foreach ($priceOptions as $price): ?>
                    <option value="<?= (int) $price ?>" <?= $activeMinPrice === $price ? ' selected' : '' ?>><?= esc(format_rand($price)) ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground" aria-hidden="true">
                  <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                  </svg>
                </span>
              </div>
            </div>

            <div class="min-w-0">
              <label for="cars-max-price" class="mb-2 block font-heading text-xs font-semibold uppercase tracking-[0.16em] text-foreground">Max Price</label>
              <div class="relative">
                <select
                  name="max_price"
                  id="cars-max-price"
                  class="h-12 w-full cursor-pointer appearance-none rounded-xl border border-border bg-background px-4 pr-10 font-heading text-sm font-semibold text-foreground shadow-sm transition-colors hover:border-primary/35 focus:outline-none focus-visible:border-primary/50 focus-visible:ring-2 focus-visible:ring-primary/15"
                >
                  <option value="">Select</option>
                  <?php foreach ($priceOptions as $price): ?>
                    <option value="<?= (int) $price ?>" <?= $activeMaxPrice === $price ? ' selected' : '' ?>><?= esc(format_rand($price)) ?></option>
                  <?php endforeach; ?>
                </select>
                <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground" aria-hidden="true">
                  <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <div class="mt-5 flex flex-wrap items-center gap-3">
            <button type="submit" class="inline-flex min-h-10 items-center rounded-full bg-primary px-5 font-heading text-xs font-semibold uppercase tracking-wider text-primary-foreground shadow-sm transition-opacity hover:opacity-90 sm:text-sm">
              Apply filters
            </button>
            <?php if ($hasFilters): ?>
              <a href="cars" class="inline-flex min-h-10 items-center rounded-full border border-border bg-background px-5 font-heading text-xs font-semibold uppercase tracking-wider text-muted-foreground transition-colors hover:border-primary/40 hover:text-primary sm:text-sm">
                Clear all filters
              </a>
            <?php endif; ?>
          </div>
        </form>

      </div>
    </div>

    <?php if ($total > 0): ?>
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-7 lg:grid-cols-3 xl:grid-cols-4" data-reveal-stagger>
        <?php foreach ($filtered as $car):
            $cardBrandLogo = brand_logo_url((string) $car['brand']);
            $cardImage = car_primary_image_url($car);
            ?>
          <a
            href="car?id=<?= (int) $car['id'] ?>"
            class="group flex flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-md transition-all duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl"
          >
            <div class="relative aspect-[4/3] overflow-hidden bg-muted">
              <img
                src="<?= esc($cardImage) ?>"
                alt="<?= esc((string) $car['name']) ?>"
                class="h-full w-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
                width="800"
                height="480"
              >
              <span class="absolute left-3 top-3 rounded-md bg-primary/95 px-2.5 py-1 font-heading text-[10px] font-bold uppercase tracking-wider text-primary-foreground shadow-sm sm:text-xs">
                <?= esc((string) $car['category']) ?>
              </span>
              <?php if ($cardBrandLogo !== ''): ?>
                <span class="absolute bottom-3 right-3 flex h-10 w-10 items-center justify-center overflow-hidden rounded-lg border border-white/40 bg-background/95 shadow-md backdrop-blur-sm" title="<?= esc((string) $car['brand']) ?>">
                  <img src="<?= esc($cardBrandLogo) ?>" alt="<?= esc((string) $car['brand']) ?>" class="h-full w-full object-cover" width="40" height="40" loading="lazy" decoding="async">
                </span>
              <?php endif; ?>
            </div>
            <div class="flex flex-1 flex-col p-5 sm:p-6">
              <h2 class="font-heading text-lg font-bold leading-snug text-foreground transition-colors group-hover:text-primary sm:text-xl">
                <?= esc((string) $car['name']) ?>
              </h2>
              <p class="mt-1.5 text-sm text-muted-foreground"><?= esc((string) $car['mileage']) ?> · <?= (int) $car['year'] ?></p>
              <div class="mt-auto flex flex-col gap-3 border-t border-border pt-4 sm:flex-row sm:items-center sm:justify-between">
                <span class="font-heading text-2xl font-bold text-primary"><?= esc((string) $car['price']) ?></span>
                <span class="inline-flex items-center font-heading text-xs font-semibold uppercase tracking-wider text-primary opacity-90 transition-opacity group-hover:opacity-100">
                  View details
                  <span class="ml-1 transition-transform group-hover:translate-x-0.5" aria-hidden="true">→</span>
                </span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($totalPages > 1): ?>
        <nav class="mt-10 flex flex-wrap items-center justify-center gap-2" aria-label="Cars pages">
          <?php
          $prevHref = cars_query_href(['page' => $page - 1], $queryGet);
          $nextHref = cars_query_href(['page' => $page + 1], $queryGet);
          ?>
          <a href="<?= esc($prevHref) ?>" class="inline-flex min-h-10 items-center rounded-lg border border-border bg-card px-4 font-heading text-xs font-semibold uppercase tracking-wider text-foreground transition-colors hover:border-primary/50 hover:text-primary <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">Previous</a>
          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="<?= esc(cars_query_href(['page' => $p], $queryGet)) ?>" class="inline-flex min-h-10 min-w-10 items-center justify-center rounded-lg border px-3 font-heading text-xs font-semibold uppercase tracking-wider transition-colors <?= $p === $page ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-card text-foreground hover:border-primary/50 hover:text-primary' ?>"><?= $p ?></a>
          <?php endfor; ?>
          <a href="<?= esc($nextHref) ?>" class="inline-flex min-h-10 items-center rounded-lg border border-border bg-card px-4 font-heading text-xs font-semibold uppercase tracking-wider text-foreground transition-colors hover:border-primary/50 hover:text-primary <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">Next</a>
        </nav>
        <p class="mt-3 text-center text-xs font-medium uppercase tracking-wider text-muted-foreground">Page <?= $page ?> of <?= $totalPages ?></p>
      <?php endif; ?>
    <?php else: ?>
      <div class="rounded-2xl border-2 border-dashed border-border bg-muted/20 px-6 py-16 text-center sm:py-20">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-3xl" aria-hidden="true">🚗</div>
        <p class="font-heading text-xl font-semibold uppercase tracking-wider text-foreground">No cars match</p>
        <p class="mx-auto mt-2 max-w-md text-muted-foreground">Try clearing filters or searching with different keywords.</p>
        <a href="cars" class="mt-8 inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-8 font-heading text-sm font-semibold uppercase tracking-wider text-primary-foreground transition-opacity hover:opacity-90">
          Reset catalogue
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>
