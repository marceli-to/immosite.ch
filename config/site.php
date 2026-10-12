<?php

/*
|--------------------------------------------------------------------------
| Projekt
|--------------------------------------------------------------------------
|
| Alles, was pro Projekt wechselt und keine Design-Frage ist: Name, Texte
| fuer Meta-Tags, Vermarkter-Adresse, Schriftquelle und die Seitenliste.
| Farben, Schriften und Groessen stehen als Tokens in resources/css/app.css.
|
*/

return [

    'name' => env('APP_NAME', 'Immosite'),

    // Titel der Startseite und Fallback, wenn eine Seite keinen eigenen setzt.
    'title' => 'Immosite – Wohnen an bester Lage',

    'description' => 'Neubauprojekt mit Mietwohnungen an bester Lage. Jetzt informieren und vormerken lassen.',

    // Liegt in public/, 1200 × 630 px.
    'og_image' => 'img/og-image.jpg',
    'og_image_alt' => 'Visualisierung des Neubauprojekts',

    // Browserleiste auf Mobile. Gleicher Wert wie --color-surface-alt.
    'theme_color' => '#f2f2f0',

    // Stylesheet der Webfonts (Adobe Fonts / Typekit). Leer lassen, wenn die
    // Schriften selbst gehostet werden; die Datenschutzerklaerung blendet den
    // Abschnitt zu Adobe Fonts dann aus.
    'fonts' => [
        'stylesheet' => null, // z.B. 'https://use.typekit.net/xxxxxxx.css'
    ],

    // Vermarktung: erscheint im Footer und im Impressum.
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

    // Hosting fuer die Datenschutzerklaerung.
    'hosting' => [
        'name' => 'webkeeper GmbH, 8604 Volketswil, Schweiz',
        'privacy_url' => 'https://www.webkeeper.ch/de/unternehmen/datenschutz',
    ],

    /*
    |--------------------------------------------------------------------------
    | Seiten
    |--------------------------------------------------------------------------
    |
    | Eine Liste fuer Hauptnavigation, Footer-Links und /sitemap.xml. Die Route
    | selbst steht in routes/web.php. Module tragen ihre Seiten hier mit einer
    | Zeile ein (markiert mit // module:<name>).
    |
    | nav:     erscheint in der Hauptnavigation
    | footer:  erscheint in der Footer-Leiste (rechtliche Seiten)
    | sitemap: [changefreq, priority] oder null fuer "nicht in der Sitemap"
    |
    */

    'pages' => [
        ['route' => 'page.home', 'title' => 'Projekt', 'nav' => true, 'footer' => false, 'sitemap' => ['weekly', '1.0']],
        ['route' => 'page.disclaimer', 'title' => 'Disclaimer', 'nav' => false, 'footer' => true, 'sitemap' => ['yearly', '0.3']], // module:disclaimer
        ['route' => 'page.imprint', 'title' => 'Impressum', 'nav' => false, 'footer' => true, 'sitemap' => ['yearly', '0.3']],
        ['route' => 'page.privacy', 'title' => 'Datenschutz', 'nav' => false, 'footer' => true, 'sitemap' => ['yearly', '0.3']],
    ],

];
