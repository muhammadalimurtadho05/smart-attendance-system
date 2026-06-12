@extends('layouts.app')
@section('main-content')

<div id="page-mahasiswa" class="page active">
  <!-- Filter & Actions -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div class="flex gap-2 flex-1 flex-wrap">
      <input
        type="text"
        class="inp max-w-xs"
        placeholder="Cari nama / RFID / email..."
        oninput="filterTable(this.value)"
      >
      <select class="inp max-w-40">
        <option value="">Semua Jabatan</option>
        <option>Ketua</option>
        <option>Anggota</option>
        <option>Bendahara</option>
        <option>Sekretaris</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button class="btn-secondary no-print" onclick="printSection('mahasiswa-table')">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak
      </button>
      <button class="btn-primary" onclick="showModal('modal-tambah-mahasiswa')">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Mahasiswa
      </button>
    </div>
  </div>

  <!-- Mahasiswa Table -->
  <div id="mahasiswa-table" class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
    <table class="data-table">
      <thead>
        <tr>
          <th>No</th>
          <th>Mahasiswa</th>
          <th>RFID</th>
          <th>Email</th>
          <th>Status</th>
          <th class="no-print">Aksi</th>
        </tr>
      </thead>
      <tbody id="mahasiswa-tbody">
        @foreach ($panitia as $idx => $mhs)
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td>
              <div class="text-slate-900 font-500 text-sm">{{ $mhs->name }}</div>
            </td>
            <td>
              <code class="text-xs font-display text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                {{ $mhs->rfid_uid }}
              </code>
            </td>
            <td>{{ $mhs->email }}</td>
            <td>
              <span class="badge {{ $mhs->is_active == '1' ? 'badge-green' : 'badge-gray' }}">
                {{ $mhs->is_active == '1' ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="no-print">
              <div class="flex gap-2">
                <button class="btn-secondary py-1.5 px-3 text-xs">Edit</button>
                <button class="btn-danger py-1.5 px-3 text-xs">Hapus</button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
