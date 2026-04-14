<?php

declare(strict_types=1);

/** @var array<string, mixed> $part */
/** @var string $img */
/** @var list<string> $images */
/** @var list<array{label: string, value: string, icon: string}> $specs */
?>
<div class="min-h-screen bg-background flex flex-col">
  <div class="max-w-[1400px] mx-auto pt-24 pb-16 px-4 flex-1 w-full">
    <a href="parts" class="inline-flex items-center gap-2 text-primary hover:underline mb-6">
      ← Back to Parts
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
      <div class="w-full max-w-[760px] overflow-hidden">
        <?php $gallery = $images !== [] ? $images : [$img]; ?>
        <figure class="overflow-hidden rounded-lg border border-border bg-card">
          <img
            id="part-carousel-main"
            src="<?= esc($gallery[0]) ?>"
            alt="<?= esc((string) $part['name']) ?> image 1"
            class="h-80 w-full cursor-zoom-in object-cover lg:h-[450px]"
            loading="eager"
            width="1200"
            height="720"
          >
        </figure>
        <?php if (count($gallery) > 1): ?>
          <div class="mt-3 flex w-full items-center justify-between gap-2">
            <button type="button" id="part-gallery-prev" class="inline-flex min-h-9 items-center rounded-md border border-border bg-card px-3 text-xs font-semibold uppercase tracking-wider text-foreground transition-colors hover:border-primary/50 hover:text-primary">Prev</button>
            <button type="button" id="part-gallery-next" class="inline-flex min-h-9 items-center rounded-md border border-border bg-card px-3 text-xs font-semibold uppercase tracking-wider text-foreground transition-colors hover:border-primary/50 hover:text-primary">Next</button>
          </div>
          <div class="mt-3 w-full overflow-x-auto pb-1">
            <div class="flex w-max min-w-full gap-2">
            <?php foreach ($gallery as $idx => $photo): ?>
              <button type="button" data-index="<?= (int) $idx ?>" class="part-gallery-thumb block h-16 w-24 shrink-0 overflow-hidden rounded-md border border-border bg-card transition-colors hover:border-primary/60 <?= $idx === 0 ? 'ring-2 ring-primary/50' : '' ?>">
                <img src="<?= esc($photo) ?>" alt="<?= esc((string) $part['name']) ?> thumbnail <?= (int) ($idx + 1) ?>" class="h-full w-full object-cover" loading="lazy" width="192" height="128">
              </button>
            <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
        <div class="mt-3 flex w-full justify-end">
          <button type="button" id="part-gallery-full" class="inline-flex min-h-9 items-center rounded-md border border-border bg-card px-3 text-xs font-semibold uppercase tracking-wider text-foreground transition-colors hover:border-primary/50 hover:text-primary">Full view</button>
        </div>
        <?php if (count($gallery) > 1): ?>
          <p class="mt-2 text-xs font-medium uppercase tracking-wider text-muted-foreground">Use Prev/Next or tap a thumbnail</p>
        <?php endif; ?>
      </div>
      <div>
        <h1 class="font-heading text-3xl md:text-4xl font-bold uppercase tracking-wider text-foreground"><?= esc((string) $part['name']) ?></h1>
        <p class="font-heading text-4xl font-bold text-primary mt-4"><?= esc((string) $part['price']) ?></p>

        <div class="grid grid-cols-1 gap-3 mt-8 sm:grid-cols-2 xl:grid-cols-3">
          <?php foreach ($specs as $s): ?>
            <div class="bg-card border border-border rounded-lg p-4 flex items-start gap-3">
              <span class="text-xl leading-none text-primary" aria-hidden="true"><?= esc($s['icon']) ?></span>
              <div class="min-w-0">
                <p class="text-xs text-muted-foreground uppercase"><?= esc($s['label']) ?></p>
                <p class="font-medium text-foreground break-words"><?= esc($s['value']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-8">
          <h2 class="font-heading text-xl font-semibold uppercase tracking-wider text-foreground mb-3">Description</h2>
          <p class="text-muted-foreground leading-relaxed"><?= esc((string) ($part['description'] ?? 'No description provided for this part yet.')) ?></p>
        </div>

        <div class="mt-8 flex flex-wrap gap-4">
          <a href="contact" class="inline-flex items-center justify-center px-6 py-3 rounded-md border border-border bg-card font-heading uppercase tracking-wider text-lg hover:bg-muted">Ask a Question</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="part-lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/85 p-4" aria-hidden="true">
  <button type="button" id="part-lightbox-close" class="absolute right-4 top-4 inline-flex min-h-10 items-center rounded-md border border-white/40 bg-black/40 px-3 text-xs font-semibold uppercase tracking-wider text-white transition-colors hover:border-white hover:bg-black/60">Cancel full view</button>
  <img id="part-lightbox-img" src="" alt="" class="max-h-[92vh] w-auto max-w-[95vw] rounded-md object-contain">
</div>

<?php if (count($gallery) > 1): ?>
<script>
(() => {
  const main = document.getElementById('part-carousel-main');
  const prevBtn = document.getElementById('part-gallery-prev');
  const nextBtn = document.getElementById('part-gallery-next');
  const thumbs = Array.from(document.querySelectorAll('.part-gallery-thumb'));
  if (!main || !prevBtn || !nextBtn || thumbs.length === 0) return;

  const sources = thumbs.map((btn) => {
    const img = btn.querySelector('img');
    return img ? img.getAttribute('src') || '' : '';
  }).filter((src) => src !== '');
  if (sources.length === 0) return;

  let current = 0;

  const goTo = (idx) => {
    if (idx < 0) idx = 0;
    if (idx >= sources.length) idx = sources.length - 1;
    current = idx;
    main.setAttribute('src', sources[current]);
    main.setAttribute('alt', '<?= esc((string) $part['name']) ?> image ' + (current + 1));
    thumbs.forEach((btn, i) => {
      if (i === current) {
        btn.classList.add('ring-2', 'ring-primary/50');
      } else {
        btn.classList.remove('ring-2', 'ring-primary/50');
      }
    });
  };

  prevBtn.addEventListener('click', () => goTo((current - 1 + sources.length) % sources.length));
  nextBtn.addEventListener('click', () => goTo((current + 1) % sources.length));

  thumbs.forEach((btn) => {
    btn.addEventListener('click', () => {
      const idx = Number(btn.getAttribute('data-index') || '0');
      goTo(idx);
    });
  });

  goTo(0);
})();
</script>
<?php endif; ?>

<script>
(() => {
  const main = document.getElementById('part-carousel-main');
  const lightbox = document.getElementById('part-lightbox');
  const lightboxImg = document.getElementById('part-lightbox-img');
  const lightboxClose = document.getElementById('part-lightbox-close');
  const fullBtn = document.getElementById('part-gallery-full');
  if (!main || !lightbox || !lightboxImg || !lightboxClose || !fullBtn) return;
  if (fullBtn.dataset.lightboxBound === '1') return;
  fullBtn.dataset.lightboxBound = '1';

  const openLightbox = () => {
    lightboxImg.setAttribute('src', main.getAttribute('src') || '');
    lightboxImg.setAttribute('alt', main.getAttribute('alt') || '');
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    lightbox.setAttribute('aria-hidden', 'false');
  };

  const closeLightbox = () => {
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    lightbox.setAttribute('aria-hidden', 'true');
  };

  fullBtn.addEventListener('click', openLightbox);
  main.addEventListener('click', openLightbox);
  lightboxClose.addEventListener('click', closeLightbox);
  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) closeLightbox();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLightbox();
  });
})();
</script>
