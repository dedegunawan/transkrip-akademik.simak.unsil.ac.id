<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([\App\Http\Middleware\AuthSimakMiddleware::class])->group(function () {
    Route::get('/', 'TranskripController@index')->name('transkrip_index');

    Route::get('/viewer{link?}.pdf', 'PdfViewer@index')->name('pdf_viewer');

    Route::post('/', 'TranskripController@doFilter')->name('transkrip_filter');

    Route::any('/opsi-print', 'TranskripController@opsiPrint')->name('opsi_print');
    Route::post('/reset-opsi-print', 'TranskripController@resetOpsiPrint')->name('reset_opsi_print');

    Route::any('/transkrip.pdf', 'TranskripController@generateTranskrip')->name('generate_transkrip');
    Route::any('/transkrip-preview.pdf', 'TranskripController@generateTranskripPreview')
        ->name('generate_transkrip_preview');

    Route::any('/load-transkrip', 'TranskripController@loadTranskrip')->name('load_transkrip');

    Route::get('/generate-nomor-transkrip.html', 'TranskripController@generateNomorTranskrip')->name('generate_nomor_transkrip');
    Route::post('/generate-nomor-transkrip.html', 'TranskripController@doGenerateNomorTranskrip')->name('do_generate_nomor_transkrip');


    Route::post('/generate-nomor-transkrip-by-npm.html', 'TranskripController@doGenerateNomorTranskripByRequestNpm')
        ->name('do_generate_nomor_transkrip_by_npm');



});

Route::get('auth', 'SimakAuthController@check');

