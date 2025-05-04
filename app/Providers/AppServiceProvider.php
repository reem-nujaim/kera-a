<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminController; // إضافة استيراد الـ Controller
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // إضافة الميدل وير للمسارات التي تحتاج إلى صلاحيات الأدمن
        Route::middleware([AdminMiddleware::class])->group(function () {
            Route::get('/admin', [AdminController::class, 'index']);

            // أضيفي هنا المسارات الأخرى التي تحتاج إلى صلاحيات الأدمن
        });
    }
}
