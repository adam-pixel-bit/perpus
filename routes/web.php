<?php

use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BookRequestController as AdminBookRequestController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ScannerController as AdminScannerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\User\BookCatalogController as UserBookCatalogController;
use App\Http\Controllers\User\BookRequestController as UserBookRequestController;
use App\Http\Controllers\User\BorrowingController as UserBorrowingController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPUS Digital
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }

    return redirect()->route('login');
});

// Authentication Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Password Reset Flow
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile & Account Settings (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profile/card', [ProfileController::class, 'memberCard'])->name('profile.card');

    // Printable Circulation Receipt
    Route::get('/borrowings/{borrowing}/receipt', [ReceiptController::class, 'show'])->name('receipt.show');

    // Notifications
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Admin Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Book Management (CRUD)
    Route::resource('books', AdminBookController::class);

    // Category Management (CRUD)
    Route::resource('categories', AdminCategoryController::class)->except(['show', 'create', 'edit']);

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Borrowing & Return Management
    Route::get('/borrowings', [AdminBorrowingController::class, 'index'])->name('borrowings.index');
    Route::patch('/borrowings/{borrowing}/approve', [AdminBorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::patch('/borrowings/{borrowing}/reject', [AdminBorrowingController::class, 'reject'])->name('borrowings.reject');
    Route::patch('/borrowings/{borrowing}/return', [AdminBorrowingController::class, 'returnBook'])->name('borrowings.return');
    Route::post('/borrowings/{borrowing}/pay-fine', [AdminBorrowingController::class, 'payFine'])->name('borrowings.payFine');
    Route::post('/borrowings/run-fines-calculation', [AdminBorrowingController::class, 'runFinesCalculation'])->name('borrowings.runFines');

    // Book Requests from Members
    Route::get('/book-requests', [AdminBookRequestController::class, 'index'])->name('requests.index');
    Route::patch('/book-requests/{bookRequest}/status', [AdminBookRequestController::class, 'updateStatus'])->name('requests.updateStatus');

    // QR & Barcode Scanner Desk
    Route::get('/scanner', [AdminScannerController::class, 'index'])->name('scanner.index');
    Route::get('/scanner/lookup', [AdminScannerController::class, 'lookup'])->name('scanner.lookup');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [AdminReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');

    // Security & Audit Logs
    Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
});

// Member / User Routes (Role: user)
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Book Catalog & Detail
    Route::get('/catalog', [UserBookCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{book:slug}', [UserBookCatalogController::class, 'show'])->name('catalog.show');

    // Book Reviews
    Route::post('/catalog/{book}/reviews', [UserReviewController::class, 'store'])->name('reviews.store');

    // Borrowings & Renewal
    Route::get('/borrowings', [UserBorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings', [UserBorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('/borrowings/{borrowing}/renew', [UserBorrowingController::class, 'renew'])->name('borrowings.renew');
    Route::delete('/borrowings/{borrowing}/cancel', [UserBorrowingController::class, 'cancel'])->name('borrowings.cancel');

    // Book Procurement Requests
    Route::get('/book-requests', [UserBookRequestController::class, 'index'])->name('requests.index');
    Route::post('/book-requests', [UserBookRequestController::class, 'store'])->name('requests.store');
});
