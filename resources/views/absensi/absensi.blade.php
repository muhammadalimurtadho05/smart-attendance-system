@extends('layouts.app')
@section('page_title', 'Absensi')
@section('page_subtitle', 'Proses absensi dengan RFID')
@section('main-content')
@php
$divisiList = $absensi->pluck('nama_divisi')->unique()->filter()->values();
@endphp

<div id="page-absensi-rekap" class="page active">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <div class="text-sm flex items-center font-display font-600 uppercase tracking-wider text-slate-500 mb-2">
                <a href="/acara" class="hover:text-blue-600 transition">Acara</a>
                <span class="mx-2 text-slate-400">/</span>
                <a href="{{ route('acara.agenda', ['acara_id' => encrypt($acara->id)]) }}" class="hover:text-blue-600 transition">{{ $acara->nama }}</a>
                <span class="mx-2 text-slate-400">/</span>
                <span class="text-slate-900">{{ $agenda->nama }}</span>
            </div>
            <h2 class="font-display font-800 text-slate-900 text-2xl leading-none">Rekap Kehadiran</h2>
            <p class="text-slate-500 text-sm mt-1.5">Laporan absensi masuk dan pulang untuk agenda ini</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('acara.agenda', ['acara_id' => encrypt($acara->id)]) }}" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('report.agenda', ['agenda_id' => encrypt($agenda->id)]) }}" target="_blank" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Unduh PDF
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all duration-300 p-5 flex items-center justify-between">
            <div>
                <div class="text-slate-400 text-xs font-display uppercase tracking-wider mb-1">Total Panitia</div>
                <div class="font-display text-3xl font-800 text-slate-900">{{ $stats['total'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        <!-- Hadir -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all duration-300 p-5 flex items-center justify-between">
            <div>
                <div class="text-emerald-500 text-xs font-display uppercase tracking-wider mb-1">Hadir & Terlambat</div>
                <div class="font-display text-3xl font-800 text-emerald-600">{{ $stats['hadir'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Izin -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all duration-300 p-5 flex items-center justify-between">
            <div>
                <div class="text-blue-500 text-xs font-display uppercase tracking-wider mb-1">Izin</div>
                <div class="font-display text-3xl font-800 text-blue-600">{{ $stats['izin'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Alpa -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-slate-300 hover:shadow-md transition-all duration-300 p-5 flex items-center justify-between">
            <div>
                <div class="text-rose-500 text-xs font-display uppercase tracking-wider mb-1">Alpa</div>
                <div class="font-display text-3xl font-800 text-rose-600">{{ $stats['tidak_hadir'] }}</div>
            </div>
            <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500 border border-rose-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="w-full flex flex-col sm:flex-row gap-3">
            <!-- Search input -->
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" id="search-absensi" class="inp pl-9" placeholder="Cari nama, RFID, divisi...">
            </div>

            <!-- Status filter -->
            <div class="w-full sm:w-48">
                <select id="filter-status" class="inp">
                    <option value="">Semua Status</option>
                    <option value="hadir">Hadir</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="izin">Izin</option>
                    <option value="tidak_hadir">Alpa</option>
                </select>
            </div>

            <!-- Divisi filter -->
            <div class="w-full sm:w-48">
                <select id="filter-divisi" class="inp">
                    <option value="">Semua Divisi</option>
                    @foreach($divisiList as $div)
                        <option value="{{ $div }}">{{ $div }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table" id="table-absensi-list">
                <thead>
                    <tr>
                        <th class="w-[5%] text-center">No</th>
                        <th class="w-[22%]">Nama</th>
                        <th class="w-[13%]">RFID</th>
                        <th class="w-[14%]">Divisi</th>
                        <th class="w-[11%] text-center">Masuk</th>
                        <th class="w-[11%] text-center">Pulang</th>
                        <th class="w-[9%] text-center">Status</th>
                        <th class="w-[10%]">Keterangan</th>
                        <th class="w-[5%] text-center no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $idx => $abs)
                    @php
                    $status      = $abs->status ?? 'tidak_hadir';
                    $badgeClass  = 'badge-red';
                    $statusLabel = 'Alpa';

                    if ($status === 'hadir') {
                        $badgeClass  = 'badge-green';
                        $statusLabel = 'Hadir';
                    } elseif ($status === 'terlambat') {
                        $badgeClass  = 'badge-orange';
                        $statusLabel = 'Terlambat';
                    } elseif ($status === 'izin') {
                        $badgeClass  = 'badge-blue';
                        $statusLabel = 'Izin';
                    }
                    @endphp
                    <tr class="absensi-row" data-name="{{ strtolower($abs->name) }}" data-rfid="{{ strtolower($abs->rfid_uid ?? '') }}" data-divisi="{{ strtolower($abs->nama_divisi ?? '') }}" data-status="{{ $status }}">
                        <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td>
                            <div class="text-slate-900 font-semibold text-sm">{{ $abs->name }}</div>
                            <div class="text-slate-400 text-xs mt-0.5">{{ $abs->email }}</div>
                        </td>
                        <td>
                            @if($abs->rfid_uid)
                            <code class="text-xs font-mono text-blue-600 bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                                {{ $abs->rfid_uid }}
                            </code>
                            @else
                            <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="text-slate-600 font-medium text-sm">{{ $abs->nama_divisi }}</td>
                        <td class="text-center">
                            @if($abs->waktu_masuk)
                            <div class="inline-flex items-center gap-1.5 text-slate-700 font-semibold text-sm bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($abs->waktu_masuk)->format('H:i') }}
                            </div>
                            @else
                            <span class="text-slate-400 text-xs font-semibold">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($abs->waktu_pulang)
                            <div class="inline-flex items-center gap-1.5 text-slate-700 font-semibold text-sm bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($abs->waktu_pulang)->format('H:i') }}
                            </div>
                            @else
                            <span class="text-slate-400 text-xs font-semibold">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-slate-500 text-xs max-w-[150px] truncate" title="{{ $abs->keterangan ?? '-' }}">
                            {{ $abs->keterangan ?? '-' }}
                        </td>
                        <td class="text-center no-print">
                            @if($abs->rfid_uid)
                            <button class="btn-secondary py-1 px-2.5 text-xs text-blue-600 hover:text-blue-700 transition" onclick="openEditAbsensiModal('{{ $abs->name }}', '{{ $abs->rfid_uid }}', '{{ $status }}', '{{ $abs->waktu_masuk ? \Carbon\Carbon::parse($abs->waktu_masuk)->format('H:i') : '' }}', '{{ $abs->waktu_pulang ? \Carbon\Carbon::parse($abs->waktu_pulang)->format('H:i') : '' }}', '{{ $abs->keterangan ?? '' }}')">
                                Edit
                            </button>
                            @else
                            <button class="btn-secondary py-1 px-2.5 text-xs opacity-50 cursor-not-allowed" disabled title="RFID belum terdaftar">
                                Edit
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr id="no-records-row-empty">
                        <td colspan="9" class="text-center py-8 text-slate-500 font-medium text-sm">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Belum ada data kehadiran untuk agenda ini.
                        </td>
                    </tr>
                    @endforelse
                    
                    <tr id="no-records-row" class="hidden">
                        <td colspan="9" class="text-center py-8 text-slate-500 font-medium text-sm">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Tidak ada data kehadiran yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Absensi Modal -->
<div id="modal-edit-absensi" class="modal-overlay hidden" onclick="closeModal(event, 'modal-edit-absensi')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-display font-800 text-slate-900 text-xl">Edit Kehadiran</h2>
                <p class="text-slate-500 text-sm mt-0.5">Ubah status kehadiran panitia/mahasiswa secara manual</p>
            </div>
            <button onclick="closeModal(null, 'modal-edit-absensi')"
                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                ✕
            </button>
        </div>
        <form method="POST" action="{{ route('agenda.absensi.update') }}">
            @csrf
            <input type="hidden" name="agenda_id" value="{{ $agenda->id }}">
            <input type="hidden" name="rfid_uid" id="edit-absensi-rfid">

            <div class="space-y-4 mb-6">
                <!-- Info Display -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-150 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-slate-400 font-medium text-xs uppercase block">Nama Lengkap</span>
                        <strong class="text-slate-800 font-semibold" id="edit-absensi-nama-text">-</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium text-xs uppercase block">RFID UID</span>
                        <code class="text-blue-600 font-mono text-xs" id="edit-absensi-rfid-text">-</code>
                    </div>
                </div>

                <!-- Status Kehadiran -->
                <div>
                    <label for="edit-absensi-status">Status Kehadiran</label>
                    <select name="status" id="edit-absensi-status" class="inp" required>
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="izin">Izin</option>
                        <option value="tidak_hadir">Alpa</option>
                    </select>
                </div>

                <!-- Waktu Masuk & Pulang -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit-absensi-masuk">Waktu Masuk</label>
                        <input type="time" name="waktu_masuk" id="edit-absensi-masuk" class="inp">
                    </div>
                    <div>
                        <label for="edit-absensi-pulang">Waktu Pulang</label>
                        <input type="time" name="waktu_pulang" id="edit-absensi-pulang" class="inp">
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="edit-absensi-keterangan">Keterangan</label>
                    <input type="text" name="keterangan" id="edit-absensi-keterangan" class="inp" placeholder="Alasan izin atau info tambahan...">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" class="btn-secondary flex-1 justify-center" onclick="closeModal(null, 'modal-edit-absensi')">
                    Batal
                </button>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update topbar title & sub programmatically
        const pageTitle = document.getElementById('page-title');
        const pageSub = document.getElementById('page-sub');
        if (pageTitle) pageTitle.textContent = "Kehadiran";
        if (pageSub) pageSub.textContent = "Rekapitulasi kehadiran panitia & mahasiswa";

        // Filtering logic
        const searchInput = document.getElementById('search-absensi');
        const statusFilter = document.getElementById('filter-status');
        const divisiFilter = document.getElementById('filter-divisi');
        const rows = document.querySelectorAll('.absensi-row');
        const noRecordsRow = document.getElementById('no-records-row');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value;
            const divisiValue = divisiFilter.value.toLowerCase();

            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const rfid = row.getAttribute('data-rfid');
                const divisi = row.getAttribute('data-divisi');
                const status = row.getAttribute('data-status');

                const matchesSearch = !searchValue || 
                                     name.includes(searchValue) || 
                                     rfid.includes(searchValue) || 
                                     divisi.includes(searchValue);
                                     
                const matchesStatus = !statusValue || status === statusValue;
                const matchesDivisi = !divisiValue || divisi === divisiValue;

                if (matchesSearch && matchesStatus && matchesDivisi) {
                    row.classList.remove('hidden');
                    visibleCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (visibleCount === 0 && rows.length > 0) {
                noRecordsRow.classList.remove('hidden');
            } else {
                noRecordsRow.classList.add('hidden');
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (statusFilter) statusFilter.addEventListener('change', filterTable);
        if (divisiFilter) divisiFilter.addEventListener('change', filterTable);

        // Edit Modal dynamic time enabling/disabling
        const statusSelect = document.getElementById('edit-absensi-status');
        const masukInput = document.getElementById('edit-absensi-masuk');
        const pulangInput = document.getElementById('edit-absensi-pulang');

        function toggleTimeInputs() {
            const val = statusSelect.value;
            if (val === 'izin' || val === 'tidak_hadir') {
                masukInput.disabled = true;
                pulangInput.disabled = true;
                masukInput.classList.add('opacity-50', 'bg-slate-50');
                pulangInput.classList.add('opacity-50', 'bg-slate-50');
                masukInput.value = '';
                pulangInput.value = '';
            } else {
                masukInput.disabled = false;
                pulangInput.disabled = false;
                masukInput.classList.remove('opacity-50', 'bg-slate-50');
                pulangInput.classList.remove('opacity-50', 'bg-slate-50');
            }
        }
        statusSelect.addEventListener('change', toggleTimeInputs);
        window.toggleTimeInputs = toggleTimeInputs; // export to global
    });

    function openEditAbsensiModal(name, rfid, status, masuk, pulang, keterangan) {
        document.getElementById('edit-absensi-rfid').value = rfid;
        document.getElementById('edit-absensi-nama-text').textContent = name;
        document.getElementById('edit-absensi-rfid-text').textContent = rfid;
        document.getElementById('edit-absensi-status').value = status;
        document.getElementById('edit-absensi-masuk').value = masuk;
        document.getElementById('edit-absensi-pulang').value = pulang;
        document.getElementById('edit-absensi-keterangan').value = keterangan === '-' ? '' : keterangan;

        // update disabled state
        if (window.toggleTimeInputs) {
            window.toggleTimeInputs();
        }

        showModal('modal-edit-absensi');
    }
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '<div class="font-display font-bold text-2xl text-slate-800">Berhasil!</div>',
            text: "{{ session('success') }}",
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-2xl border border-slate-100',
            }
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '<div class="font-display font-bold text-2xl text-slate-800">Gagal!</div>',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#EF4444',
            customClass: {
                popup: 'rounded-2xl border border-slate-100',
            }
        });
    });
</script>
@endif
@endsection