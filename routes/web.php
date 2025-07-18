<?php

use App\Http\Controllers\admin\KriteriaController;
use App\Http\Controllers\admin\PerhitunganController;
use App\Http\Controllers\admin\SubkriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\developer\RumahController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NasabahController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {

    // Jika user sudah login
    if (Auth::check()) {
        $role = Auth::user()->role;
        $user = Auth::user();
        // Redirect berdasarkan role
        return redirect()->route(match ($role) {
            1 => 'admin.dashboard',
            2 => 'developer.dashboard',
            default => 'home',
        })->with('success', 'Sudah Login sebagai ' . $user->name);
    }
    return Inertia::render('Welcome');
})->name('home');


// Admin route group
Route::middleware(['auth', 'role:1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    })->group(
        function () {
            Route::prefix('nasabah')
                ->name('nasabah.')
                ->group(function () {

                    // Lihat semua data
                    Route::get('/', [NasabahController::class, 'index'])->name('index');
                    Route::put('/{nasabah}', [NasabahController::class, 'updateKelengkapan'])->name('update-kelengkapan');
                });
        }
    )->group(
        function () {
            Route::prefix('kriteria')
                ->name('kriteria.')
                ->group(function () {

                    Route::get('/', [KriteriaController::class, 'index'])->name('index');

                    // Tambah data
                    Route::get('/create', [KriteriaController::class, 'create'])->name('create');
                    Route::post('/', [KriteriaController::class, 'store'])->name('store');

                    // Edit data tanpa ID di URL
                    Route::post('/edit/init', [KriteriaController::class, 'editInit'])->name('edit.init');
                    Route::get('/edit', [KriteriaController::class, 'edit'])->name('edit');
                    Route::put('/{kriteria}', [KriteriaController::class, 'update'])->name('update');


                    // Hapus data
                    Route::delete('/{kriteria}', [KriteriaController::class, 'destroy'])->name('destroy');
                });

            Route::prefix('subkriteria')
                ->name('subkriteria.')
                ->group(function () {

                    // Lihat semua data
                    Route::get('/', [SubkriteriaController::class, 'index'])->name('index');

                    // Tambah data
                    Route::get('/create', [SubkriteriaController::class, 'create'])->name('create');
                    Route::post('/', [SubkriteriaController::class, 'store'])->name('store');

                    // Edit data tanpa ID di URL
                    Route::post('/edit/init', [SubkriteriaController::class, 'editInit'])->name('edit.init');
                    Route::get('/edit', [SubkriteriaController::class, 'edit'])->name('edit');
                    Route::put('/{subkriteria}', [SubkriteriaController::class, 'update'])->name('update');

                    // Hapus data
                    Route::delete('/{subkriteria}', [SubkriteriaController::class, 'destroy'])->name('destroy');
                });

            Route::prefix('subkriteria')
                ->name('subkriteria.')
                ->group(function () {

                    // Lihat semua data
                    Route::get('/', [SubkriteriaController::class, 'index'])->name('index');

                    // Tambah data
                    Route::get('/create', [SubkriteriaController::class, 'create'])->name('create');
                    Route::post('/', [SubkriteriaController::class, 'store'])->name('store');

                    // Edit data tanpa ID di URL
                    Route::post('/edit/init', [SubkriteriaController::class, 'editInit'])->name('edit.init');
                    Route::get('/edit', [SubkriteriaController::class, 'edit'])->name('edit');
                    Route::put('/{subkriteria}', [SubkriteriaController::class, 'update'])->name('update');

                    // Hapus data
                    Route::delete('/{subkriteria}', [SubkriteriaController::class, 'destroy'])->name('destroy');
                });

            Route::prefix('perhitungan')
                ->name('perhitungan.')
                ->group(function () {
                    // Lihat semua data
                    Route::get('/', [PerhitunganController::class, 'index'])->name('index');
                });


        }
    );


// Developer route group
Route::middleware(['auth', 'role:2'])
    ->prefix('developer')
    ->name('developer.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Nasabah Routes
        Route::prefix('nasabah')
            ->name('nasabah.')
            ->group(function () {
                // Lihat semua data
                Route::get('/', [NasabahController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [NasabahController::class, 'create'])->name('create');
                Route::post('/', [NasabahController::class, 'store'])->name('store');

                // Tambah kolom dan delete
                Route::post('/add-column', [NasabahController::class, 'addColumn'])->name('add-column');
                Route::post('/remove-column', [NasabahController::class, 'removeColumn'])->name('remove-column');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [NasabahController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [NasabahController::class, 'edit'])->name('edit');
                Route::post('/{nasabah}', [NasabahController::class, 'update'])->name('update');


                // Hapus data
                Route::delete('/{nasabah}', [NasabahController::class, 'destroy'])->name('destroy');
            });

        Route::prefix('rumah')
            ->name('rumah.')
            ->group(function () {

                // Lihat semua data
                Route::get('/', [RumahController::class, 'index'])->name('index');

                // Tambah data
                Route::get('/create', [RumahController::class, 'create'])->name('create');
                Route::post('/', [RumahController::class, 'store'])->name('store');

                // Edit data tanpa ID di URL
                Route::post('/edit/init', [RumahController::class, 'editInit'])->name('edit.init');
                Route::get('/edit', [RumahController::class, 'edit'])->name('edit');
                Route::put('/{rumah}', [RumahController::class, 'update'])->name('update');

                // Hapus data
                Route::delete('/{rumah}', [RumahController::class, 'destroy'])->name('destroy');
            });
    });



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
