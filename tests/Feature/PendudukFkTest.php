<?php

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\QueryException;

it('menolak penghapusan akun selama masih memiliki data penduduk', function () {
    $user = User::factory()->create();
    $penduduk = Penduduk::factory()->create(['user_id' => $user->id]);

    expect(fn () => $user->delete())
        ->toThrow(QueryException::class);

    expect(Penduduk::find($penduduk->id))->not->toBeNull();
    expect(User::find($user->id))->not->toBeNull();
});

it('mengizinkan akun dihapus setelah data penduduk dihapus lebih dahulu', function () {
    $user = User::factory()->create();
    $penduduk = Penduduk::factory()->create(['user_id' => $user->id]);

    $penduduk->delete();
    $user->delete();

    expect(Penduduk::find($penduduk->id))->toBeNull();
    expect(User::find($user->id))->toBeNull();
});
