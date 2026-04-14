<?php

declare(strict_types=1);

/**
 * Application configuration.
 *
 * base_url: Full site origin with no trailing slash (e.g. https://www.example.com).
 *   Improves Open Graph / canonical URLs in production; leave null to detect from the request.
 */
return [
    /**
     * When false, PHP will not print errors to the page (recommended on live servers).
     * Override locally with `'debug' => true` or environment variable APP_DEBUG=1.
     */
    'debug' => filter_var(getenv('APP_DEBUG') ?: '', FILTER_VALIDATE_BOOLEAN),

    'app_name' => 'Mario Motors',
    'app_title_suffix' => 'Mario Motors & Spare Parts',
    'app_email' => 'info@mariomotorspares.co.za',

    /**
     * SMTP settings for PHPMailer (used by contact form).
     * Prefer environment variables on production servers.
     */
    'smtp_host' => getenv('SMTP_HOST') ?: 'mail.mariomotorspares.co.za',
    'smtp_port' => (int) (getenv('SMTP_PORT') ?: 587),
    'smtp_username' => getenv('SMTP_USERNAME') ?: 'noreply@mariomotorspares.co.za',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: 'aKaRmaZMhb2PvCJWN2sz',
    'smtp_encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls', // tls|ssl|none
    'smtp_from_email' => getenv('SMTP_FROM_EMAIL') ?: 'noreply@mariomotorspares.co.za',
    'smtp_from_name' => getenv('SMTP_FROM_NAME') ?: 'Mario Motors Website',

    /**
     * Contact form advertiser/spam filter phrases.
     * If a message matches these patterns, submission is silently ignored.
     *
     * @var list<string>
     */
    'contact_block_keywords' => [
        'seo services',
        'digital marketing',
        'google ranking',
        'backlink',
        'guest post',
        'sponsored post',
        'paid promotion',
        'lead generation',
        'social media management',
        'facebook ads',
        'marketing agency',
        'increase your traffic',
        'increase website traffic',
        'website design services',
        'we can promote your business',
    ],

    /**
     * Contact form allowlist phrases for genuine customer intent.
     * If these phrases are present, submissions are not blocked by ad filter.
     *
     * @var list<string>
     */
    'contact_allow_keywords' => [
        'price',
        'quote',
        'part',
        'spare',
        'availability',
        'car',
        'vehicle',
        'battery',
        'engine',
        'brakes',
        'suspension',
        'order',
        'stock',
        'vin',
        'mm code',
    ],

    'base_url' => null,

    /**
     * Navbar, footer, favicon & social preview.
     * File must be in `public/assets/` (e.g. `public/assets/logo.png`).
     */
    'logo_path' => 'logo.png',

    'business_phone' => '+27123040937',
    'business_phone_display' => '012 304 0937',
    'business_mobile' => '+27623974149',
    'business_mobile_display' => '062 397 4149',
    'whatsapp_number' => '27623974149',

    /** First message in WhatsApp when using the floating chat (optional). */
    'chat_whatsapp_prefill' => 'Hi Mario Motors, I have a question: ',

    'business_address' => [
        'street' => '702 Paul Kruger Street',
        'suburb' => 'Mayville',
        'city' => 'Pretoria',
        'region' => 'Gauteng',
        'postal' => '0082',
        'country' => 'ZA',
    ],

    'services_highlight' => [
        'We buy & sell used cars',
        'Spare parts',
        'New & used batteries',
    ],

    /** Public catalog page size (cars and parts). */
    'listing_per_page' => 12,

    /**
     * Business working hours shown on contact and footer.
     *
     * @var list<array{label: string, value: string}>
     */
    'business_hours' => [
        ['label' => 'Monday to Friday', 'value' => '8am to 5pm'],
        ['label' => 'Saturday', 'value' => '8:30am to 2pm'],
        ['label' => 'Public holidays', 'value' => '8:30am to 2pm'],
    ],

    'seo_default_description' => 'Mario Motors & Spare Parts — we buy and sell used cars, spare parts, and new & used batteries in Mayville, Pretoria. Visit us on Paul Kruger Street or browse online.',

    /**
     * Default meta keywords (comma-separated in HTML). Edit here; major search engines may ignore this tag but it still helps some tools and regional crawlers.
     *
     * @var list<string>|string
     */
    'seo_keywords' => [
        'Mario Motors',
        'Mario Motors spare parts',
        'used cars Pretoria',
        'second hand cars Pretoria',
        'buy used car Gauteng',
        'sell used car Pretoria',
        'car spares Pretoria',
        'auto parts Mayville',
        'spare parts Paul Kruger Street',
        'car batteries Pretoria',
        'new car battery',
        'used car battery',
        'Toyota spares',
        'Ford spares',
        'VW parts',
        'Nissan parts',
        'Hyundai parts',
        'Kia parts',
        'Mazda parts',
        'Chevrolet parts',
        'Opel parts',
        'Suzuki parts',
        'Mahindra spares',
        'Haval GWM',
        'Datsun parts',
        'automotive dealer Pretoria',
        'Mayville motors',
        'mariomotorspares',
    ],

    /** Public website (shown in hero bar). */
    'public_website' => 'https://www.mariomotorspares.co.za',
    'public_website_display' => 'www.mariomotorspares.co.za',

    /**
     * Social profile URLs (https only). Empty string = hidden. WhatsApp icon also appears when `whatsapp_number` is set.
     *
     * @var array<string, string>
     */
    'social_links' => [
        'facebook' => '',
        'instagram' => '',
        'tiktok' => '',
        'youtube' => '',
        'x' => '',
        'linkedin' => '',
    ],

    /**
     * Optional wide PNG/JPG in public/assets/ (e.g. brand strip). Empty string = no image, text badges only.
     */
    'brands_banner_image' => '',

    /**
     * Brands we stock / work with (matches your flyer).
     *
     * @var list<string>
     */
    'featured_car_brands' => [
        'Ford', 'Volkswagen', 'Mazda', 'Mahindra', 'Kia', 'Nissan', 'Haval',
        'Chevrolet', 'GWM', 'Opel', 'Hyundai', 'Suzuki', 'Toyota', 'Datsun',
    ],

    /**
     * Home page “parts” strip: category tiles (must match part `category` in catalog). Images: public/assets/part-categories/{image}.
     *
     * @var list<array{title: string, category: string, image: string, blurb: string}>
     */
    'home_part_category_tiles' => [
        ['title' => 'Brakes', 'category' => 'Brakes', 'image' => 'brakes.png', 'blurb' => 'Pads, discs & brake kits'],
        ['title' => 'Engine', 'category' => 'Engine', 'image' => 'engine.png', 'blurb' => 'Filters, intakes & engine parts'],
        ['title' => 'Exhaust', 'category' => 'Exhaust', 'image' => 'exhaust.png', 'blurb' => 'Systems, tips & performance'],
        ['title' => 'Lighting', 'category' => 'Lighting', 'image' => 'lighting.png', 'blurb' => 'Bulbs, LEDs & assemblies'],
    ],

    /**
     * Optional full URL prefix for assets, e.g. `https://cdn.example.com/stuff` or path `/shop/assets`.
     */
    'assets_base_url' => null,

    /**
     * Optional URL path to your `public` folder (no leading/trailing slash), e.g. `Mario Motors/public`.
     * Leave null: computed from DOCUMENT_ROOT + PUBLIC_PATH (fixes styling when routes are rewritten).
     */
    'public_url_path' => null,
];
