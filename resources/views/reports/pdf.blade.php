<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi Inspeksi Fasilitas Pelabuhan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #a63b00; /* Safety Orange */
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            color: #1961a1; /* Industrial Blue */
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 10px;
            color: #555555;
            margin-top: 3px;
        }
        .meta-info {
            font-size: 9px;
            text-align: right;
            color: #666666;
        }
        .meta-info table {
            float: right;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #a63b00; /* Safety Orange */
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 7px 8px;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #a63b00;
        }
        .data-table td {
            padding: 7px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .code-font {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            color: #1a1a1a;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 2px;
            color: #ffffff;
        }
        .badge-low { bg-color: #64748b; background-color: #64748b; }
        .badge-medium { bg-color: #f59e0b; background-color: #f59e0b; }
        .badge-high { bg-color: #ea580c; background-color: #ea580c; }
        .badge-critical { bg-color: #dc2626; background-color: #dc2626; }

        .badge-draft { bg-color: #475569; background-color: #475569; }
        .badge-reported { bg-color: #2563eb; background-color: #2563eb; }
        .badge-verified { bg-color: #4f46e5; background-color: #4f46e5; }
        .badge-assigned { bg-color: #d97706; background-color: #d97706; }
        .badge-in_progress { bg-color: #f97316; background-color: #f97316; }
        .badge-waiting_verification { bg-color: #eab308; background-color: #eab308; }
        .badge-completed { bg-color: #059669; background-color: #059669; }

        .footer-note {
            margin-top: 30px;
            width: 100%;
            font-size: 8px;
            color: #888888;
            text-align: center;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            font-size: 10px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 50px;
        }
        .signature-name {
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        $logoSrc = $logoData ? 'data:image/png;base64,' . $logoData : '';
    @endphp
    <!-- Header Block -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            @if($logoSrc)
            <td style="width: 65px; vertical-align: middle;">
                <img src="{{ $logoSrc }}" alt="Logo PTBA" style="width: 55px; height: auto;">
            </td>
            @endif
            <td style="vertical-align: middle;">
                <div class="title">PT BUKIT ASAM (PERSERO) Tbk</div>
                <div class="subtitle">Unit Pelabuhan Kertapati — E-Reporting Inspeksi Fasilitas Pelabuhan</div>
                <div style="font-size: 8px; color: #777; margin-top: 2px;">
                    Jl. Stasiun Kertapati, Kec. Kertapati, Kota Palembang, Sumatera Selatan 30258
                </div>
            </td>
            <td class="meta-info" style="vertical-align: top;">
                <div>Tanggal Cetak: <strong>{{ now()->format('d M Y H:i') }}</strong></div>
                <div>Filter Periode: <strong>{{ ($filters['start_date'] ?? 'Semua') . ' s/d ' . ($filters['end_date'] ?? 'Semua') }}</strong></div>
            </td>
        </tr>
    </table>

    <div style="font-size: 12px; font-weight: bold; margin-bottom: 12px; color: #334155;">
        LAPORAN REKAPITULASI KERUSAKAN FASILITAS
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">No. Laporan</th>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 15%;">Fasilitas</th>
                <th style="width: 25%;">Judul Laporan & Deskripsi</th>
                <th style="width: 14%;">Waktu Pengerjaan</th>
                <th style="width: 10%; text-align: center;">Tingkat</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 8%;">Pelapor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $rep)
                <tr>
                    <td class="code-font">{{ $rep->report_number }}</td>
                    <td class="code-font">{{ $rep->created_at->format('d/m/Y') }}</td>
                    <td>
                        <strong>{{ $rep->facility->facility_name }}</strong><br>
                        <span class="code-font" style="font-size: 8px; color: #666;">{{ $rep->facility->facility_code }}</span>
                    </td>
                    <td>
                        <strong>{{ $rep->title }}</strong><br>
                        <span style="font-size: 9px; color: #555555;">{{ Str::limit($rep->description, 100) }}</span>
                    </td>
                    <td class="code-font" style="font-size: 8px;">
                        @if($rep->workOrder)
                            @php
                                $startLog = $rep->statusHistories->where('new_status', \App\Enums\DamageStatus::IN_PROGRESS)->last();
                                $finishLog = $rep->statusHistories->where('new_status', \App\Enums\DamageStatus::WAITING_VERIFICATION)->last();
                                
                                $startTime = $startLog ? $startLog->created_at->format('d/m/Y H:i') : 'Belum dimulai';
                                $finishTime = $finishLog ? $finishLog->created_at->format('d/m/Y H:i') : 'Belum selesai';
                                
                                $duration = '';
                                if ($startLog && $finishLog) {
                                    $diff = $startLog->created_at->diff($finishLog->created_at);
                                    $hours = ($diff->days * 24) + $diff->h;
                                    $mins = $diff->i;
                                    $duration = "<br><span style='color:#059669; font-weight:bold;'>({$hours}j {$mins}m)</span>";
                                }
                            @endphp
                            <div>Mulai: {{ $startTime }}</div>
                            <div style="margin-top: 3px;">Selesai: {{ $finishTime }}</div>
                            {!! $duration !!}
                        @else
                            <span style="color: #999; font-style: italic;">Belum ada WO</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $rep->severity->value }}">
                            {{ $rep->severity->value }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $rep->status->value }}">
                            {{ str_replace('_', ' ', $rep->status->value) }}
                        </span>
                    </td>
                    <td>{{ $rep->reporter->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; font-style: italic; color: #777777;">
                        Tidak ada data kerusakan fasilitas ditemukan untuk parameter filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Block -->
    <table class="signature-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 35%; text-align: center;">
                <div>Mengetahui,</div>
                <div class="signature-title">Supervisor</div>
                <div style="height: 60px;"></div>
                <div class="signature-name">{{ $supervisor ? $supervisor->name : '_______________________' }}</div>
                <div style="font-size: 9px; color: #666666;">Unit Pelabuhan Kertapati</div>
            </td>
            <td style="width: 30%;"></td>
            <td style="width: 35%; text-align: center;">
                <div>Palembang, {{ now()->format('d F Y') }}</div>
                <div class="signature-title">Dibuat Oleh, Admin</div>
                <div style="height: 60px;"></div>
                <div class="signature-name">{{ $admin ? $admin->name : '_______________________' }}</div>
                <div style="font-size: 9px; color: #666666;">Unit Pelabuhan Kertapati</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh Sistem E-Reporting Inspeksi Fasilitas Pelabuhan PT Bukit Asam Kertapati Port.
    </div>
</body>
</html>
