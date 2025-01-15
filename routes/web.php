<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HakaksesController;
use Illuminate\Support\Facades\Auth;

// Route::get('/welcome', function () {
//     return view('welcome');
// });
Route::get('/', function () {

    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [HomeController::class, 'blank'])->name('blank');

    Route::get('/menu/surat-masuk', [HomeController::class, 'suratMasuk'])->name('menu.surat-masuk');
    Route::get('/menu/surat-disposisi', [HomeController::class, 'suratDisposisi'])->name('menu.surat-disposisi');
    Route::get('/menu/surat-keluar-perintah', [HomeController::class, 'suratKeluarPerintah'])->name('menu.surat-keluar-perintah');



    Route::get('/hakakses', [HakaksesController::class, 'index'])->name('hakakses.index')->middleware('superadmin');
    Route::get('/hakakses/edit/{id}', [HakaksesController::class, 'edit'])->name('hakakses.edit')->middleware('superadmin');
    Route::put('/hakakses/update/{id}', [HakaksesController::class, 'update'])->name('hakakses.update')->middleware('superadmin');
    Route::delete('/hakakses/delete/{id}', [HakaksesController::class, 'destroy'])->name('hakakses.delete')->middleware('superadmin');
});


Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/hakakses/create', [HakaksesController::class, 'create'])->name('hakakses.create');
    Route::post('/hakakses/store', [HakaksesController::class, 'store'])->name('hakakses.store');
});