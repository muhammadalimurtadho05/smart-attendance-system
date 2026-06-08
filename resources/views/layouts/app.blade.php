<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SiAbsen — Sistem Absensi Digital</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Syne', 'sans-serif'],
            body: ['DM Sans', 'sans-serif'],
          },
          colors: {
            ink: { DEFAULT: '#0D0D0D', 50: '#F5F5F5', 100: '#E8E8E8', 200: '#D0D0D0', 300: '#A8A8A8', 400: '#6B6B6B', 500: '#3D3D3D', 600: '#2B2B2B', 700: '#1E1E1E', 800: '#141414', 900: '#0D0D0D' },
            accent: { DEFAULT: '#FF4D00', light: '#FF6B2B', dark: '#CC3D00' },
            jade: { DEFAULT: '#00C896', light: '#00E5AB', dark: '#009B75' },
            sky: { DEFAULT: '#0066FF', light: '#3385FF', dark: '#0047CC' },
          }
        }
      }
    }
  </script>
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; background: #0D0D0D; color: #E8E8E8; margin: 0; }
    .font-display { font-family: 'Syne', sans-serif; }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: #1E1E1E; }
    ::-webkit-scrollbar-thumb { background: #3D3D3D; border-radius: 2px; }

    /* Sidebar */
    #sidebar { transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), width 0.3s cubic-bezier(0.4,0,0.2,1); }
    #sidebar.collapsed { width: 72px !important; }
    #sidebar.collapsed .nav-label, #sidebar.collapsed .sidebar-logo-text, #sidebar.collapsed .sidebar-section-title { opacity: 0; pointer-events: none; overflow: hidden; width: 0; }
    #sidebar.collapsed .nav-item { justify-content: center; padding: 12px; }
    #sidebar.collapsed .nav-icon { margin: 0; }

    /* Page transitions */
    .page { display: none; animation: fadeUp 0.35s ease; }
    .page.active { display: block; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

    /* Noise texture overlay */
    body::before { content: ''; position: fixed; inset: 0; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E"); pointer-events: none; z-index: 9999; opacity: 0.4; }

    /* Cards */
    .glass-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); backdrop-filter: blur(12px); border-radius: 16px; }
    .stat-card { background: #141414; border: 1px solid #2B2B2B; border-radius: 16px; position: relative; overflow: hidden; transition: border-color 0.2s, transform 0.2s; }
    .stat-card:hover { border-color: #FF4D00; transform: translateY(-2px); }
    .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; border-radius: 50%; filter: blur(40px); opacity: 0.15; }
    .stat-card.orange::before { background: #FF4D00; }
    .stat-card.green::before { background: #00C896; }
    .stat-card.blue::before { background: #0066FF; }
    .stat-card.purple::before { background: #9B6AFF; }

    /* Nav items */
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 10px; cursor: pointer; transition: background 0.2s, color 0.2s; color: #6B6B6B; font-size: 14px; font-weight: 500; text-decoration: none; margin-bottom: 2px; white-space: nowrap; }
    .nav-item:hover { background: #1E1E1E; color: #E8E8E8; }
    .nav-item.active { background: #FF4D00; color: #fff; }
    .nav-item.active svg { color: #fff; }
    .nav-icon { flex-shrink: 0; width: 20px; height: 20px; }

    /* Table */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { font-family: 'Syne', sans-serif; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: #6B6B6B; padding: 12px 16px; text-align: left; border-bottom: 1px solid #2B2B2B; font-weight: 600; }
    .data-table td { padding: 14px 16px; border-bottom: 1px solid #1E1E1E; font-size: 14px; color: #A8A8A8; }
    .data-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .data-table tbody tr:last-child td { border-bottom: none; }

    /* Buttons */
    .btn-primary { background: #FF4D00; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s, transform 0.15s; display: inline-flex; align-items: center; gap: 8px; font-family: 'DM Sans'; }
    .btn-primary:hover { background: #FF6B2B; transform: translateY(-1px); }
    .btn-secondary { background: #1E1E1E; color: #E8E8E8; border: 1px solid #2B2B2B; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.2s, border-color 0.2s; display: inline-flex; align-items: center; gap: 8px; font-family: 'DM Sans'; }
    .btn-secondary:hover { background: #2B2B2B; border-color: #3D3D3D; }
    .btn-green { background: #00C896; color: #0D0D0D; border: none; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 6px; font-family: 'DM Sans'; }
    .btn-green:hover { background: #00E5AB; }
    .btn-danger { background: rgba(255,59,48,0.1); color: #FF3B30; border: 1px solid rgba(255,59,48,0.2); padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 6px; font-family: 'DM Sans'; }
    .btn-danger:hover { background: rgba(255,59,48,0.2); }

    /* Inputs */
    .inp { background: #1E1E1E; border: 1px solid #2B2B2B; border-radius: 10px; padding: 10px 14px; color: #E8E8E8; font-size: 14px; font-family: 'DM Sans'; width: 100%; transition: border-color 0.2s; outline: none; }
    .inp:focus { border-color: #FF4D00; }
    .inp::placeholder { color: #3D3D3D; }
    label { display: block; font-size: 12px; font-weight: 600; color: #6B6B6B; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.06em; font-family: 'Syne'; }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.75); z-index: 1000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal-box { background: #141414; border: 1px solid #2B2B2B; border-radius: 20px; width: 100%; max-width: 560px; max-height: 85vh; overflow-y: auto; padding: 32px; position: relative; animation: modalIn 0.25s ease; }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }

    /* Badge */
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; }
    .badge-green { background: rgba(0,200,150,0.12); color: #00C896; }
    .badge-orange { background: rgba(255,77,0,0.12); color: #FF4D00; }
    .badge-blue { background: rgba(0,102,255,0.12); color: #0066FF; }
    .badge-red { background: rgba(255,59,48,0.12); color: #FF3B30; }
    .badge-gray { background: rgba(255,255,255,0.06); color: #A8A8A8; }

    /* RFID pulse */
    .rfid-pulse { animation: rfidPulse 1.5s infinite; }
    @keyframes rfidPulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(255,77,0,0.4); } 50% { box-shadow: 0 0 0 16px rgba(255,77,0,0); } }

    /* Progress bar */
    .progress-bar { background: #2B2B2B; border-radius: 99px; height: 6px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 99px; transition: width 0.6s ease; }

    /* Accordion */
    .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
    .accordion-content.open { max-height: 2000px; }

    /* Print */
    @media print {
      #sidebar, #topbar, .no-print { display: none !important; }
      #main-content { margin: 0 !important; padding: 20px !important; }
      body { background: white; color: black; }
      .glass-card, .stat-card { border: 1px solid #ccc; background: white; }
    }
  </style>
</head>
<body class="flex h-screen overflow-hidden">

<!-- ═══════════════════════════════════════════════════════════ SIDEBAR -->
  @include('components.sidebar')


<!-- ═══════════════════════════════════════════════════════════ MAIN WRAPPER -->
<div class="flex-1 flex flex-col overflow-hidden">

  <!-- TOPBAR -->
  @include('components.topbar')

  <!-- CONTENT AREA -->
  <main id="main-content" class="flex-1 overflow-y-auto bg-ink-900 p-6">
    
    @yield('main-content')
  </main>

  <!-- FOOTER -->
  @include('components.footer')
</div>

<!-- ══════════════════════════════════════════════════════════ MODALS -->

<!-- Modal Tambah Mahasiswa -->


<!-- Modal Tambah Acara -->


<!-- Modal Detail Acara / Agenda -->


<!-- Toast -->
<div id="toast" class="fixed bottom-6 right-6 z-[9999] transition-all duration-300 opacity-0 translate-y-4 pointer-events-none">
  <div id="toast-inner" class="flex items-center gap-3 px-5 py-3 rounded-xl shadow-2xl text-sm font-500 bg-jade text-ink-900"></div>
</div>

<script>
// ═══════════════════════════════════════════════ DATA
const mahasiswaData = [
  { id: 1, rfid: 'A1B2C3D4', nama: 'Ahmad Fauzi', email: 'ahmad@kampus.ac.id', jabatan: 'Ketua', foto: '', status: 'aktif' },
  { id: 2, rfid: 'E5F6G7H8', nama: 'Siti Rahayu', email: 'siti@kampus.ac.id', jabatan: 'Sekretaris', foto: '', status: 'aktif' },
  { id: 3, rfid: 'I9J0K1L2', nama: 'Budi Santoso', email: 'budi@kampus.ac.id', jabatan: 'Anggota', foto: '', status: 'aktif' },
  { id: 4, rfid: 'M3N4O5P6', nama: 'Dewi Lestari', email: 'dewi@kampus.ac.id', jabatan: 'Bendahara', foto: '', status: 'aktif' },
  { id: 5, rfid: 'Q7R8S9T0', nama: 'Rudi Hermawan', email: 'rudi@kampus.ac.id', jabatan: 'Anggota', foto: '', status: 'nonaktif' },
];

const acaraData = [
  { id: 1, nama: 'Seminar Nasional AI 2025', tanggalMulai: '2025-06-06', tanggalSelesai: '2025-06-06', lokasi: 'Aula Utama', deskripsi: 'Seminar tentang perkembangan AI di Indonesia', status: 'aktif',
    agenda: [
      { id: 1, nama: 'Pembukaan & Registrasi', mulai: '07:30', selesai: '08:00', batasAbsen: '08:15' },
      { id: 2, nama: 'Sesi I: AI Fundamentals', mulai: '08:00', selesai: '10:00', batasAbsen: '08:30' },
      { id: 3, nama: 'Sesi II: Applied AI', mulai: '13:00', selesai: '15:00', batasAbsen: '13:30' },
    ]
  },
  { id: 2, nama: 'Workshop UI/UX Design', tanggalMulai: '2025-06-07', tanggalSelesai: '2025-06-07', lokasi: 'Lab Komputer 2', deskripsi: 'Workshop praktis desain antarmuka', status: 'upcoming',
    agenda: [
      { id: 4, nama: 'Materi Figma Dasar', mulai: '09:00', selesai: '11:00', batasAbsen: '09:20' },
      { id: 5, nama: 'Praktik Desain', mulai: '13:00', selesai: '15:00', batasAbsen: '13:15' },
    ]
  },
  { id: 3, nama: 'Rapat Himpunan Mahasiswa', tanggalMulai: '2025-06-09', tanggalSelesai: '2025-06-09', lokasi: 'Ruang Rapat', deskripsi: 'Rapat koordinasi bulanan', status: 'upcoming',
    agenda: [{ id: 6, nama: 'Rapat Pleno', mulai: '13:00', selesai: '17:00', batasAbsen: '13:30' }]
  },
];

const absensiLog = [
  { mahasiswa: 'Ahmad Fauzi', agenda: 'Seminar AI — Sesi I', masuk: '07:58', pulang: '10:05', status: 'hadir' },
  { mahasiswa: 'Siti Rahayu', agenda: 'Seminar AI — Sesi I', masuk: '08:02', pulang: '10:00', status: 'hadir' },
  { mahasiswa: 'Budi Santoso', agenda: 'Seminar AI — Sesi I', masuk: '09:15', pulang: '10:08', status: 'terlambat' },
  { mahasiswa: 'Dewi Lestari', agenda: 'Seminar AI — Sesi I', masuk: '07:55', pulang: '10:02', status: 'hadir' },
  { mahasiswa: 'Rudi Hermawan', agenda: 'Seminar AI — Sesi I', masuk: '-', pulang: '-', status: 'tidak-hadir' },
];

// ═══════════════════════════════════════════════ INIT
window.onload = () => {
  startClock();
  renderMahasiswaTable(mahasiswaData);
  renderAcaraList();
  renderAbsensiLog();
  initCharts();
};

// ═══════════════════════════════════════════════ NAVIGATION
function navigate(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');
  document.querySelector(`[onclick="navigate('${page}')"]`).classList.add('active');
  const titles = { dashboard: ['Dashboard', 'Ringkasan sistem absensi'], mahasiswa: ['Daftar Mahasiswa', 'Manajemen data mahasiswa'], acara: ['Acara & Agenda', 'Kelola acara dan agenda'], absensi: ['Absensi', 'Proses absensi dengan RFID'], laporan: ['Laporan & Cetak', 'Rekap dan export data'] };
  document.getElementById('page-title').textContent = titles[page][0];
  document.getElementById('page-sub').textContent = titles[page][1];
  if (page === 'laporan') setTimeout(initLaporanChart, 100);
}

// ═══════════════════════════════════════════════ SIDEBAR
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('collapsed');
}

// ═══════════════════════════════════════════════ CLOCK
function startClock() {
  const update = () => {
    const now = new Date();
    document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('datestr').textContent = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
  };
  update(); setInterval(update, 1000);
}

// ═══════════════════════════════════════════════ MAHASISWA TABLE

function filterTable(q) {
  const filtered = mahasiswaData.filter(m => m.nama.toLowerCase().includes(q.toLowerCase()) || m.rfid.toLowerCase().includes(q.toLowerCase()) || m.email.toLowerCase().includes(q.toLowerCase()));
  renderMahasiswaTable(filtered);
}

function saveMahasiswa() {
  const rfid = document.getElementById('rfid-new').value || 'RF' + Math.random().toString(36).substr(2, 6).toUpperCase();
  showToast('✓ Mahasiswa berhasil ditambahkan!', 'jade');
  closeModal(null, 'modal-tambah-mahasiswa');
}

function editMahasiswa(id) { showToast('✏️ Mode edit mahasiswa #' + id, 'sky'); }
function hapusMahasiswa(id) { showToast('🗑️ Mahasiswa dihapus', 'red'); }

function simulateRFID() {
  const chars = 'ABCDEF0123456789';
  let rfid = '';
  for (let i = 0; i < 8; i++) rfid += chars[Math.floor(Math.random() * chars.length)];
  document.getElementById('rfid-new').value = rfid;
  showToast('📡 RFID terdeteksi: ' + rfid, 'jade');
}

function previewFoto(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('foto-img').src = e.target.result;
    document.getElementById('foto-preview').classList.remove('hidden');
  };
  reader.readAsDataURL(file);
}

// ═══════════════════════════════════════════════ ACARA
function renderAcaraList() {
  const container = document.getElementById('acara-list');
  container.innerHTML = acaraData.map(a => {
    const statusClass = { aktif: 'badge-green', upcoming: 'badge-blue', selesai: 'badge-gray' }[a.status] || 'badge-gray';
    return `
    <div class="glass-card overflow-hidden">
      <div class="p-5 cursor-pointer" onclick="toggleAccordion('acara-${a.id}')">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-1">
              <h3 class="font-display font-700 text-white text-base">${a.nama}</h3>
              <span class="badge ${statusClass} text-xs">${a.status}</span>
            </div>
            <div class="flex flex-wrap gap-4 text-sm text-ink-400">
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>${formatDate(a.tanggalMulai)}</span>
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>${a.lokasi}</span>
              <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>${a.agenda.length} agenda</span>
            </div>
          </div>
          <div class="flex gap-2 flex-shrink-0 no-print">
            <button class="btn-green text-xs py-1.5 px-3" onclick="event.stopPropagation();openDetailAcara(${a.id})">Detail</button>
            <button class="btn-secondary text-xs py-1.5 px-3" onclick="event.stopPropagation();showModal('modal-tambah-acara')">Edit</button>
            <svg class="w-5 h-5 text-ink-500 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
          </div>
        </div>
      </div>
      <div id="acara-${a.id}" class="accordion-content">
        <div class="px-5 pb-5 pt-0 border-t border-ink-700">
          <p class="text-ink-400 text-sm mb-4 mt-4">${a.deskripsi}</p>
          <div class="text-xs font-display font-600 uppercase tracking-wider text-ink-500 mb-3">Agenda</div>
          <div class="space-y-2">
            ${a.agenda.map(ag => `
              <div class="flex items-center gap-3 p-3 rounded-xl bg-ink-800">
                <div class="w-2 h-2 rounded-full bg-accent flex-shrink-0"></div>
                <div class="flex-1"><div class="text-white text-sm font-500">${ag.nama}</div><div class="text-ink-400 text-xs">${ag.mulai} – ${ag.selesai} · Batas absen: ${ag.batasAbsen}</div></div>
                <button class="btn-primary text-xs py-1.5 px-3" onclick="navigate('absensi')">Absensi</button>
              </div>`).join('')}
          </div>
          <button class="btn-secondary mt-3 text-xs py-2 px-4" onclick="addAgendaToAcara(${a.id})"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Tambah Agenda</button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function toggleAccordion(id) {
  const el = document.getElementById(id);
  el.classList.toggle('open');
}

function openDetailAcara(id) {
  const a = acaraData.find(x => x.id === id);
  document.getElementById('detail-acara-title').textContent = a.nama;
  document.getElementById('detail-acara-sub').textContent = formatDate(a.tanggalMulai) + ' · ' + a.lokasi;
  document.getElementById('detail-agenda-list').innerHTML = a.agenda.map(ag => `
    <div class="flex items-center justify-between p-4 rounded-xl bg-ink-800 border border-ink-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
        </div>
        <div><div class="text-white font-600 text-sm">${ag.nama}</div><div class="text-ink-400 text-xs">${ag.mulai} – ${ag.selesai} · Batas: ${ag.batasAbsen}</div></div>
      </div>
      <button class="btn-green text-xs py-1.5 px-3" onclick="closeModal(null,'modal-detail-acara');navigate('absensi')">Absensi</button>
    </div>`).join('');
  showModal('modal-detail-acara');
}

function saveAcara() {
  showToast('✓ Acara berhasil disimpan!', 'jade');
  closeModal(null, 'modal-tambah-acara');
}

function addAgendaRow() {
  const row = document.createElement('div');
  row.className = 'agenda-row grid grid-cols-12 gap-2 items-center';
  row.innerHTML = `<input type="text" class="inp col-span-5 text-sm py-2" placeholder="Nama agenda"><input type="time" class="inp col-span-3 text-sm py-2"><input type="time" class="inp col-span-3 text-sm py-2"><button class="col-span-1 text-red-400 hover:text-red-300 flex items-center justify-center" onclick="removeAgenda(this)"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
  document.getElementById('agenda-rows').appendChild(row);
}

function removeAgenda(btn) { btn.closest('.agenda-row').remove(); }

function generateAgenda() {
  const templates = [['Pembukaan & Registrasi','07:30','08:00'],['Sesi Materi I','08:00','10:00'],['Istirahat','10:00','10:30'],['Sesi Materi II','10:30','12:00'],['Ishoma','12:00','13:00'],['Sesi Materi III','13:00','15:00'],['Penutupan','15:00','15:30']];
  const container = document.getElementById('agenda-rows');
  container.innerHTML = '';
  templates.forEach(([nama, mulai, selesai]) => {
    const row = document.createElement('div');
    row.className = 'agenda-row grid grid-cols-12 gap-2 items-center';
    row.innerHTML = `<input type="text" class="inp col-span-5 text-sm py-2" value="${nama}"><input type="time" class="inp col-span-3 text-sm py-2" value="${mulai}"><input type="time" class="inp col-span-3 text-sm py-2" value="${selesai}"><button class="col-span-1 text-red-400 hover:text-red-300 flex items-center justify-center" onclick="removeAgenda(this)"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
    container.appendChild(row);
  });
  showToast('⚡ 7 agenda berhasil di-generate!', 'jade');
}

function addAgendaToAcara(id) { showToast('+ Tambah agenda ke acara #' + id, 'sky'); }

// ═══════════════════════════════════════════════ ABSENSI
function renderAbsensiLog() {
  const tbody = document.getElementById('absensi-tbody');
  tbody.innerHTML = absensiLog.map(a => {
    const statusMap = { hadir: 'badge-green', terlambat: 'badge-orange', 'tidak-hadir': 'badge-red' };
    const statusLabel = { hadir: 'Hadir', terlambat: 'Terlambat', 'tidak-hadir': 'Tidak Hadir' };
    return `<tr><td class="text-white font-500">${a.mahasiswa}</td><td>${a.agenda}</td><td class="${a.masuk !== '-' ? 'text-jade' : 'text-ink-500'}">${a.masuk}</td><td class="${a.pulang !== '-' ? 'text-sky' : 'text-ink-500'}">${a.pulang}</td><td><span class="badge ${statusMap[a.status]}">${statusLabel[a.status]}</span></td></tr>`;
  }).join('');
}

function processAbsensi(type) {
  const rfid = document.getElementById('rfid-input').value;
  const agenda = document.getElementById('absen-agenda').value;
  if (!rfid) { showToast('⚠️ Tempelkan atau masukkan RFID', 'orange'); return; }
  if (!agenda) { showToast('⚠️ Pilih agenda terlebih dahulu', 'orange'); return; }
  const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  const result = document.getElementById('absen-result');
  result.className = 'mt-3 p-3 rounded-xl text-sm text-center';
  if (type === 'masuk') {
    result.className += ' bg-jade/10 text-jade border border-jade/20';
    result.innerHTML = `✓ Absen masuk berhasil · ${now}`;
    showToast('✓ Absen masuk tercatat: ' + rfid, 'jade');
  } else {
    result.className += ' bg-sky/10 text-sky border border-sky/20';
    result.innerHTML = `✓ Absen pulang berhasil · ${now}`;
    showToast('✓ Absen pulang tercatat: ' + rfid, 'sky');
  }
  result.classList.remove('hidden');
  document.getElementById('rfid-input').value = '';
}

// ═══════════════════════════════════════════════ CHARTS
function initCharts() {
  const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
  const hadir = [210, 195, 220, 205, 192, 180, 215];
  const absen = [38, 53, 28, 43, 56, 68, 33];

  new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: {
      labels: days,
      datasets: [
        { label: 'Hadir', data: hadir, backgroundColor: 'rgba(0,200,150,0.7)', borderRadius: 6 },
        { label: 'Absen', data: absen, backgroundColor: 'rgba(255,77,0,0.5)', borderRadius: 6 }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6B6B6B', font: { family: 'Syne', size: 11 } } },
        y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6B6B6B', font: { family: 'Syne', size: 11 } } }
      }
    }
  });

  new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
      labels: ['Hadir', 'Terlambat', 'Tidak Hadir'],
      datasets: [{ data: [192, 24, 32], backgroundColor: ['#00C896', '#FF4D00', '#3D3D3D'], borderWidth: 0, hoverOffset: 6 }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '72%',
      plugins: { legend: { display: false } }
    }
  });
}

function initLaporanChart() {
  const ctx = document.getElementById('laporanChart');
  if (!ctx || ctx._chartDestroyed) return;
  if (ctx._chart) { ctx._chart.destroy(); }
  const c = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
      datasets: [{ label: 'Kehadiran (%)', data: [78, 82, 79, 85, 88, 84], borderColor: '#FF4D00', backgroundColor: 'rgba(255,77,0,0.08)', tension: 0.4, pointBackgroundColor: '#FF4D00', fill: true, borderWidth: 2 }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6B6B6B', font: { family: 'Syne', size: 11 } } },
        y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6B6B6B', font: { family: 'Syne', size: 11 } }, min: 60, max: 100 }
      }
    }
  });
  ctx._chart = c;
}

// ═══════════════════════════════════════════════ MODAL
function showModal(id) { document.getElementById(id).classList.remove('hidden'); }
function closeModal(event, id) {
  if (!event || event.target === document.getElementById(id)) document.getElementById(id).classList.add('hidden');
}

// ═══════════════════════════════════════════════ TOAST
let toastTimer;
function showToast(msg, color = 'jade') {
  clearTimeout(toastTimer);
  const t = document.getElementById('toast');
  const inner = document.getElementById('toast-inner');
  const colors = { jade: 'bg-jade text-ink-900', orange: 'bg-accent text-white', sky: 'bg-sky text-white', red: 'bg-red-500 text-white' };
  inner.className = `flex items-center gap-3 px-5 py-3 rounded-xl shadow-2xl text-sm font-500 ${colors[color] || colors.jade}`;
  inner.textContent = msg;
  t.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
  t.classList.add('opacity-100', 'translate-y-0');
  toastTimer = setTimeout(() => { t.classList.add('opacity-0', 'translate-y-4'); t.classList.remove('opacity-100', 'translate-y-0'); }, 3000);
}

// ═══════════════════════════════════════════════ PRINT
function printSection(id) {
  const el = document.getElementById(id);
  if (!el) { window.print(); return; }
  const win = window.open('', '_blank');
  win.document.write(`<!DOCTYPE html><html><head><title>SiAbsen - Laporan</title>
  <style>
    body { font-family: 'DM Sans', Arial, sans-serif; color: #111; background: #fff; padding: 32px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f0f0f0; padding: 10px 14px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #555; }
    td { padding: 10px 14px; border-bottom: 1px solid #eee; font-size: 13px; color: #333; }
    h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
    .header { border-bottom: 2px solid #FF4D00; padding-bottom: 12px; margin-bottom: 24px; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600; }
    .hadir { background: #e8faf4; color: #00a87a; }
    .terlambat { background: #fff1eb; color: #FF4D00; }
    .tidak-hadir { background: #fef2f2; color: #e00; }
    @media print { body { padding: 0; } }
  </style></head><body>
  <div class="header"><h1>SiAbsen · Laporan Absensi</h1><p style="color:#888;font-size:13px">Dicetak: ${new Date().toLocaleString('id-ID')}</p></div>
  ${el.innerHTML}
  <script>window.onload=()=>{ window.print(); }<\/script>
  </body></html>`);
  win.document.close();
}

// ═══════════════════════════════════════════════ UTILS
function formatDate(d) {
  const date = new Date(d);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>
</body>
</html>
