<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Domisili</title>
    <style>
        @page {
            margin: 15mm 20mm 15mm 25mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
        }

        .kop {
            position: relative;
            min-height: 92px;
            border-bottom: 3px solid #000;
            padding-bottom: 7px;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
            line-height: 1.05;
            padding-top: 1px;
        }

        .kabupaten {
            font-size: 13pt;
            font-weight: bold;
        }

        .kecamatan {
            font-size: 12pt;
            font-weight: bold;
        }

        .desa {
            font-size: 14pt;
            font-weight: bold;
        }

        .alamat {
            font-size: 9pt;
            margin-top: 3px;
        }

        .judul {
            text-align: center;
            margin-top: 15px;
        }

        .judul-surat {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .nomor {
            margin-top: 1px;
            font-size: 10pt;
        }

        .isi {
            margin-top: 15px;
            text-align: justify;
        }

        .paragraf {
            margin: 0 0 8px 0;
        }

        .data {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 8px 0;
        }

        .data td {
            vertical-align: top;
            padding: 1px 0;
            line-height: 1.35;
        }

        .data .label {
            width: 145px;
            white-space: nowrap;
        }

        .data .titik {
            width: 15px;
            padding: 0;
        }

        .data .nilai {
            padding-left: 0;
        }

        .penutup {
            margin: 0 0 8px 0;
            text-align: justify;
        }

        .ttd {
            width: 230px;
            margin-left: auto;
            margin-top: 18px;
            text-align: center;
            page-break-inside: avoid;
        }

        .tanggal {
            margin-bottom: 2px;
        }

        .jabatan {
            margin-bottom: 2px;
        }

        .ruang-ttd {
            height: 55px;
        }

        .ttd-gambar {
            width: 85px;
            height: auto;
        }

        .nama {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="kop">
        <img src="file://{{ public_path('images/logo-purworejo-pdf.jpg') }}" class="logo" alt="">
        <div class="kop-text">
            <div class="kabupaten">
                PEMERINTAH KABUPATEN PURWOREJO
            </div>

            <div class="kecamatan">
                KECAMATAN PITURUH
            </div>

            <div class="desa">
                DESA SEKARTEJO
            </div>

            <div class="alamat">
                Sekartejo, Kecamatan Pituruh, Kabupaten Purworejo,
                Jawa Tengah 54263
            </div>
        </div>

    </div>

    <div class="judul">

        <div class="judul-surat">
            SURAT KETERANGAN DOMISILI
        </div>

        <div class="nomor">
            Nomor: {{ $pengajuan->nomor_surat ?? '-' }}
        </div>

    </div>

    <div class="isi">

        <p class="paragraf">
            Yang bertanda tangan di bawah ini, Kepala Desa Sekartejo,
            Kecamatan Pituruh, Kabupaten Purworejo, menerangkan bahwa:
        </p>

        <table class="data">

            <tr>
                <td class="label">Nama</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->nama ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">NIK</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->nik ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Nomor KK</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->no_kk ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->tempat_lahir ?? '-' }},
                    {{ $pengajuan->penduduk->tanggal_lahir
                        ? \Carbon\Carbon::parse($pengajuan->penduduk->tanggal_lahir)->translatedFormat('d F Y')
                        : '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->jenis_kelamin ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Agama</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->agama ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Pekerjaan</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->pekerjaan ?? '-' }}
                </td>
            </tr>

            <tr>
                <td class="label">Alamat</td>
                <td class="titik">:</td>
                <td class="nilai">
                    {{ $pengajuan->penduduk->alamat ?? '-' }},
                    RT {{ $pengajuan->penduduk->rt ?? '-' }}/RW {{ $pengajuan->penduduk->rw ?? '-' }},
                    Dusun {{ $pengajuan->penduduk->dusun ?? '-' }},
                    Desa Sekartejo, Kecamatan Pituruh, Kabupaten Purworejo
                </td>
            </tr>

        </table>

        <p class="penutup">
            Berdasarkan data kependudukan yang tercatat pada Pemerintah
            Desa Sekartejo, yang bersangkutan benar berdomisili dan
            bertempat tinggal di Desa Sekartejo, Kecamatan Pituruh,
            Kabupaten Purworejo.
        </p>

        <p class="penutup">
            Surat keterangan ini dibuat berdasarkan data kependudukan
            yang ada pada Pemerintah Desa Sekartejo dan diberikan kepada
            yang bersangkutan untuk dipergunakan sebagaimana mestinya.
        </p>

        <p class="penutup">
            Demikian surat keterangan ini dibuat dengan sebenar-benarnya
            untuk dapat dipergunakan sebagaimana mestinya.
        </p>

    </div>

    <div class="ttd">

        <div class="tanggal">
            Sekartejo,
            {{ $pengajuan->tanggal_surat
                ? \Carbon\Carbon::parse($pengajuan->tanggal_surat)->translatedFormat('d F Y')
                : '-' }}
        </div>

        <div class="jabatan">
            Kepala Desa Sekartejo
        </div>

        <div class="ruang-ttd">

            @if ($sudahDitandatangani ?? false)
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/ttdkades.png'))) }}"
                    class="ttd-gambar" alt="">
            @endif

        </div>

        <div class="nama">
            PUJIYANTO
        </div>

    </div>

</body>

</html>
