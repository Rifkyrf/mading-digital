<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page (publik)
Route::get('/', [PageController::class, 'landing'])->name('home');

// Detail karya (publik) - GUNAKAN /works/ UNTUK SEMUA
Route::get('/works/{id}', [WorkController::class, 'show'])->name('work.show');
Route::get('/works/{work}/modal', [WorkController::class, 'showModal'])->name('work.modal');
// Route::get('/works/{id}', [WorkController::class, 'showg'])->name('work.showg');

// Like (butuh auth)
Route::post('/works/{work}/like', [LikeController::class, 'toggle'])->name('likes.toggle');

// Pencarian (publik)
Route::get('/search/results', function (\Illuminate\Http\Request $request) {
    $query = $request->get('q', '');
    $users = [];

    if (strlen($query) >= 2) {
        $users = \App\Models\User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('nis', 'like', "%{$query}%")
            ->select('id', 'name', 'profile_photo')
            ->limit(20)
            ->get();
    }

    return view('search.results', compact('users', 'query'));
})->name('search.results');

Route::get('/search/users', [SearchController::class, 'users'])->name('search.users');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (User & Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [WorkController::class, 'index'])->name('dashboard');

    // Upload
    Route::get('/upload', [WorkController::class, 'create'])->name('upload.page');
    Route::post('/upload', [WorkController::class, 'store'])->name('upload.store');
    Route::get('/upload/form', [WorkController::class, 'create'])->name('upload.form.modal');

    // Edit - GUNAKAN /works/ UNTUK KONSISTEN
    Route::get('/works/{work}/edit/form', [WorkController::class, 'editForm'])->name('work.edit.form');
    Route::put('/works/{work}', [WorkController::class, 'update'])->name('work.update');

    // Delete
    Route::delete('/works/{work}', [WorkController::class, 'destroy'])->name('work.destroy');

    // Komentar
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    // Profil
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/work/{id}/edit', [WorkController::class, 'edit'])->name('work.edit');
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});