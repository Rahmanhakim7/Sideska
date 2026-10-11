<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PendudukFotoService
{
    public const FOLDER = 'sideska/penduduk';

    public function upload(string $filePath): string
    {
        $result = Cloudinary::upload($filePath, ['folder' => self::FOLDER]);

        $securePath = $result->getSecurePath();

        if (! is_string($securePath) || $securePath === '') {
            throw new RuntimeException('Cloudinary tidak mengembalikan URL foto yang valid.');
        }

        return $securePath;
    }

    public function hapus(?string $foto): void
    {
        if ($foto === null || $foto === '') {
            return;
        }

        $publicId = $this->ambilPublicIdDariUrl($foto);

        if ($publicId !== null) {
            $this->hapusDariCloudinary($publicId);

            return;
        }

        $this->hapusDariDiskPublik($foto);
    }

    public function ambilPublicIdDariUrl(string $url): ?string
    {
        $parts = parse_url($url);

        if (
            ($parts['scheme'] ?? null) !== 'https'
            || ($parts['host'] ?? null) !== 'res.cloudinary.com'
        ) {
            return null;
        }

        $segments = explode('/', trim($parts['path'] ?? '', '/'));

        if (($segments[0] ?? null) === null || $this->cloudNameAplikasi() !== $segments[0]) {
            return null;
        }

        $posisiUpload = array_search('upload', $segments, true);

        if ($posisiUpload === false) {
            return null;
        }

        $sisa = array_slice($segments, $posisiUpload + 1);

        if ($sisa === []) {
            return null;
        }

        $file = array_pop($sisa);

        $posisiVersi = null;

        foreach ($sisa as $indeks => $segmen) {
            if (preg_match('/^v\d+$/', $segmen)) {
                $posisiVersi = $indeks;
            }
        }

        $bagianPublicId = $posisiVersi !== null
            ? array_slice($sisa, $posisiVersi + 1)
            : $sisa;

        $file = preg_replace('/\.[^.]+$/', '', $file) ?? $file;

        $bagianPublicId[] = $file;

        return implode('/', $bagianPublicId);
    }

    private function cloudNameAplikasi(): ?string
    {
        $cloudName = config('cloudinary.cloud_name');

        if (is_string($cloudName) && $cloudName !== '') {
            return $cloudName;
        }

        $cloudUrl = config('cloudinary.cloud_url');

        if (is_string($cloudUrl) && $cloudUrl !== '') {
            $cloudName = parse_url($cloudUrl, PHP_URL_HOST);

            if (is_string($cloudName) && $cloudName !== '') {
                return $cloudName;
            }
        }

        return null;
    }

    private function hapusDariCloudinary(string $publicId): void
    {
        try {
            $result = Cloudinary::destroy($publicId, ['resource_type' => 'image']);

            if (($result['result'] ?? null) !== 'ok') {
                Log::warning('Gagal menghapus foto lama dari Cloudinary.', [
                    'public_id' => $publicId,
                    'result' => $result,
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal menghapus foto lama dari Cloudinary.', [
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function hapusDariDiskPublik(string $foto): void
    {
        try {
            if (Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal menghapus foto lama dari disk publik.', [
                'foto' => $foto,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
