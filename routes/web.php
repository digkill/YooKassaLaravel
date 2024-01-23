<?php

use Illuminate\Support\Facades\Route;
use Digkill\YooKassaLaravel\Http\Middleware\IpAccess;

Route::group(['middleware' => [IpAccess::class]], function () {
    Route::namespace('Digkill\YooKassaLaravel\Http\Controllers')->group(function () {
        Route::post('/yookassa/notifications', 'NotificationController@index')->name('yookassa.notifications');
    });
});
