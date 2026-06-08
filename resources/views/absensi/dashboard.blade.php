@extends('layouts.app')
@section('main-content')
<div id="page-dashboard" class="page active">
      <!-- Stats Row -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card orange p-5">
          <div class="text-ink-500 text-xs font-display uppercase tracking-wider mb-3">Total Mahasiswa</div>
          <div class="font-display text-3xl font-800 text-white mb-1">248</div>
          <div class="flex items-center gap-1 text-jade text-xs"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>+12 bulan ini</div>
        </div>
        <div class="stat-card green p-5">
          <div class="text-ink-500 text-xs font-display uppercase tracking-wider mb-3">Hadir Hari Ini</div>
          <div class="font-display text-3xl font-800 text-white mb-1">192</div>
          <div class="flex items-center gap-1 text-jade text-xs"><span class="text-jade">●</span> 77.4% kehadiran</div>
        </div>
        <div class="stat-card blue p-5">
          <div class="text-ink-500 text-xs font-display uppercase tracking-wider mb-3">Acara Aktif</div>
          <div class="font-display text-3xl font-800 text-white mb-1">3</div>
          <div class="flex items-center gap-1 text-sky text-xs"><span>●</span> 8 agenda minggu ini</div>
        </div>
        <div class="stat-card purple p-5">
          <div class="text-ink-500 text-xs font-display uppercase tracking-wider mb-3">Tingkat Hadir</div>
          <div class="font-display text-3xl font-800 text-white mb-1">84%</div>
          <div class="progress-bar mt-3"><div class="progress-fill bg-accent" style="width:84%"></div></div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <!-- Bar chart -->
        <div class="glass-card p-5 lg:col-span-2">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="font-display font-700 text-white">Tren Kehadiran</h3>
              <p class="text-ink-400 text-xs mt-0.5">7 hari terakhir</p>
            </div>
            <div class="flex gap-2">
              <span class="badge badge-green">Hadir</span>
              <span class="badge badge-red">Absen</span>
            </div>
          </div>
          <canvas id="attendanceChart" height="180"></canvas>
        </div>
        <!-- Donut chart -->
        <div class="glass-card p-5">
          <div class="mb-4">
            <h3 class="font-display font-700 text-white">Status Hari Ini</h3>
            <p class="text-ink-400 text-xs mt-0.5">Distribusi kehadiran</p>
          </div>
          <canvas id="statusChart" height="180"></canvas>
          <div class="mt-4 space-y-2">
            <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-jade inline-block"></span>Hadir</span><span class="text-white font-600">192</span></div>
            <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-accent inline-block"></span>Terlambat</span><span class="text-white font-600">24</span></div>
            <div class="flex justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-ink-500 inline-block"></span>Tidak Hadir</span><span class="text-white font-600">32</span></div>
          </div>
        </div>
      </div>

      <!-- Activity + Leaderboard -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="glass-card p-5">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-700 text-white">Aktivitas Terbaru</h3>
            <button class="text-xs text-accent hover:underline">Lihat Semua</button>
          </div>
          <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-ink-800">
              <div class="w-8 h-8 rounded-full bg-jade/20 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-jade" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg></div>
              <div class="flex-1 min-w-0"><div class="text-white text-sm font-500 truncate">Ahmad Fauzi masuk</div><div class="text-ink-400 text-xs">Seminar AI — 08:02</div></div>
              <span class="badge badge-green text-xs">Masuk</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-ink-800">
              <div class="w-8 h-8 rounded-full bg-sky/20 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-sky" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16l4-4m0 0l-4-4m4 4H3"/></svg></div>
              <div class="flex-1 min-w-0"><div class="text-white text-sm font-500 truncate">Siti Rahayu pulang</div><div class="text-ink-400 text-xs">Workshop UI/UX — 16:45</div></div>
              <span class="badge badge-blue text-xs">Pulang</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-ink-800">
              <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg></div>
              <div class="flex-1 min-w-0"><div class="text-white text-sm font-500 truncate">Budi Santoso terlambat</div><div class="text-ink-400 text-xs">Seminar AI — 09:15</div></div>
              <span class="badge badge-orange text-xs">Terlambat</span>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-ink-800">
              <div class="w-8 h-8 rounded-full bg-jade/20 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-jade" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg></div>
              <div class="flex-1 min-w-0"><div class="text-white text-sm font-500 truncate">Dewi Lestari masuk</div><div class="text-ink-400 text-xs">Rapat Himpunan — 07:55</div></div>
              <span class="badge badge-green text-xs">Masuk</span>
            </div>
          </div>
        </div>
        <div class="glass-card p-5">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-display font-700 text-white">Acara Mendatang</h3>
            <button class="btn-secondary text-xs py-1.5 px-3 no-print" onclick="navigate('acara')">+ Tambah</button>
          </div>
          <div class="space-y-3">
            <div class="p-3 rounded-xl border border-accent/30 bg-accent/5">
              <div class="flex justify-between items-start mb-1"><span class="text-white text-sm font-600">Seminar Nasional AI</span><span class="badge badge-green text-xs">Aktif</span></div>
              <div class="text-ink-400 text-xs">6 Jun 2025 · 08:00 – 16:00</div>
              <div class="text-ink-400 text-xs mt-1">3 agenda · Aula Utama</div>
            </div>
            <div class="p-3 rounded-xl border border-ink-700 bg-ink-800">
              <div class="flex justify-between items-start mb-1"><span class="text-white text-sm font-600">Workshop UI/UX Design</span><span class="badge badge-blue text-xs">Besok</span></div>
              <div class="text-ink-400 text-xs">7 Jun 2025 · 09:00 – 15:00</div>
              <div class="text-ink-400 text-xs mt-1">2 agenda · Lab Komputer 2</div>
            </div>
            <div class="p-3 rounded-xl border border-ink-700 bg-ink-800">
              <div class="flex justify-between items-start mb-1"><span class="text-white text-sm font-600">Rapat Himpunan Mahasiswa</span><span class="badge badge-gray text-xs">3 Hari</span></div>
              <div class="text-ink-400 text-xs">9 Jun 2025 · 13:00 – 17:00</div>
              <div class="text-ink-400 text-xs mt-1">1 agenda · Ruang Rapat</div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

