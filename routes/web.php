<?php

use App\Http\Controllers\ContactSubmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/process', 'process')->name('process');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactSubmissionController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/work', [WorkController::class, 'index'])->name('work');
Route::get('/work/{slug}', [WorkController::class, 'show'])->name('work.show');

Route::get('/journal', [JournalController::class, 'index'])->name('journal');
Route::get('/journal/{slug}', [JournalController::class, 'show'])->name('journal.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::view('noor-te-nakhra', 'noor-te-nakhra')->name('noor-te-nakhra');