<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>FR.APL.02 - ASESMEN MANDIRI</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        thead tr:first-child th {
            background-color: #e3f3fa;
        }

        tr.bg-gray-200 {
            background-color: #e3f3fa !important;
            font-weight: bold;
        }

        td[colspan="2"] strong {
            display: block;
            margin-bottom: 4px;
            color: #000;
        }

        input[type="radio"] {
            transform: scale(1.1);
            margin: 2px;
        }

        input[type="text"] {
            width: 100%;
            padding: 4px;
            font-size: 12px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }

        .highlight {
            background-color: yellow;
            font-weight: bold;
            padding: 4px 8px;
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


</head>

<body>
    <p class="highlight">FR.APL.02. ASESMEN MANDIRI</p>
    @php
    $skema =
    $data->aplOne->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme
    ?? null;
    $jenisSkema = strtolower($skema->jenis_skema ?? '');
    $units = $skema->units ?? [];
    @endphp

    <table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse; margin-bottom: 0;">
        <tr>

            @php
            $jenisSkema =
            strtolower($data->aplOne->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->jenis_skema
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
                    $data->aplOne->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->judul_skema
                    }}
                </strong>
            </td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>
                <strong>
                    {{
                    $data->aplOne->paymentReview->payment->userCertification->certificationList->assessmentSchedule->certificationScheme->nomor_skema
                    }}
                </strong>
            </td>
        </tr>
    </table>

    <br>

    @foreach ($units as $index => $unit)
    <table class="w-full border border-gray-600 text-sm mb-6" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="5"
                    class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-left bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <strong>Unit Kompetensi</strong>
                </th>
            </tr>
            <tr>
                <td class="border border-gray-700 dark:border-gray-600 px-3 py-1" width="10%"><strong>{{$index +
                        1}}</strong></td>
                <td class="border border-gray-700 dark:border-gray-600 px-3 py-1 dark:text-white" colspan="4">
                    <strong>{{ $unit->nomor }}</strong><br>{{ $unit->name }}
                </td>
            </tr>
            <tr class="bg-gray-200 dark:bg-gray-700 text-left dark:text-white">
                <th colspan="2" class="border border-gray-700 dark:border-gray-600 px-3 py-1">Dapatkah Saya
                    ..............?</th>
                <th class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-center" width="5%">K</th>
                <th class="border border-gray-700 dark:border-gray-600 px-3 py-1 text-center" width="5%">BK</th>
                <th class="border border-gray-700 dark:border-gray-600 px-3 py-1">Bukti yang relevan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unit->elements as $elementIndex => $element)

            <tr>
                <td colspan="2" class="border border-gray-700 px-3 py-1">
                    <strong>Elemen {{ $elementIndex + 1 }} {{ $element->name }}</strong>
                    < <div><strong>&bull; Kriteria Unjuk Kerja:</strong></div>

                        {!! $element->content !!}



                </td>

                <td colspan="1" style="text-align: center;"><input type="checkbox"></td>
                <td colspan="1" style="text-align: center;"><input type="checkbox"></td>
                <td colspan="1" style="text-align: center;"><input type="checkbox"></td>

            </tr>

            @endforeach
        </tbody>
    </table>
    @endforeach

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
                Nama : {{ $data->aplOne->paymentReview->payment->userCertification->user->name ??
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