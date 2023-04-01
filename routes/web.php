<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider and all of them will
  | be assigned to the "web" middleware group. Make something great!
  |
 */

// Route::get('/', function () {
//     return view('layout/adm-page');
// });

/**
 * Rota Raiz
 */
Route::prefix('/')->middleware(['auth.not-employee'])->group(function () {
    // LoginController
    Route::controller(\App\Http\Controllers\LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'authenticate')->name('authenticate');
    });
});

/**
 * Rotas adm
 */
Route::prefix('adm')->middleware(['auth.employee'])->group(function () {
    // HomeController
    Route::controller(\App\Http\Controllers\Adm\HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('adm.home');
    });
    /**
     * Rotas adm/storage
     */
    Route::prefix('storage')->group(function () {
        // TypeProductController
        Route::controller(\App\Http\Controllers\Adm\Storage\TypeProductController::class)->group(function () {
            Route::get('type-product/list', 'listSearch')->name('type-product.list-search');
            Route::get('type-product/create', 'create')->name('type-product.create');
            Route::post('type-product/insert', 'insert')->name('type-product.insert');
            Route::get('type-product/{id}/edit', 'edit')->name('type-product.edit');
            Route::post('type-product/update', 'update')->name('type-product.update');
            Route::get('type-product/{id}/remove', 'remove')->name('type-product.remove');
            Route::get('type-product/{id}/delete', 'delete')->name('type-product.delete');
        });
        // ProductController
        Route::controller(\App\Http\Controllers\Adm\Storage\ProductController::class)->middleware(['type-product.exists'])->group(function () {
            Route::get('product/list', 'listSearch')->name('product.list-search');
            Route::get('product/create', 'create')->name('product.create');
            Route::post('product/insert', 'insert')->name('product.insert');
            Route::get('product/{id}/edit', 'edit')->name('product.edit');
            Route::post('product/update', 'update')->name('product.update');
            Route::get('product/{id}/remove', 'remove')->name('product.remove');
            Route::get('product/{id}/delete', 'delete')->name('product.delete');
        });
        // StorageController
        Route::controller(\App\Http\Controllers\Adm\Storage\StorageController::class, 'index')->middleware(['product.exists'])->group(function () {
            Route::get('storage/list', 'listSearch')->name('storage.list-search');
            Route::get('storage/{id}/adding', 'adding')->name('storage.adding');
            Route::post('storage/add', 'add')->name('storage.add');
            Route::get('storage/{id}/remove', 'remove')->name('storage.remove');
            Route::get('storage/{id}/delete', 'delete')->name('storage.delete');
        });
    });
    /**
     * Rotas adm/service
     */
    Route::prefix('service')->middleware(['client.exists'])->group(function () {
        // Rota order
        Route::prefix('order')->group(function () {
            // OrderController
            Route::controller(\App\Http\Controllers\Adm\Services\OrderController::class)->group(function () {
                Route::get('list', 'index')->name('order.list');
                Route::get('searching-client', 'searchingClient')->name('order.searching-client');
                Route::get('{id}/register', 'register')->name('order.register');
                Route::get('{id}/confirm', 'confirm')->name('order.confirm');
                Route::get('{id}/finish', 'finishOrder')->name('order.finish');
                Route::get('{id}/confirmCancel', 'confirmCancel')->name('order.confirm-cancel');
                Route::get('{id}/cancel', 'cancelOrder')->name('order.cancel-order');
            });
            // OrderCartController
            Route::controller(\App\Http\Controllers\Adm\Services\OrderCartController::class)->group(function () {
                Route::get('{id}/cart/show', 'showCart')->name('order.show-cart');
                Route::get('{id}/cart/searching-item', 'searchingItem')->name('order.searching-item');
                Route::get('{id}/cart/add-item', 'addItem')->name('order.add-item');
                Route::get('{id}/cart/remove-item', 'removeItem')->name('order.remove-item');
            });
        });
    });
    /**
     * Rotas adm/rh
     */
    Route::prefix('rh')->group(function () {
        // ClientController
        Route::controller(\App\Http\Controllers\Adm\Rh\ClientController::class)->group(function () {
            Route::get('client/list', 'listSearch')->name('client.list-search');
            Route::get('client/create', 'create')->name('client.create');
            Route::post('cliente/insert', 'insert')->name('client.insert');
            Route::get('client/{id}/edit', 'edit')->name('client.edit');
            Route::post('client/update', 'update')->name('client.update');
            Route::get('client/{id}/remove', 'remove')->name('client.remove');
            Route::get('client/{id}/delete', 'delete')->name('client.delete');
        });
        // EmployeeController
        Route::controller(\App\Http\Controllers\Adm\Rh\EmployeeController::class)->group(function () {
            Route::get('employee/list', 'listSearch')->name('employee.list-search');
            Route::get('employee/create', 'create')->name('employee.create');
            Route::post('employee/inset', 'insert')->name('employee.insert');
            Route::get('employee/{id}/edit', 'edit')->name('employee.edit');
            Route::post('employee/{id}/changeStatus', 'changeStatus')->name('employee.changeStatus');
            Route::get('employee/{id}/remove', 'remove')->name('employee.remove');
            Route::post('employee/{id}/delete', 'delete')->name('employee.delete');
            Route::get('employee/logout', 'logout')->name('employee.logout');
        });
    });
});
/**
 * Rota Activation
 */
Route::prefix('activation')->group(function () {
    // EmployeeActivationController
    Route::controller(\App\Http\Controllers\Activation\EmployeeActivationController::class)->group(function () {
        Route::get('{hash}/employee', 'start')->name('employee.active');
        Route::get('resend', 'resendActivation')->name('activation.resend');
        Route::post('send', 'sendActivation')->name('activation.send');
        Route::get('password', 'createPassword')->name('activation.create-password');
        Route::post('password', 'setPassword')->name('activation.set-password');
    });
});
