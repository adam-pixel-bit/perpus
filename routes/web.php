<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\User\BookCatalogController as UserBookCatalogController;
use App\Http\Controllers\User\BorrowingController as UserBorrowingController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
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

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Notifications (Authenticated)
Route::middleware('auth')->group(function () {
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

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
});

// Member / User Routes (Role: user)
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Book Catalog & Detail
    Route::get('/catalog', [UserBookCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{book:slug}', [UserBookCatalogController::class, 'show'])->name('catalog.show');

    // Borrowings
    Route::get('/borrowings', [UserBorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings', [UserBorrowingController::class, 'store'])->name('borrowings.store');
    Route::delete('/borrowings/{borrowing}/cancel', [UserBorrowingController::class, 'cancel'])->name('borrowings.cancel');
});
