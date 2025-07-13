<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>FR.APL.01 - Permohonan Sertifikasi Kompetensi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .highlight {
            background-color: yellow;
            font-weight: bold;
        }

        td.label {
            width: 30%;
            vertical-align: top;
        }

        td.separator {
            width: 2%;
        }

        td.value {
            width: 68%;
        }
    </style>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .label {
            width: 30%;
            vertical-align: top;
        }

        .separator {
            width: 1%;
            vertical-align: top;
            white-space: nowrap;
        }

        .value {
            width: 69%;
            vertical-align: top;
            word-wrap: break-word;
        }
    </style>

</head>

<body>
    <p class="highlight">FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI</p>

    {{-- {{ $data }} --}}

    {{-- {{ $data->paymentReview->payment->userCertification->user }} --}}
    @php
    $customFields = $data->paymentReview->payment->userCertification->user->custom_fields;
    @endphp

    <p><strong>Bagian 1 : Rincian Data Pemohon Sertifikasi</strong></p>
    <p>Cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini</p>

    <p class="section-title">a. Data Pribadi</p>

    <table>
        <tr>
            <td class="label">Nama lengkap</td>
            <td class="separator">:</td>
            <td class="value">{{ $data->paymentReview->payment->userCertification->user->name ??
                '....................................................' }}</td>
        </tr>
        <tr>
            <td class="label">No. KTP/NIK/Paspor</td>
            <td class="separator">:</td>
            <td class="value">{{ $customFields['nik'] ?? '....................................................' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat / tgl. Lahir</td>
            <td class="separator">:</td>
            <td class="value">
                {{
                ($customFields['tempat_lahir'] ?? '................................') . ' / ' .
                ($customFields['tgl_lahir']
                ? \Carbon\Carbon::parse($customFields['tgl_lahir'])->format('d-m-Y')
                : '................................')
                }}
            </td>
        </tr>
        <tr>
            <td class="label">Jenis kelamin</td>
            <td class="separator">:</td>
            <td class="value">
                @php
                $jk = strtolower($customFields['jenis_kelamin'] ?? '');
                @endphp
                @if ($jk === 'laki-laki')
                Laki-laki / <s>Wanita</s> *))
                @elseif ($jk === 'wanita')
                <s>Laki-laki</s> / Wanita *))
                @else
                Laki-laki / Wanita *))
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Kebangsaan</td>
            <td class="separator">:</td>
            <td class="value">{{ $customFields['kewarganegaraan'] ??
                '....................................................' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat rumah</td>
            <td class="separator">:</td>
            <td class="value">{{ $customFields['alamat'] ?? '....................................................' }}
            </td>
        </tr>
        <tr>
            <td class="label">No. Telepon</td>
            <td class="separator">:</td>
            <td class="value">{{ $customFields['no_wa'] ?? '................................' }}</td>
        </tr>
        <tr>
            <td class="label">E-mail</td>
            <td class="separator">:</td>
            <td class="value">{{ $data->paymentReview->payment->userCertification->user->email ??
                '................................' }}</td>
        </tr>
        <tr>
            <td class="label">Kualifikasi Pendidikan</td>
            <td class="separator">:</td>
            <td class="value">{{ strtoupper($customFields['pendidikan_terakhir'] ??
                '....................................................') }}</td>
        </tr>
    </table>

    <p><em>*Coret yang tidak perlu</em></p>






    <p class="section-title">b. Data Pekerjaan Sekarang</p>
    <table>
        <tr>
            <td class="label">Nama Institusi / Perusahaan</td>
            <td class="separator">:</td>
            <td class="value">{{ $customFields['perusahaan'] ?? '....................................................'
                }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td class="separator">:</td>
            <td class="value">{{ $customFields['jabatan'] ?? '....................................................'
                }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Kantor</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $customFields['alamat_perusahaan'] ?? '....................................................'
                }}<br>
                Kode pos: {{ $customFields['kode_pos_perusahaan'] ??
                '....................................................'
                }}
            </td>
        </tr>
        <tr>
            <td class="label">No. Telp/Fax/E-mail</td>
            <td class="separator">:</td>
            <td class="value">
                Telp: {{ $customFields['telp_perusahaan'] ?? '.....................' }} &nbsp;&nbsp; Fax: {{
                $customFields['fax_perusahaan'] ?? '.....................' }} <br>
                E-mail: {{ $customFields['email_perusahaan'] ??
                '.......................................................' }}
            </td>
        </tr>
    </table>

    <p class="section-title">Bagian 2 : Data Sertifikasi</p>
    <p>
        Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada skema
        sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman
        kerja yang anda miliki.
    </p>

    <!-- Tabel Skema Sertifikasi -->
    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
        <tr>

            @php
            $jenisSkema =
            strtolower($data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->jenis_skema
            ?? '');
            @endphp
            <td rowspan="2" style="width: 25%; vertical-align: top;">
                <strong>Skema Sertifikasi</strong><br>

                @if ($jenisSkema === 'kkni')
                <strong> (KKNI / <s>Okupasi</s> / <s>Klaster</s>) </strong>
                @elseif ($jenisSkema === 'okupasi')
                <strong> (<s>KKNI</s> / Okupasi / <s>Klaster</s>) </strong>
                @elseif ($jenisSkema === 'klaster')
                <strong> (<s>KKNI</s> / <s>Okupasi</s> / Klaster) </strong>
                @else
                <strong> (KKNI / Okupasi / Klaster) </strong>
                @endif
            </td>

            <td style="width: 10%;">Judul</td>
            <td style="width: 65%;">
                <strong>
                    {{
                    $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->judul_skema
                    }}
                </strong>
            </td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>
                <strong>
                    {{
                    $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->nomor_skema
                    }}
                </strong>
            </td>
        </tr>
    </table>

    <!-- Tabel Tujuan Asesmen -->


    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse; margin-top: 0;">
        <tr>
            <td rowspan="4" style="width: 25%; vertical-align: top;">Tujuan Asesmen</td>
            <td style="width: 5%; text-align: center;"><input type="checkbox" {{
                    $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->tujuan_skema
                == 'Sertifikasi' ? 'checked' : '' }} ></td>
            <td>Sertifikasi</td>
        </tr>
        <tr>
            <td style="text-align: center;"><input type="checkbox" {{
                    $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->tujuan_skema
                == 'Pengakuan Kompetensi Terkini (PKT)' ? 'checked' : '' }} ></td>
            <td>Pengakuan Kompetensi Terkini (PKT)</td>
        </tr>
        <tr>
            <td style="text-align: center;"><input type="checkbox" {{
                    $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->tujuan_skema
                == 'Rekognisi Pembelajaran Lampau (RPL)' ? 'checked' : '' }} ></td>
            <td>Rekognisi Pembelajaran Lampau (RPL)</td>
        </tr>
        <tr>
            <td style="text-align: center;"><input type="checkbox" {{
                    $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->tujuan_skema
                == 'Lainnya' ? 'checked' : '' }} ></td>
            <td>Lainnya</td>
        </tr>
    </table>

    <br>


    <p><strong>Daftar Unit Kompetensi sesuai kemasan:</strong></p>

    <style>
        thead {
            display: table-row-group;
            /* Mencegah pengulangan header */
        }
    </style>

    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
        <thead style="background-color: #eee;">
            <tr>
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 20%; text-align: center;">Kode Unit</th>
                <th style="text-align: center;">Judul Unit</th>
                <th style="width: 20%; text-align: center;">Standar Kompetensi Kerja</th>
            </tr>
        </thead>
        <tbody>
            @php
            $units =
            $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->units
            ?? null;
            $jenisSkema =
            $data->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->jenis_skema
            ?? '-';
            @endphp

            @if ($units && $units->count())
            @foreach ($units as $item)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td style="text-align: center;">{{ $item->nomor }}</td>
                <td style="text-align: left;">{{ $item->name }}</td>
                <td style="text-align: center;">{{ $jenisSkema }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4" style="text-align: center; color: #888;">
                    Tidak ada data unit kompetensi.
                </td>
            </tr>
            @endif
        </tbody>
    </table>


    <p class="section-title">Bagian 3 : Bukti Kelengkapan Pemohon</p>

    <p><strong>3.1 Bukti Persyaratan Dasar Pemohon</strong></p>
    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No.</th>
                <th rowspan="2">Bukti Persyaratan Dasar</th>
                <th colspan="3">Ada</th>
            </tr>
            <tr>
                <th style="width: 15%;">Memenuhi Syarat</th>
                <th style="width: 15%;">Tidak Memenuhi Syarat</th>
                <th style="width: 15%;">Tidak Ada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->paymentReview->payment->userCertification->documents
            as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->documentType->name }}</td>
                <td style="text-align: center;"><input type="checkbox" {{ $item->file_path != NULL ? 'checked' : '' }}>
                </td>
                <td style="text-align: center;"><input type="checkbox"></td>
                <td style="text-align: center;"><input type="checkbox"></td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <p><strong>3.2 Bukti Administratif</strong></p>
    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No.</th>
                <th rowspan="2">Bukti Administratif</th>
                <th colspan="3">Ada</th>
            </tr>
            <tr>
                <th style="width: 15%;">Memenuhi Syarat</th>
                <th style="width: 15%;">Tidak Memenuhi Syarat</th>
                <th style="width: 15%;">Tidak Ada</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1.</td>
                <td>Foto copy e-KTP</td>
                <td style="text-align: center;"><input type="checkbox"></td>
                <td style="text-align: center;"><input type="checkbox"></td>
                <td style="text-align: center;"><input type="checkbox"></td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Pas foto 3x4 latar belakang merah sebanyak 3 lembar</td>
                <td style="text-align: center;"><input type="checkbox"></td>
                <td style="text-align: center;"><input type="checkbox"></td>
                <td style="text-align: center;"><input type="checkbox"></td>
            </tr>
        </tbody>
    </table>

    <br>
    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td rowspan="2" style="width: 55%;">
                <strong>Rekomendasi (diisi oleh LSP):</strong><br>
                Berdasarkan ketentuan persyaratan dasar, maka pemohon:<br><br>
                <strong>Diterima / Tidak diterima *</strong> sebagai peserta sertifikasi<br>
                <small>* coret yang tidak sesuai</small><br><br>
                <strong>Catatan :</strong><br><br><br><br>
            </td>
            <td style="width: 45%;">
                <strong>Pemohon / Kandidat :</strong><br><br>
                Nama : {{ $data->paymentReview->payment->userCertification->user->name ??
                '....................................................' }}<br><br>
                Tanda tangan / Tanggal :<br><br><br><br>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Admin LSP :</strong><br><br>
                Nama : ...................................................<br><br>
                Tanda tangan / Tanggal :<br><br><br><br>
            </td>
        </tr>
    </table>


</body>

</html>