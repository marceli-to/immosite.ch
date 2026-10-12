<?php

use App\Http\Controllers\ApartmentController; // module:apartments
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('page.home');
Route::view('/lage', 'pages.location')->name('page.location'); // module:pages-full
Route::view('/kontakt', 'pages.contact')->name('page.contact'); // module:pages-full
Route::get('/wohnen', ApartmentController::class)->name('page.apartments'); // module:apartments
Route::view('/bewerben', 'pages.apply')->name('page.apply'); // module:apply
Route::view('/faq', 'pages.faq')->name('page.faq'); // module:faq
Route::view('/ausbau', 'pages.building-spec')->name('page.building-spec'); // module:building-spec

Route::view('/disclaimer', 'pages.disclaimer')->name('page.disclaimer'); // module:disclaimer
Route::view('/danke', 'pages.thanks')->name('page.thanks'); // module:contact-form
Route::view('/impressum', 'pages.imprint')->name('page.imprint');
Route::view('/datenschutz', 'pages.privacy')->name('page.privacy');

// robots.txt comes from the app instead of a static file, so staging stays
// blocked via ROBOTS_NOINDEX=true and a deploy doesn't reset that.
Route::get('/robots.txt', function () {
    $body = config('seo.noindex')
        ? "User-agent: *\nDisallow: /\n"
        : "User-agent: *\nDisallow:\n\nSitemap: ".route('sitemap')."\n";

    return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
})->name('robots');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
