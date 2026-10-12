<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('page.home');

Route::view('/disclaimer', 'pages.disclaimer')->name('page.disclaimer'); // module:disclaimer
Route::view('/danke', 'pages.thanks')->name('page.thanks'); // module:contact-form
Route::view('/impressum', 'pages.imprint')->name('page.imprint');
Route::view('/datenschutz', 'pages.privacy')->name('page.privacy');

// robots.txt kommt aus der App statt als statische Datei, damit Staging per
// ROBOTS_NOINDEX=true gesperrt bleibt und ein Deploy das nicht zuruecksetzt.
Route::get('/robots.txt', function () {
    $body = config('seo.noindex')
        ? "User-agent: *\nDisallow: /\n"
        : "User-agent: *\nDisallow:\n\nSitemap: ".route('sitemap')."\n";

    return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

// Seiten kommen aus config('site.pages'), damit Navigation und Sitemap
// nicht auseinanderlaufen.
Route::get('/sitemap.xml', function () {
    $pages = collect(config('site.pages'))->filter(fn ($page) => $page['sitemap']);

    // Die XML-Deklaration steht hier statt im View: In Blade wuerde "<?" als
    // PHP-Tag gelesen, und Umgehungen wie '<'.'?xml' verwirren den Editor.
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".view('sitemap', ['pages' => $pages])->render();

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->name('sitemap');
