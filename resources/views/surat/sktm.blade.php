<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 15mm 20mm 15mm 25mm;
        }
        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .kop {
            position: relative;
            text-align: center;
            min-height: 88px;
            border-bottom: 3px solid #000;
            padding-bottom: 6px;
        }
        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 65px;
            height: 65px;
        }
        .kop-text {
            line-height: 1.05;
            padding-top: 2px;
        }
        .kop-text .kabupaten {
            font-size: 13pt;
            font-weight: bold;
        }
        .kop-text .kecamatan {
            font-size: 12pt;
            font-weight: bold;
        }
        .kop-text .desa {
            font-size: 14pt;
            font-weight: bold;
        }
        .kop-text .alamat {
            font-size: 9pt;
            margin-top: 2px;
        }
        .judul {
            text-align: center;
            margin-top: 15px;
        }
        .judul .nama-surat {
            font-weight: bold;
            text-decoration: underline;
            font-size: 13pt;
        }
        .judul .nomor {
            margin-top: 1px;
            font-size: 10pt;
        }
        .isi {
            margin-top: 15px;
            text-align: justify;
        }
        .isi p {
            margin-top: 0;
            margin-bottom: 8px;
        }
        .identitas {
            margin-top: 6px;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        .identitas table {
            width: 100%;
            border-collapse: collapse;
        }
        .identitas td {
            vertical-align: top;
            padding: 1px 0;
        }
        .identitas .label {
            width: 145px;
        }
        .identitas .titik {
            width: 15px;
        }
        .pengantar-orang-tua {
            margin-top: 7px;
            margin-bottom: 5px;
        }
        .orang-tua {
            margin-top: 3px;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        .orang-tua table {
            width: 100%;
            border-collapse: collapse;
        }
        .orang-tua td {
            vertical-align: top;
            padding: 1px 0;
        }
        .orang-tua .label {
            width: 145px;
        }
        .orang-tua .titik {
            width: 15px;
        }
        .penutup {
            margin-top: 8px;
            text-align: justify;
        }
        .ttd {
            margin-top: 18px;
            width: 230px;
            margin-left: auto;
            text-align: center;
            page-break-inside: avoid;
        }
        .ttd .jabatan {
            margin-bottom: 2px;
        }
        .ttd .nama {
            margin-top: 42px;
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
                PEMERINTAH KABUPATEN PURWOREJO saya
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
        <div class="nama-surat">
            SURAT KETERANGAN DOMISILI
        </div>
        <div class="nomor">
            Nomor: {{ $pengajuan->nomor_surat ?? '-' }}
        </div>
    </div>
    <div class="isi">
        <p>
            Yang bertanda tangan di bawah ini, Kepala Desa Sekartejo,
            Kecamatan Pituruh, Kabupaten Purworejo, menerangkan bahwa:
        </p>
        <div class="identitas">
            <table>
                <tr>
                    <td class="label">
                        Nama
                    </td>
                    <td class="titik">
                        :
                    </td>
                    <td>
                        {{ $pengajuan->penduduk->nama }}
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        NIK
                    </td>
                    <td class="titik">
                        :
                    </td>
                    <td>
                        {{ $pengajuan->penduduk->nik }}
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Nomor KK
                    </td>
                    <td class="titik">
                        :
                    </td>
                    <td>
                        {{ $pengajuan->penduduk->no_kk }}
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Tempat, Tanggal Lahir
                    </td>
                    <td class="titik">
                        :
                    </td>
                    <td>
                        {{ $pengajuan->penduduk->tempat_lahir }},
                        {{ $pengajuan->penduduk->tanggal_lahir?->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Jenis Kelamin
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        {{ $pengajuan->penduduk->jenis_kelamin }}
                    </td>
                </tr>


                <tr>
                    <td class="label">
                        Alamat
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        {{ $pengajuan->penduduk->alamat }},
                        RT {{ $pengajuan->penduduk->rt }}/RW {{ $pengajuan->penduduk->rw }},
                        Dusun {{ $pengajuan->penduduk->dusun }},
                        Desa Sekartejo, Kecamatan Pituruh,
                        Kabupaten Purworejo
                    </td>
                </tr>

            </table>

        </div>

        <p class="pengantar-orang-tua">
            Adapun data orang tua dari yang bersangkutan adalah sebagai berikut:
        </p>

        <div class="orang-tua">

            <table>

                <tr>
                    <td class="label">
                        Nama Ayah
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        {{ $pengajuan->penduduk->nama_ayah ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Pekerjaan Ayah
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        {{ $dataSktm['pekerjaan_ayah'] ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">
                        Penghasilan Ayah
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        @if (!empty($dataSktm['penghasilan_ayah']))
                            Rp {{ $dataSktm['penghasilan_ayah'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Nama Ibu
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        {{ $pengajuan->penduduk->nama_ibu ?? '-' }}
                    </td>
                </tr>


                <tr>
                    <td class="label">
                        Pekerjaan Ibu
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        {{ $dataSktm['pekerjaan_ibu'] ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        Penghasilan Ibu
                    </td>

                    <td class="titik">
                        :
                    </td>

                    <td>
                        @if (!empty($dataSktm['penghasilan_ibu']))
                            Rp {{ $dataSktm['penghasilan_ibu'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>

            </table>

        </div>
        <p>
            Berdasarkan data dan keterangan yang ada pada Pemerintah Desa
            Sekartejo, yang bersangkutan benar merupakan warga Desa
            Sekartejo dan termasuk dalam keluarga yang kurang mampu.
        </p>

        <p>
            Surat Keterangan Tidak Mampu ini dibuat untuk keperluan:
            <strong>{{ $pengajuan->keperluan }}</strong>.
        </p>


        <div class="penutup">

            Demikian Surat Keterangan Tidak Mampu ini dibuat dengan
            sebenarnya untuk dapat dipergunakan sebagaimana mestinya.

        </div>

    </div>

    <div class="ttd">

        <div class="jabatan">

            Sekartejo,
            {{ $pengajuan->tanggal_surat?->translatedFormat('d F Y') }}

        </div>

        <div>
            Kepala Desa Sekartejo
        </div>

        <div class="nama">
            PUJIYANTO
        </div>

    </div>

</body>

</html>
