<?php

use App\Http\Controllers\Surat\SuratPdfController;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\KepalaDesa\Dashboard as KepalaDesaDashboard;
use App\Livewire\KepalaDesa\DetailPersetujuan;
use App\Livewire\KepalaDesa\Persetujuan;
use App\Livewire\Masyarakat\AjukanSurat;
use App\Livewire\Masyarakat\AjukanSurat\Domisili;
use App\Livewire\Masyarakat\AjukanSurat\Sktm;
use App\Livewire\Masyarakat\AjukanSurat\Usaha;
use App\Livewire\Masyarakat\Dashboard as MasyarakatDashboard;
use App\Livewire\Masyarakat\RiwayatPengajuan;
use App\Livewire\PerangkatDesa\BuatSurat;
use App\Livewire\PerangkatDesa\Dashboard as PerangkatDesaDashboard;
use App\Livewire\PerangkatDesa\DetailPelayananSurat;
use App\Livewire\PerangkatDesa\PelayananSurat;
use App\Livewire\PerangkatDesa\Penduduk\Create as PendudukCreate;
use App\Livewire\PerangkatDesa\Penduduk\Edit as PendudukEdit;
use App\Livewire\PerangkatDesa\Penduduk\Index as PendudukIndex;
use App\Livewire\PerangkatDesa\Penduduk\Show as PendudukShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/admin/dashboard', AdminDashboard::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::get('/perangkat-desa/dashboard', PerangkatDesaDashboard::class)
    ->middleware(['auth', 'role:perangkat_desa'])
    ->name('perangkat-desa.dashboard');
Route::get('/perangkat-desa/penduduk', PendudukIndex::class)
    ->middleware(['auth', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.index');
Route::get('/perangkat-desa/penduduk/create', PendudukCreate::class)
    ->middleware(['auth', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.create');
Route::get('/perangkat-desa/penduduk/{penduduk}/show', PendudukShow::class)
    ->middleware(['auth', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.show');
Route::get('/perangkat-desa/penduduk/{penduduk}/edit', PendudukEdit::class)
    ->middleware(['auth', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.edit');
Route::get('/perangkat-desa/pelayanan-surat', PelayananSurat::class)
    ->middleware(['auth', 'verified', 'role:perangkat_desa'])
    ->name('perangkat-desa.pelayanan-surat');
Route::get('/perangkat-desa/pelayanan-surat/{pengajuan}', DetailPelayananSurat::class)
    ->middleware(['auth', 'verified', 'role:perangkat_desa'])
    ->name('perangkat-desa.pelayanan-surat.detail');
Route::get('/perangkat-desa/pelayanan-surat/{pengajuan}/buat', BuatSurat::class)
    ->middleware(['auth', 'verified', 'role:perangkat_desa'])
    ->name('perangkat-desa.pelayanan-surat.buat');
Route::get(
    '/perangkat-desa/pelayanan-surat/{pengajuan}/pdf',
    [SuratPdfController::class, 'show']
)
    ->middleware(['auth', 'verified', 'role:perangkat_desa,kepala_desa,masyarakat'])
    ->name('perangkat-desa.pelayanan-surat.pdf');

Route::get(
    '/perangkat-desa/pelayanan-surat/{pengajuan}/pdf/download',
    [SuratPdfController::class, 'download']
)
    ->middleware(['auth', 'verified', 'role:perangkat_desa,kepala_desa,masyarakat'])
    ->name('perangkat-desa.pelayanan-surat.pdf.download');

Route::get('/kepala-desa/dashboard', KepalaDesaDashboard::class)
    ->middleware(['auth', 'role:kepala_desa'])
    ->name('kepala.dashboard');
Route::get('/kepala-desa/persetujuan', Persetujuan::class)
    ->middleware(['auth', 'role:kepala_desa'])
    ->name('kepala.persetujuan');
Route::get('/kepala-desa/persetujuan/{pengajuan}', DetailPersetujuan::class)
    ->middleware(['auth', 'role:kepala_desa'])
    ->name('kepala.persetujuan.detail');

Route::get('/masyarakat/dashboard', MasyarakatDashboard::class)
    ->middleware(['auth', 'verified', 'role:masyarakat'])
    ->name('dashboard');
Route::middleware(['auth', 'role:masyarakat'])->group(function () {
    Route::get('/masyarakat/ajukan-surat', AjukanSurat::class)
        ->name('masyarakat.surat.create');
    Route::get('/masyarakat/ajukan-surat/domisili/{pengajuan?}', Domisili::class)
        ->name('masyarakat.ajukan-surat.domisili');
    Route::get('/masyarakat/ajukan-surat/sktm/{pengajuan?}', Sktm::class)
        ->name('masyarakat.ajukan-surat.sktm');
    Route::get('/masyarakat/ajukan-surat/usaha/{pengajuan?}', Usaha::class)
        ->name('masyarakat.ajukan-surat.usaha');
    Route::get('/masyarakat/riwayat-pengajuan', RiwayatPengajuan::class)
        ->name('masyarakat.surat.riwayat');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
require __DIR__.'/auth.php';
