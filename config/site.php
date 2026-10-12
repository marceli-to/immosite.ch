<?php

/*
|--------------------------------------------------------------------------
| Project
|--------------------------------------------------------------------------
|
| Everything that changes per project and isn't a design question: name, texts
| for meta tags, marketer address, font source and the page list.
| Colours, fonts and sizes live as tokens in resources/css/app.css.
|
*/

return [

    'name' => env('APP_NAME', 'Immosite'),

    // Title of the home page and fallback if a page doesn't set its own.
    'title' => 'Immosite – Wohnen an bester Lage',

    'description' => 'Neubauprojekt mit Mietwohnungen an bester Lage. Jetzt informieren und vormerken lassen.',

    // Lives in public/, 1200 × 630 px.
    'og_image' => 'img/og-image.jpg',
    'og_image_alt' => 'Visualisierung des Neubauprojekts',

    // Browser bar on mobile. Same value as --color-surface-alt.
    'theme_color' => '#f2f2f0',

    // Stylesheet of the web fonts (Adobe Fonts / Typekit). Leave empty if the
    // fonts are self-hosted; the privacy policy then hides the
    // Adobe Fonts section.
    'fonts' => [
        'stylesheet' => null, // e.g. 'https://use.typekit.net/xxxxxxx.css'
    ],

    // Marketing agency: shown in the footer and in the imprint.
    'marketer' => [
        'name' => 'Muster Immobilien AG',
        'street' => 'Musterstrasse 1',
        'city' => '8000 Zürich',
        'contact' => 'Frau Erika Muster',
        'role' => 'Vermarktung',
        'email' => 'vermietung@example.ch',
        'phone' => '+41 44 000 00 00',
    ],

    'credits' => [
        'design' => ['name' => 'Stoz Werbeagentur AG', 'street' => 'Barzloostrasse 2', 'city' => '8330 Pfäffikon ZH', 'email' => 'hello@stoz.ch', 'url' => 'https://www.stoz.ch', 'label' => 'design by stoz'],
        'development' => ['name' => 'Marcel Stadelmann, Zürich', 'url' => 'https://marceli.to'],
    ],

    // Hosting, for the privacy policy.
    'hosting' => [
        'name' => 'webkeeper GmbH, 8604 Volketswil, Schweiz',
        'privacy_url' => 'https://www.webkeeper.ch/de/unternehmen/datenschutz',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | One list for the main navigation, footer links and /sitemap.xml. The route
    | itself lives in routes/web.php. Modules add their pages here with one
    | line (marked with // module:<name>).
    |
    | nav:     shown in the main navigation
    | footer:  shown in the footer bar (legal pages)
    | sitemap: [changefreq, priority] or null for "not in the sitemap"
    |
    */

    'pages' => [
        ['route' => 'page.home', 'title' => 'Projekt', 'nav' => true, 'footer' => false, 'sitemap' => ['weekly', '1.0']],
        ['route' => 'page.apartments', 'title' => 'Wohnungen', 'nav' => true, 'footer' => false, 'sitemap' => ['daily', '0.9']], // module:apartments
        ['route' => 'page.location', 'title' => 'Lage', 'nav' => true, 'footer' => false, 'sitemap' => ['monthly', '0.8']], // module:pages-full
        ['route' => 'page.faq', 'title' => 'FAQ', 'nav' => true, 'footer' => false, 'sitemap' => ['monthly', '0.6']], // module:faq
        ['route' => 'page.contact', 'title' => 'Kontakt', 'nav' => true, 'footer' => false, 'sitemap' => ['monthly', '0.8']], // module:pages-full
        ['route' => 'page.disclaimer', 'title' => 'Disclaimer', 'nav' => false, 'footer' => true, 'sitemap' => ['yearly', '0.3']], // module:disclaimer
        ['route' => 'page.imprint', 'title' => 'Impressum', 'nav' => false, 'footer' => true, 'sitemap' => ['yearly', '0.3']],
        ['route' => 'page.privacy', 'title' => 'Datenschutz', 'nav' => false, 'footer' => true, 'sitemap' => ['yearly', '0.3']],
    ],

];
