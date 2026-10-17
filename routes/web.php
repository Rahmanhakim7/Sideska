<?php

use App\Http\Controllers\Surat\SuratPdfController;
use App\Livewire\Admin\AuditLog\Index as AdminAuditLogIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Pengguna\CreateKepalaDesa as AdminPenggunaCreateKepalaDesa;
use App\Livewire\Admin\Pengguna\CreateMasyarakat as AdminPenggunaCreateMasyarakat;
use App\Livewire\Admin\Pengguna\CreatePerangkatDesa as AdminPenggunaCreatePerangkatDesa;
use App\Livewire\Admin\Pengguna\Detail as AdminPenggunaDetail;
use App\Livewire\Admin\Pengguna\Index as AdminPenggunaIndex;
use App\Livewire\KepalaDesa\Dashboard as KepalaDesaDashboard;
use App\Livewire\KepalaDesa\DetailPersetujuan;
use App\Livewire\KepalaDesa\Mutasi\Index as KepalaDesaMutasiIndex;
use App\Livewire\KepalaDesa\Mutasi\Show as KepalaDesaMutasiShow;
use App\Livewire\KepalaDesa\Persetujuan;
use App\Livewire\Masyarakat\AjukanSurat;
use App\Livewire\Masyarakat\AjukanSurat\Domisili;
use App\Livewire\Masyarakat\AjukanSurat\KeteranganPindah;
use App\Livewire\Masyarakat\AjukanSurat\Skck;
use App\Livewire\Masyarakat\AjukanSurat\Sktm;
use App\Livewire\Masyarakat\AjukanSurat\Usaha;
use App\Livewire\Masyarakat\Dashboard as MasyarakatDashboard;
use App\Livewire\Masyarakat\DetailPengajuan;
use App\Livewire\Masyarakat\RiwayatPengajuan;
use App\Livewire\Notifikasi;
use App\Livewire\PerangkatDesa\BuatSurat;
use App\Livewire\PerangkatDesa\Dashboard as PerangkatDesaDashboard;
use App\Livewire\PerangkatDesa\DetailPelayananSurat;
use App\Livewire\PerangkatDesa\Mutasi\Index as MutasiIndex;
use App\Livewire\PerangkatDesa\Mutasi\Show as MutasiShow;
use App\Livewire\PerangkatDesa\MutasiKeluar\Create as MutasiKeluarCreate;
use App\Livewire\PerangkatDesa\MutasiMasuk\Create as MutasiMasukCreate;
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
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.dashboard');

Route::get('/admin/pengguna', AdminPenggunaIndex::class)
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.pengguna');

Route::get('/admin/pengguna/masyarakat/create', AdminPenggunaCreateMasyarakat::class)
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.pengguna.masyarakat.create');

Route::get('/admin/pengguna/perangkat-desa/create', AdminPenggunaCreatePerangkatDesa::class)
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.pengguna.perangkat-desa.create');

Route::get('/admin/pengguna/kepala-desa/create', AdminPenggunaCreateKepalaDesa::class)
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.pengguna.kepala-desa.create');

Route::get('/admin/pengguna/{user}', AdminPenggunaDetail::class)
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.pengguna.show');

Route::get('/admin/audit-log', AdminAuditLogIndex::class)
    ->middleware(['auth', 'active', 'role:admin'])
    ->name('admin.audit-log');

