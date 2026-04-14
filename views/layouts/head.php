<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? config('app_name');
$appName = config('app_name');
$siteTitle = (string) config('app_title_suffix', $appName);
$fullTitle = $pageTitle . ' — ' . $siteTitle;
$defaultDesc = (string) config('seo_default_description', '');
$metaDescription = isset($metaDescription) && is_string($metaDescription) && $metaDescription !== ''
    ? $metaDescription
    : $defaultDesc;
$metaKeywordsRaw = $metaKeywords ?? null;
$metaKeywordsContent = seo_keywords_content($metaKeywordsRaw);
$canonicalUrl = canonical_request_url();
$logoFile = site_logo_filename();
$hasSiteLogo = has_site_logo();
$ogImageUrl = $hasSiteLogo ? absolute_asset_url($logoFile) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= esc($metaDescription) ?>">
  <?php if ($metaKeywordsContent !== ''): ?>
  <meta name="keywords" content="<?= esc($metaKeywordsContent) ?>">
  <?php endif; ?>
  <title><?= esc($fullTitle) ?></title>
  <link rel="canonical" href="<?= esc($canonicalUrl) ?>">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= esc($siteTitle) ?>">
  <meta property="og:title" content="<?= esc($fullTitle) ?>">
  <meta property="og:description" content="<?= esc($metaDescription) ?>">
  <meta property="og:url" content="<?= esc($canonicalUrl) ?>">
  <?php if ($hasSiteLogo && $ogImageUrl !== ''): ?>
  <meta property="og:image" content="<?= esc($ogImageUrl) ?>">
  <meta property="og:image:type" content="image/png">
  <?php endif; ?>
  <meta property="og:locale" content="en_US">

  <meta name="twitter:card" content="<?= $hasSiteLogo && $ogImageUrl !== '' ? 'summary_large_image' : 'summary' ?>">
  <meta name="twitter:title" content="<?= esc($fullTitle) ?>">
  <meta name="twitter:description" content="<?= esc($metaDescription) ?>">
  <?php if ($hasSiteLogo && $ogImageUrl !== ''): ?>
  <meta name="twitter:image" content="<?= esc($ogImageUrl) ?>">
  <?php endif; ?>

  <?php if ($hasSiteLogo): ?>
  <link rel="icon" href="<?= esc(logo_url()) ?>" type="image/png">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= esc(asset('app.css')) ?>">
</head>
<body class="bg-background text-foreground font-sans antialiased border-border">
