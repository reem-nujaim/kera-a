<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\changePasswordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\RentalRequestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\NotificationController ;
use App\Http\Controllers\Api\UserReportController ;
use App\Http\Controllers\Api\BillController;


// Route::get('/user/{id}', [UserController::class, 'show']);
// Route::get('/users/{id}/items', [UserController::class, 'getUserItems']);

Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);

Route::get('/report', [UserReportController::class, 'report']);
Route::get('/bill', [BillController::class, 'getAllBills']);
Route::get('/user/{userId}/bills', [BillController::class, 'getUserBills']);
Route::middleware('auth:sanctum')->get('/user/verification-status', [AuthController::class, 'getVerificationStatus']);





Route::middleware('auth:sanctum')->get('/notifications', [NotificationController::class, 'getUserNotifications']);
// في ملف routes/api.php
Route::middleware('auth:api')->get('/reports/stats', [UserReportController::class, 'getStats']);




Route::get('/items/{item}/rental_requests', [RentalRequestController::class, 'getItemRentalRequests']);

Route::get('/user/{id}/rental-requests', [RentalRequestController::class, 'getUserRentalRequests']);
Route::get('items/{itemId}/reserved-dates', [RentalRequestController::class, 'getReservedDates']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class , 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [ProfileController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/user/update', [ProfileController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->patch('/account/status', [UserController::class, 'updateAccountStatus']);
Route::middleware('auth:sanctum')->post('/deactivate-account', [UserController::class, 'deactivateAccount']);
Route::middleware('auth:sanctum')->post('/change-password', [changePasswordController::class, 'changePassword']);
 Route::post('/verify-account/{id}', [AuthController::class, 'verifyAccount']);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/verify-account/{id}', [AuthController::class, 'verifyAccount']);
// });

Route::post('password/forgot', [ForgotPasswordController::class, 'sendPasswordResetLink']);
Route::post('password/reset', [ForgotPasswordController::class, 'resetPassword']);





Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']); // جلب كل الفئات
    Route::post('/', [CategoryController::class, 'store']); // إنشاء فئة جديدة
    Route::get('/{id}', [CategoryController::class, 'show']); // جلب بيانات فئة واحدة
    Route::put('/{id}', [CategoryController::class, 'update']); // تحديث بيانات فئة
    Route::delete('/{id}', [CategoryController::class, 'destroy']); // حذف فئة
    Route::get('/items/{item}/rental_requests', [RentalRequestController::class, 'getItemRentalRequests']);
    Route::get('/user/{id}/rental-requests', [RentalRequestController::class, 'getUserRentalRequests']);
    Route::get('items/{itemId}/reserved-dates', [RentalRequestController::class, 'getReservedDates']);
});






Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::post('/', [ItemController::class, 'store']);
    Route::get('/{id}', [ItemController::class, 'show']);
    Route::put('/{id}', [ItemController::class, 'update']);
    Route::delete('/{id}', [ItemController::class, 'destroy']);
});
Route::get('/items/category/{categoryId}', [ItemController::class, 'getItemsByCategory']);
Route::get('/items/user/{userId}', [ItemController::class, 'getItemsByUser']);
Route::get('/search-items', [ItemController::class, 'searchItems']);









Route::prefix('rental-requests')->group(function () {
Route::post('/', [RentalRequestController::class, 'store']);
Route::get('/', [RentalRequestController::class, 'index']);
Route::get('/{id}', [RentalRequestController::class, 'show']);
Route::put('/{id}', [RentalRequestController::class, 'update']);
Route::delete('/{id}', [RentalRequestController::class, 'destroy']);
Route::post('/{id}/status', [RentalRequestController::class, 'updateRentalRequestStatus']);
Route::post('/{id}/add-transaction', [RentalRequestController::class, 'submitTransactionNumber']);
Route::post('/{id}/approve', [RentalRequestController::class, 'reviewAvailability']);


});



Route::prefix('ratings')->group(function () {
    Route::get('/', [RatingController::class, 'index']);
    Route::post('/', [RatingController::class, 'store']);
    Route::get('/{id}', [RatingController::class, 'show']);
    Route::put('/{id}', [RatingController::class, 'update']);
    Route::delete('/{id}', [RatingController::class, 'destroy']);
});




Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/unread', [NotificationController::class, 'unread']);
Route::put('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
Route::put('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
Route::delete('/notifications', [NotificationController::class, 'destroyAll']);



