<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* ── page ─────────────────────────── */
        @page {
            margin: 1.8cm 1.8cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
            background: #fbfdff;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ── smooth blue palette ────────────── */
        .bg-soft-blue {
            background: #f0f7ff;
        }
        .border-soft-blue {
            border-color: #b9d6fd;
        }
        .text-blue-deep {
            color: #1a4f9e;
        }
        .text-blue-mid {
            color: #2563eb;
        }
        .text-blue-light {
            color: #6b94e8;
        }
        .text-slate {
            color: #475569;
        }
        .text-slate-light {
            color: #94a3b8;
        }

        /* ── header ─────────────────────────── */
        .header-wrap {
            padding-bottom: 16px;
            border-bottom: 2px solid #d9eafb;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .org-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2.2px;
            text-transform: uppercase;
            color: #6b94e8;
            margin: 0 0 4px 0;
        }

        .doc-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a4f9e;
            margin: 0;
            letter-spacing: -0.4px;
            line-height: 1.2;
        }

        .date-box {
            text-align: right;
        }
        .date-label {
            font-size: 9px;
            font-weight: 500;
            color: #94a3b8;
            margin: 0 0 2px 0;
            letter-spacing: 0.4px;
        }
        .date-val {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            background: #f2f8ff;
            padding: 4px 12px;
            border-radius: 30px;
            display: inline-block;
        }

        /* ── info + stats band ─────────────── */
        .band-layout {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: stretch;
            margin-bottom: 28px;
            gap: 16px;
        }

        .info-card {
            background: #f6fbff;
            border: 1px solid #cde0fa;
            border-radius: 18px;
            padding: 16px 22px;
            flex: 1 1 48%;
            min-width: 200px;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.04);
        }

        .info-row {
            margin-bottom: 8px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-lbl {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #7aa3e6;
            margin-bottom: 1px;
        }
        .info-val {
            font-size: 13px;
            font-weight: 600;
            color: #1a4f9e;
            word-break: break-word;
        }

        /* ── stats pills ───────────────────── */
        .stats-wrap {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 10px;
            flex: 1 1 auto;
        }

        .stats-pill {
            background: white;
            border-radius: 40px;
            padding: 8px 18px 10px 18px;
            min-width: 72px;
            text-align: center;
            border: 1px solid #d9eafb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.04);
            transition: 0.1s ease;
        }

        .stats-pill.sp-total {
            background: #e8f0fe;
            border-color: #b9d6fd;
        }
        .stats-pill.sp-hadir {
            background: #e3fcf5;
            border-color: #9de8da;
        }
        .stats-pill.sp-izin {
            background: #e6f3ff;
            border-color: #b2d6fd;
        }
        .stats-pill.sp-alpa {
            background: #fef0f0;
            border-color: #fbcbce;
        }

        .stats-num {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: -0.2px;
        }
        .sp-total .stats-num {
            color: #1f58b0;
        }
        .sp-hadir .stats-num {
            color: #0f8b7a;
        }
        .sp-izin .stats-num {
            color: #1d72b8;
        }
        .sp-alpa .stats-num {
            color: #c72a3a;
        }

        .stats-lbl {
            font-size: 8.5px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #7f8fa3;
            margin-top: 4px;
        }

        /* ── table ──────────────────────────── */
        .table-wrap {
            border-radius: 18px;
            border: 1px solid #d9eafb;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.03);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        .data-table thead tr {
            background: #2563eb;
            background: linear-gradient(145deg, #1f57b0, #2b6ef0);
        }

        .data-table th {
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 14px 14px 12px 14px;
            text-align: left;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }
        .data-table th:last-child {
            border-right: none;
        }
        .data-table th:first-child {
            padding-left: 20px;
        }
        .data-table th:last-child {
            padding-right: 20px;
        }

        .data-table tbody tr {
            transition: background 0.1s;
        }
        .data-table tbody tr:nth-child(odd) td {
            background: #fafdff;
        }
        .data-table tbody tr:nth-child(even) td {
            background: #f2f8fe;
        }
        .data-table tbody tr:hover td {
            background: #e7f0fd;
        }

        .data-table td {
            padding: 12px 14px;
            font-size: 10.5px;
            color: #1e293b;
            border-bottom: 1px solid #e5effb;
            vertical-align: middle;
        }
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        .data-table td:first-child {
            padding-left: 20px;
        }
        .data-table td:last-child {
            padding-right: 20px;
        }

        .cell-no {
            text-align: center;
            color: #7a8ea5;
            font-weight: 500;
            font-size: 10px;
        }
        .cell-name {
            font-weight: 600;
            color: #0a2647;
        }
        .cell-divisi {
            color: #475569;
        }
        .cell-time {
            text-align: center;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-size: 10.5px;
            font-weight: 500;
            color: #1f3a6b;
        }
        .cell-status {
            text-align: center;
        }

        /* ── badges ─────────────────────────── */
        .badge {
            display: inline-block;
            padding: 4px 14px;
            font-size: 8.5px;
            font-weight: 700;
            border-radius: 40px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: white;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        .badge-hadir {
            background: #d2f4ed;
            color: #0d6d5e;
            border-color: #a7e3d5;
        }
        .badge-terlambat {
            background: #fef3c7;
            color: #8a6100;
            border-color: #fad98b;
        }
        .badge-izin {
            background: #d9edfe;
            color: #175a94;
            border-color: #afcef9;
        }
        .badge-tidak-hadir {
            background: #fde1e3;
            color: #a8232f;
            border-color: #f6b6bc;
        }

        /* ── footer ─────────────────────────── */
        .footer-rule {
            margin-top: 28px;
            border-top: 1px solid #deeaf8;
        }
        .footer-note {
            font-size: 8.5px;
            color: #94a3b8;
            text-align: center;
            margin-top: 10px;
            letter-spacing: 0.3px;
        }
        .footer-note span {
            color: #6b94e8;
            font-weight: 500;
        }

        /* ── responsive tweaks ──────────────── */
        @media (max-width: 640px) {
            .band-layout {
                flex-direction: column;
            }
            .stats-wrap {
                justify-content: flex-start;
            }
            .date-box {
                text-align: left;
                margin-top: 6px;
            }
        }
    </style>
</head>
<body>

    <!-- ═══ HEADER ═══ -->
    <div class="header-wrap">
        <div>
            <p class="org-label">Himpunan Mahasiswa Informatika · HIMATIF</p>
            <h1 class="doc-title">Laporan Kehadiran Agenda</h1>
        </div>
        <div class="date-box">
            <p class="date-label">Dicetak pada</p>
            <p class="date-val">{{ $date }}</p>
        </div>
    </div>

    <!-- ═══ INFO + STATS ═══ -->
    <div class="band-layout">
        <!-- info card -->
        <div class="info-card">
            <div class="info-row">
                <div class="info-lbl">Acara</div>
                <div class="info-val">{{ $acara }}</div>
            </div>
            <div class="info-row">
                <div class="info-lbl">Agenda</div>
                <div class="info-val">{{ $agenda }}</div>
            </div>
        </div>

        <!-- stats pills -->
        <div class="stats-wrap">
            <div class="stats-pill sp-total">
                <div class="stats-num">{{ $stats['total'] }}</div>
                <div class="stats-lbl">Total</div>
            </div>
            <div class="stats-pill sp-hadir">
                <div class="stats-num">{{ $stats['hadir'] }}</div>
                <div class="stats-lbl">Hadir</div>
            </div>
            <div class="stats-pill sp-izin">
                <div class="stats-num">{{ $stats['izin'] }}</div>
                <div class="stats-lbl">Izin</div>
            </div>
            <div class="stats-pill sp-alpa">
                <div class="stats-num">{{ $stats['tidak_hadir'] }}</div>
                <div class="stats-lbl">Alpa</div>
            </div>
        </div>
    </div>

    <!-- ═══ TABLE ═══ -->
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 6%;  text-align: center;">No</th>
                    <th style="width: 30%;">Nama Lengkap</th>
                    <th style="width: 22%;">Divisi</th>
                    <th style="width: 14%; text-align: center;">Jam Masuk</th>
                    <th style="width: 14%; text-align: center;">Jam Pulang</th>
                    <th style="width: 14%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $idx => $abs)
                @php
                $status      = $abs->status ?? 'tidak_hadir';
                $badgeClass  = 'badge-tidak-hadir';
                $statusLabel = 'Alpa';

                if ($status === 'hadir') {
                    $badgeClass  = 'badge-hadir';
                    $statusLabel = 'Hadir';
                } elseif ($status === 'terlambat') {
                    $badgeClass  = 'badge-terlambat';
                    $statusLabel = 'Terlambat';
                } elseif ($status === 'izin') {
                    $badgeClass  = 'badge-izin';
                    $statusLabel = 'Izin';
                }
                @endphp
                <tr>
                    <td class="cell-no">{{ $idx + 1 }}</td>
                    <td class="cell-name">{{ $abs->name }}</td>
                    <td class="cell-divisi">{{ $abs->nama_divisi }}</td>
                    <td class="cell-time">
                        {{ $abs->waktu_masuk  ? \Carbon\Carbon::parse($abs->waktu_masuk)->format('H:i')  : '—' }}
                    </td>
                    <td class="cell-time">
                        {{ $abs->waktu_pulang ? \Carbon\Carbon::parse($abs->waktu_pulang)->format('H:i') : '—' }}
                    </td>
                    <td class="cell-status">
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ═══ FOOTER ═══ -->
    <div class="footer-rule"></div>
    <p class="footer-note">
        <span>Smart Attendance System</span> &mdash; HIMATIF &bull; dokumen digenerate otomatis
    </p>

</body>
</html>