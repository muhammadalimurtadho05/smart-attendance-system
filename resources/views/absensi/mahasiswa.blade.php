@extends('layouts.app')
@section('main-content')
    <div id="page-mahasiswa" class="page active">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div class="flex gap-2 flex-1 flex-wrap">
                <input type="text" class="inp max-w-xs" placeholder="Cari nama / RFID / email..."
                    oninput="filterTable(this.value)">
            </div>
            <div class="flex gap-2">
                <button class="btn-primary" onclick="showModal('modal-tambah-mahasiswa')">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Mahasiswa
                </button>
            </div>
        </div>

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
                    @foreach ($mahasiswa as $idx => $mhs)
                    @php
                        $aktif = $mhs->is_active == '1' ? 'Aktif' : 'Nonaktif';
                    @endphp
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
                            <td>{{ $mhs->email }} {{ $mhs->is_active }}</td>
                            <td>
                                <span class="badge {{ $mhs->is_active == '1' ? 'badge-green' : 'badge-gray' }}">
                                    {{ $aktif }}
                                </span>
                            </td>
                            <td class="no-print">
                                <div class="flex gap-2">
                                    <button class="btn-secondary py-1.5 px-3 text-xs btn-edit" onclick="showEditModal('{{ $mhs->id }}', '{{ $mhs->name }}', '{{ $mhs->rfid_uid }}', '{{ $mhs->email }}', '{{ $aktif }}' )" >Edit</button>
                                    <button class="btn-danger py-1.5 px-3 text-xs">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-tambah-mahasiswa" class="modal-overlay hidden" onclick="closeModal(event, 'modal-tambah-mahasiswa')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display font-800 text-slate-900 text-xl">Tambah Mahasiswa</h2>
                    <p class="text-slate-500 text-sm mt-0.5">Daftarkan mahasiswa baru dengan RFID</p>
                </div>
                <button onclick="closeModal(null,'modal-tambah-mahasiswa')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>
            <form action="{{ route('mahasiswa.store') }}" method="POST">
                <div
                    class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-4 mb-5 text-center relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                        <div class="w-48 h-48 border-2 border-blue-600 rounded-full"></div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-2 border-blue-600 mx-auto flex items-center justify-center mb-2"
                        id="rfid-pulse">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm mb-3">Tempelkan kartu RFID untuk scan otomatis</p>
                    <b>
                        <span class="text-slate-500 text-sm mb-3">RFID UID :</span>
                        <span class="text-blue-500 text-sm mb-3" id="inner-rfid"></span>
                        <br>
                    </b>

                    <div class="flex gap-2 items-center max-w-xs mx-auto">
                        <input style="opacity:0;" type="text" id="rfid-new" class="inp  text-center"
                            placeholder="RFID ID" maxlength="16" autofocus name="rfid_uid" autocomplete="off" required>
                        <button class="btn-primary py-2 px-3 text-sm" id="scan" type="button">Scan</button>
                    </div>
                </div>

                <div class="space-y-4 mb-4">
                    <div>
                        <label>Nama Lengkap</label>
                        <input type="text" required class="inp" name="name" placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <div>
                            <label>Email</label>
                            <input required type="email" class="inp" name="email" placeholder="email@kampus.ac.id">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-tambah-mahasiswa')">
                        Batal
                    </button>
                    <button class="btn-primary flex-1 justify-center" type="submit">
                        Simpan Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="modal-edit-panitia" class="modal-overlay hidden" onclick="closeModal(event, 'modal-edit-panitia')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display font-800 text-slate-900 text-xl">Edit Mahasiswa</h2>
                    <p class="text-slate-500 text-sm mt-0.5"></p>
                </div>
                <button onclick="closeModal(null,'modal-edit-panitia')"
                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-900 transition">
                    ✕
                </button>
            </div>
            <form action="" method="POST" id="form-edit-mahasiswa">
                <div
                    class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-4 mb-5 text-center relative overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                        <div class="w-48 h-48 border-2 border-blue-600 rounded-full"></div>
                    </div>
                    <div class="w-12 h-12 rounded-full border-2 border-blue-600 mx-auto flex items-center justify-center mb-2"
                        id="rfid-pulse">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm mb-3">RFID</p>
                    <b>
                        <span class="text-slate-500 text-sm mb-3">RFID UID : </span>
                        <span class="text-blue-500 text-sm mb-3" id="rfid"></span>
                        <br>
                    </b>
                </div>

                <div class="space-y-4 mb-4">
                    <div>
                        <label>Nama Lengkap</label>
                        <input type="text" required class="inp" id="nama" name="name" placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <div>
                            <label>Email</label>
                            <input required type="email" class="inp" name="email" id="email" placeholder="email@kampus.ac.id">
                        </div>
                    </div>
                    <div>
                        <div>
                            <label>Status</label>
                            <select name="status" id="status" class="inp">
                                <option id="Aktif" value="1">Aktif</option>
                                <option id="Nonaktif" value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button class="btn-secondary flex-1 justify-center" onclick="closeModal(null,'modal-edit-panitia')">
                        Batal
                    </button>
                    <button class="btn-primary flex-1 justify-center" type="submit">
                        Simpan Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const btn_scan = document.getElementById('scan');
        const inputRfid = document.getElementById('rfid-new')
        const innerRfid = document.getElementById('inner-rfid')
        const rfidpulse = document.getElementById('rfid-pulse')

        inputRfid.addEventListener('focus', (e) => {
            rfidpulse.classList.add('rfid-pulse')
        })
        inputRfid.addEventListener('blur', (e) => {
            rfidpulse.classList.remove('rfid-pulse');
        });
        btn_scan.addEventListener('click', (e) => {
            inputRfid.focus();

        })

        let lastKeyTime = 0;
        inputRfid.addEventListener('keydown', function(e) {
            const currentTime = Date.now();
            if (e.key === 'Enter') {
                e.preventDefault();
            }
            innerRfid.textContent = inputRfid.value
            const timeDifference = currentTime - lastKeyTime;

            if (timeDifference > 50 && e.key !== 'Enter') {
                inputRfid.value = '';
            }

            lastKeyTime = currentTime;
        });

        // Edit
        function showEditModal(id, nama, rfid, email, status) {
            document.getElementById('form-edit-mahasiswa').action = `/mahasiswa/update/${id}`
            document.getElementById('rfid').innerHTML = rfid;
            console.log(document.getElementById('form-edit-mahasiswa').action)
            document.getElementById('nama').value = nama;
            document.getElementById('email').value = email;
            document.getElementById(status).setAttribute('selected', '')  ;
            // console.log(status)
            // console.log()
            // document.getElementById('edit-agenda-checkin').value = checkin || '-';
            // document.getElementById('edit-agenda-batas-checkin').value = batasCheckin || '-';
            // document.getElementById('edit-agenda-checkout').value = checkout || '-';
            // document.getElementById('edit-agenda-batas-checkout').value = batasCheckout || '-';

            showModal('modal-edit-panitia');
        }
    </script>
@endsection
