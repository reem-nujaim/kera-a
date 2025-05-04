<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RentalRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\settingController;
use App\Http\Controllers\LateFeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {
    Route::get('/', function () {
        return view('auth.login');
    });


    // مسار لوحة التحكم للأدمن مع إضافة الـ Middleware
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', AdminMiddleware::class])->name('dashboard');
    Route::get('/customers-stats', [DashboardController::class, 'getCustomersStats'])->name('customersStats');
    Route::get('/top-rated-items', [DashboardController::class, 'getMostOrderedItems'])->name('mostOrderedItems');

    // مسارات الـ Profile للمستخدمين العاديين
    Route::middleware('auth')->group(function () {

        // مسارات الأصناف
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        });
        Route::prefix('/profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/update', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        Route::prefix('/bills')->group(function () {
            Route::get('/', [BillController::class, 'index'])->name('bills.index');
            Route::post('/{id}/update-status', [BillController::class, 'updateStatus'])->name('bills.updateStatus');
            Route::delete('/{id}', [BillController::class, 'destroy'])->name('bills.destroy');
        });
        // عرض بيانات المستخدمين فقط
        Route::prefix('customers')->group(function () {
            // عرض قائمة العملاء
            Route::get('/', [UserController::class, 'index'])->name('customers.index');
            // حذف عميل
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('customers.destroy');
            Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('customers.toggleStatus');
              Route::get('/{id}/verify', [UserController::class, 'showVerificationPage'])->name('customers.verify');
            Route::patch('/{id}/verify', [UserController::class, 'toggleVerification'])->name('customers.toggleVerification');
        });

        Route::prefix('ratings')->group(function () {
            Route::get('/', [RatingController::class, 'index'])->name('ratings.index');
            Route::delete('/{id}', [RatingController::class, 'delete'])->name('ratings.delete');
            Route::patch('/{id}/hide', [RatingController::class, 'hide'])->name('ratings.hide');
        });


        Route::prefix('/setting')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('setting.index');
            Route::get('setting/{id}/edit', [SettingController::class, 'edit'])->name('setting.edit');
            Route::patch('setting/{id}', [SettingController::class, 'update'])->name('setting.update');
        });

        Route::prefix('assurances')->group(function () {
            Route::get('/', [AssuranceController::class, 'index'])->name('assurances.index');
            Route::get('/create', [AssuranceController::class, 'create'])->name('assurances.create');
            Route::post('/store', [AssuranceController::class, 'store'])->name('assurances.store');
            Route::post('/{id}/update-status', [AssuranceController::class, 'updateStatus'])->name('assurances.updateStatus');
            Route::get('/{assurance}/edit', [AssuranceController::class, 'edit'])->name('assurances.edit');
            Route::put('/{assurance}', [AssuranceController::class, 'update'])->name('assurances.update');
            Route::delete('/{assurance}', [AssuranceController::class, 'destroy'])->name('assurances.destroy');
        });

        Route::prefix('items')->group(function () {
            Route::get('/', [ItemController::class, 'index'])->name('items.index');
            Route::delete('/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
            Route::patch('/items/{item}/hide', [ItemController::class, 'hide'])->name('items.hide');
            Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
            // Approve the item
            Route::patch('/{item}/approve', [ItemController::class, 'approve'])->name('item.approve');

            // Route for rejecting the item
            Route::patch('/{item}/reject', [ItemController::class, 'reject'])->name('item.reject');
        });

        Route::prefix('requests')->group(function () {
            Route::get('/', [RentalRequestController::class, 'index'])->name(name: 'requests.index');
            Route::delete('/requests/{id}', [RentalRequestController::class, 'destroy'])->name('requests.destroy');
            Route::get('/requests/{id}', [RentalRequestController::class, 'requestsShow'])->name('requests.requestsShow');
        });

        Route::get('/log', [ActivityLogController::class, 'index'])->name('activity.index');

        // lateFees route
        Route::prefix('/lateFees')->group(function () {
            Route::get('/', [LateFeeController::class, 'index'])->name('lateFees.index');
            Route::post('/{id}/update-status', [LateFeeController::class, 'updateStatus'])->name('lateFees.updateStatus');
        });
        Route::prefix('/notifications')->group(function(){
            Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
            Route::get('/show', [NotificationController::class, 'show'])->name('notifications.show');
            Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
            Route::get('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
            Route::get('/redirect/{id}', [NotificationController::class, 'redirectToNotification'])->name('notifications.redirect');

        });


        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/{type}', [ReportController::class, 'getReport'])->name('reports.get');
        });


        Route::get('/search-suggestions', [SearchController::class, 'search'])->name('search');

    });



    // مسارات لوحة التحكم الخاصة بالأصناف (للمدير فقط)
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories');




    require __DIR__ . '/auth.php';
});