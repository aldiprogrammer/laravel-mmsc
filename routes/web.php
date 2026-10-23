<?php

use App\Http\Controllers\Admin\BolaController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataUserController;
use App\Http\Controllers\Admin\FotograferController;
use App\Http\Controllers\Admin\KarirController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\OpenKarirTeamController;
use App\Http\Controllers\Admin\OpenSoloController;
use App\Http\Controllers\Admin\RompiController;
use App\Http\Controllers\Admin\VideograferController;
use App\Http\Controllers\Admin\WasitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

Route::prefix('admin')->name('admin.')->middleware('admin.context')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan');

    Route::get('/wasit', [WasitController::class, 'index'])->name('wasit');
    Route::post('/wasit', [WasitController::class, 'store'])->name('wasit.store');
    Route::put('/wasit/{wasit}', [WasitController::class, 'update'])->name('wasit.update');
    Route::delete('/wasit/{wasit}', [WasitController::class, 'destroy'])->name('wasit.destroy');
    Route::patch('/wasit/{wasit}/toggle', [WasitController::class, 'toggle'])->name('wasit.toggle');

    Route::get('/rompi', [RompiController::class, 'index'])->name('rompi');
    Route::post('/rompi', [RompiController::class, 'store'])->name('rompi.store');
    Route::put('/rompi/{rompi}', [RompiController::class, 'update'])->name('rompi.update');
    Route::delete('/rompi/{rompi}', [RompiController::class, 'destroy'])->name('rompi.destroy');
    Route::patch('/rompi/{rompi}/toggle', [RompiController::class, 'toggle'])->name('rompi.toggle');

    Route::get('/fotografer', [FotograferController::class, 'index'])->name('fotografer');
    Route::post('/fotografer', [FotograferController::class, 'store'])->name('fotografer.store');
    Route::put('/fotografer/{fotografer}', [FotograferController::class, 'update'])->name('fotografer.update');
    Route::delete('/fotografer/{fotografer}', [FotograferController::class, 'destroy'])->name('fotografer.destroy');
    Route::patch('/fotografer/{fotografer}/toggle', [FotograferController::class, 'toggle'])->name('fotografer.toggle');

    Route::get('/bola', [BolaController::class, 'index'])->name('bola');
    Route::post('/bola', [BolaController::class, 'store'])->name('bola.store');
    Route::put('/bola/{bola}', [BolaController::class, 'update'])->name('bola.update');
    Route::delete('/bola/{bola}', [BolaController::class, 'destroy'])->name('bola.destroy');
    Route::patch('/bola/{bola}/toggle', [BolaController::class, 'toggle'])->name('bola.toggle');

    Route::get('/videografer', [VideograferController::class, 'index'])->name('videografer');
    Route::post('/videografer', [VideograferController::class, 'store'])->name('videografer.store');
    Route::put('/videografer/{videografer}', [VideograferController::class, 'update'])->name('videografer.update');
    Route::delete('/videografer/{videografer}', [VideograferController::class, 'destroy'])->name('videografer.destroy');
    Route::patch('/videografer/{videografer}/toggle', [VideograferController::class, 'toggle'])->name('videografer.toggle');

    Route::prefix('karir')->name('karir.')->group(function () {
        Route::get('/team-rank', [KarirController::class, 'teamRank'])->name('team-rank');
        Route::get('/team-rank/{team}', [KarirController::class, 'teamRankDetail'])->name('team-rank.detail');

        Route::get('/member', [KarirController::class, 'member'])->name('member');
        Route::post('/member/setujui', [KarirController::class, 'memberSetujui'])->name('member.setujui');
        Route::post('/member/tolak', [KarirController::class, 'memberTolak'])->name('member.tolak');
        Route::post('/member/hapus', [KarirController::class, 'memberHapus'])->name('member.hapus');

        Route::get('/open-solo', [OpenSoloController::class, 'index'])->name('open-solo');
        Route::post('/open-solo', [OpenSoloController::class, 'store'])->name('open-solo.store');
        Route::put('/open-solo/{game}', [OpenSoloController::class, 'update'])->name('open-solo.update');
        Route::delete('/open-solo/{game}', [OpenSoloController::class, 'destroy'])->name('open-solo.destroy');
        Route::patch('/open-solo/{game}/toggle', [OpenSoloController::class, 'toggle'])->name('open-solo.toggle');

        Route::prefix('open')->name('open.')->group(function () {
            Route::get('/', [OpenKarirTeamController::class, 'index'])->name('index');
            Route::get('/cek-booking', [OpenKarirTeamController::class, 'checkBooking'])->name('cek-booking');
            Route::get('/detail', [OpenKarirTeamController::class, 'detail'])->name('detail');
            Route::post('/', [OpenKarirTeamController::class, 'store'])->name('store');
            Route::put('/{game}', [OpenKarirTeamController::class, 'update'])->name('update');
            Route::delete('/{game}', [OpenKarirTeamController::class, 'destroy'])->name('destroy');
            Route::patch('/{game}/toggle', [OpenKarirTeamController::class, 'toggle'])->name('toggle');

            Route::get('/point', [OpenKarirTeamController::class, 'point'])->name('point');
            Route::post('/point/goal', [OpenKarirTeamController::class, 'addGoal'])->name('point.add-goal');
            Route::post('/point/result', [OpenKarirTeamController::class, 'savePointRankTeam'])->name('point.save-result');
            Route::put('/point/goal/{goalId}', [OpenKarirTeamController::class, 'updateGoal'])->whereNumber('goalId')->name('point.update-goal');
            Route::delete('/point/goal/{goalId}', [OpenKarirTeamController::class, 'destroyGoal'])->whereNumber('goalId')->name('point.destroy-goal');

            Route::view('/hasil', 'admin.coming-soon', [
                'section' => 'Hasil Rank Team',
                'sectionSlug' => 'hasil-rank-team',
            ])->name('hasil');

            Route::view('/absensi', 'admin.coming-soon', [
                'section' => 'Absensi Rank Team',
                'sectionSlug' => 'absensi-rank-team',
            ])->name('absensi');
        });
    });

    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/booking/{booking}/approve', [BookingController::class, 'approve'])->name('booking.approve');
    Route::post('/booking/{booking}/complete', [BookingController::class, 'complete'])->name('booking.complete');
    Route::post('/booking/{booking}/reject', [BookingController::class, 'reject'])->name('booking.reject');

    Route::get('/data-user', [DataUserController::class, 'index'])->name('data-user');
    Route::delete('/data-user/{dataUser}', [DataUserController::class, 'destroy'])->name('data-user.destroy');

    $sections = [
        'pemain' => 'Pemain',
        'pertandingan' => 'Pertandingan',
        'pembayaran' => 'Pembayaran',
        'laporan' => 'Laporan',
        'pengaturan' => 'Pengaturan',
    ];

    foreach ($sections as $slug => $label) {
        Route::view("/{$slug}", 'admin.coming-soon', [
            'section' => $label,
            'sectionSlug' => $slug,
        ])->name($slug);
    }

    Route::post('/logout', function (Request $request) {
        $request->session()->forget(['auth_admin_id']);

        return redirect()->route('admin.dashboard');
    })->name('logout');
});
