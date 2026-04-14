<?php

declare(strict_types=1);

/** @var bool $sent */
/** @var list<array<string, mixed>> $info */
?>
<div class="min-h-screen bg-background flex flex-col">
  <div class="max-w-[1400px] mx-auto pt-24 pb-16 px-4 flex-1 w-full" data-reveal>
    <h1 class="font-heading text-4xl md:text-5xl font-bold uppercase tracking-wider text-foreground mb-2">
      Contact <span class="text-primary">Us</span>
    </h1>
    <p class="text-muted-foreground mb-12">Have a question? We'd love to hear from you.</p>

    <?php if ($sent): ?>
      <div class="mb-8 rounded-md border border-primary/30 bg-card px-4 py-3 text-foreground" role="status">
        <strong class="font-heading uppercase tracking-wider">Message sent</strong>
        <p class="text-muted-foreground text-sm mt-1">We'll get back to you within 24 hours.</p>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <form method="post" action="contact" class="space-y-5">
        <input name="name" required placeholder="Your Name"
          class="w-full h-12 px-4 rounded-md border border-border bg-card text-foreground placeholder:text-muted-foreground">
        <input type="email" name="email" required placeholder="Email Address"
          class="w-full h-12 px-4 rounded-md border border-border bg-card text-foreground placeholder:text-muted-foreground">
        <input name="phone" placeholder="Phone Number (optional)"
          class="w-full h-12 px-4 rounded-md border border-border bg-card text-foreground placeholder:text-muted-foreground">
        <textarea name="message" required rows="6" placeholder="Your Message"
          class="w-full px-4 py-3 rounded-md border border-border bg-card text-foreground placeholder:text-muted-foreground"></textarea>
        <button type="submit" class="w-full h-12 rounded-md bg-primary text-primary-foreground font-heading uppercase tracking-wider text-lg transition-all duration-200 hover:opacity-90 hover:shadow-md active:scale-[0.99]">
          Send Message
        </button>
      </form>

      <div class="space-y-6">
        <?php foreach ($info as $item): ?>
          <?php
          $icon = (string) ($item['icon'] ?? '');
          $label = (string) ($item['label'] ?? '');
          $value = (string) ($item['value'] ?? '');
          $linkUrl = isset($item['link_url']) && is_string($item['link_url']) && $item['link_url'] !== '' ? $item['link_url'] : null;
          $external = ! empty($item['external']);
          $mapsUrl = isset($item['maps_url']) && is_string($item['maps_url']) && $item['maps_url'] !== '' ? $item['maps_url'] : null;
          ?>
          <div class="flex items-start gap-4 bg-card border border-border rounded-lg p-5">
            <?php if ($icon !== ''): ?>
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0 text-xl" aria-hidden="true"><?= esc($icon) ?></div>
            <?php endif; ?>
            <div class="min-w-0 flex-1">
              <h3 class="font-heading text-lg font-semibold text-foreground"><?= esc($label) ?></h3>
              <?php if ($label === 'Address'): ?>
                <p class="text-muted-foreground mt-1 whitespace-pre-line"><?= esc($value) ?></p>
                <?php if ($mapsUrl !== null): ?>
                  <a href="<?= esc($mapsUrl) ?>" class="mt-2 inline-block text-sm font-medium text-primary underline-offset-4 hover:underline" target="_blank" rel="noopener noreferrer">Open in Maps</a>
                <?php endif; ?>
              <?php elseif ($linkUrl !== null): ?>
                <a href="<?= esc($linkUrl) ?>" class="mt-1 inline-block font-medium text-primary underline decoration-primary/50 underline-offset-2 hover:opacity-90" <?= $external ? 'target="_blank" rel="noopener noreferrer"' : '' ?>><?= esc($value) ?></a>
              <?php else: ?>
                <p class="text-muted-foreground mt-1 whitespace-pre-line"><?= esc($value) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <?php
        $waUrl = $waUrl ?? '';
        $socialIconsWrapperClass = 'mt-10 rounded-xl border border-border bg-card p-6';
        $socialIconsHeading = 'Social media';
        require VIEW_PATH . '/partials/social-icon-links.php';
        ?>
      </div>
    </div>
  </div>
</div>