Route::get('/perangkat-desa/dashboard', PerangkatDesaDashboard::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.dashboard');
Route::get('/perangkat-desa/penduduk', PendudukIndex::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.index');
Route::get('/perangkat-desa/mutasi-masuk', MutasiMasukCreate::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.mutasi-masuk.create');
Route::get('/perangkat-desa/mutasi', MutasiIndex::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.mutasi.index');
Route::get('/perangkat-desa/mutasi/{mutasiPenduduk}', MutasiShow::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.mutasi.show');
Route::get('/perangkat-desa/penduduk/{penduduk}/mutasi-keluar', MutasiKeluarCreate::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.mutasi-keluar.create');
Route::get('/perangkat-desa/penduduk/create', PendudukCreate::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.create');
Route::get('/perangkat-desa/penduduk/{penduduk}/show', PendudukShow::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.show');
Route::get('/perangkat-desa/penduduk/{penduduk}/edit', PendudukEdit::class)
    ->middleware(['auth', 'active', 'role:perangkat_desa'])
    ->name('perangkat-desa.penduduk.edit');
Route::get('/perangkat-desa/pelayanan-surat', PelayananSurat::class)
    ->middleware(['auth', 'active', 'verified', 'role:perangkat_desa'])
    ->name('perangkat-desa.pelayanan-surat');
Route::get('/perangkat-desa/pelayanan-surat/{pengajuan}', DetailPelayananSurat::class)
    ->middleware(['auth', 'active', 'verified', 'role:perangkat_desa'])
    ->name('perangkat-desa.pelayanan-surat.detail');
Route::get('/perangkat-desa/pelayanan-surat/{pengajuan}/buat', BuatSurat::class)
    ->middleware(['auth', 'active', 'verified', 'role:perangkat_desa'])
    ->name('perangkat-desa.pelayanan-surat.buat');
Route::get(
    '/perangkat-desa/pelayanan-surat/{pengajuan}/pdf',
    [SuratPdfController::class, 'show']
)
    ->middleware(['auth', 'active', 'verified', 'role:perangkat_desa,kepala_desa,masyarakat'])
    ->name('perangkat-desa.pelayanan-surat.pdf');

Route::get(
    '/perangkat-desa/pelayanan-surat/{pengajuan}/pdf/download',
    [SuratPdfController::class, 'download']
)
    ->middleware(['auth', 'active', 'verified', 'role:perangkat_desa,kepala_desa,masyarakat'])
    ->name('perangkat-desa.pelayanan-surat.pdf.download');

Route::get('/kepala-desa/dashboard', KepalaDesaDashboard::class)
    ->middleware(['auth', 'active', 'role:kepala_desa'])
    ->name('kepala.dashboard');
Route::get('/kepala-desa/persetujuan', Persetujuan::class)
    ->middleware(['auth', 'active', 'role:kepala_desa'])
    ->name('kepala.persetujuan');
Route::get('/kepala-desa/persetujuan/{pengajuan}', DetailPersetujuan::class)
    ->middleware(['auth', 'active', 'role:kepala_desa'])
    ->name('kepala.persetujuan.detail');
Route::get('/kepala-desa/mutasi', KepalaDesaMutasiIndex::class)
    ->middleware(['auth', 'active', 'role:kepala_desa'])
    ->name('kepala.mutasi.index');
Route::get('/kepala-desa/mutasi/{mutasiPenduduk}', KepalaDesaMutasiShow::class)
    ->middleware(['auth', 'active', 'role:kepala_desa'])
    ->name('kepala.mutasi.show');

Route::get('/masyarakat/dashboard', MasyarakatDashboard::class)
    ->middleware(['auth', 'active', 'verified', 'role:masyarakat'])
    ->name('dashboard');
Route::middleware(['auth', 'active', 'role:masyarakat'])->group(function () {
    Route::get('/masyarakat/ajukan-surat', AjukanSurat::class)
        ->name('masyarakat.surat.create');
    Route::get('/masyarakat/ajukan-surat/domisili/{pengajuan?}', Domisili::class)
        ->name('masyarakat.ajukan-surat.domisili');
    Route::get('/masyarakat/ajukan-surat/sktm/{pengajuan?}', Sktm::class)
        ->name('masyarakat.ajukan-surat.sktm');
    Route::get('/masyarakat/ajukan-surat/usaha/{pengajuan?}', Usaha::class)
        ->name('masyarakat.ajukan-surat.usaha');
    Route::get('/masyarakat/ajukan-surat/skck/{pengajuan?}', Skck::class)
        ->name('masyarakat.ajukan-surat.skck');
    Route::get('/masyarakat/ajukan-surat/keterangan-pindah/{pengajuan?}', KeteranganPindah::class)
        ->name('masyarakat.ajukan-surat.keterangan-pindah');
    Route::get('/masyarakat/riwayat-pengajuan', RiwayatPengajuan::class)
        ->name('masyarakat.surat.riwayat');
    Route::get('/masyarakat/detail-pengajuan/{pengajuan}', DetailPengajuan::class)
        ->name('masyarakat.surat.detail');
});

Route::get('/notifikasi', Notifikasi::class)
    ->middleware(['auth', 'active'])
    ->name('notifikasi');

Route::view('profile', 'profile')
    ->middleware(['auth', 'active'])
    ->name('profile');
require __DIR__.'/auth.php';
