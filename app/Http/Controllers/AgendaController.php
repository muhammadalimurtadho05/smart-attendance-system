<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Acara;
use App\Models\Agenda;
use App\Models\Divisi;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

date_default_timezone_set('Asia/Jakarta');
class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($acara_id)
    {
        $acara_id = decrypt($acara_id);
        $agenda = DB::table('agenda')->where('acara_id', '=', $acara_id)->get();
        $namaacara = Acara::find($acara_id, ['nama', 'id']);
        $divisi = Divisi::all()->where('acara_id', '=', $acara_id);
        $panitia = DB::table('users')
                ->join('acara_user', 'users.id', '=', 'acara_user.user_id')
                ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
                ->where('acara_user.acara_id', '=', $acara_id)->get();

        return view('absensi.agenda', compact('agenda', 'namaacara', 'divisi', 'panitia'));

    }

    public function checkin($agenda_id)
    {
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::find($agenda_id);
        $absensi = DB::table('absensi')
            ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
            ->join('acara_user', 'acara_user.user_id', '=', 'users.id')
            ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
            ->where('agenda_id', '=', $agenda_id)->get();
        return view('absensi.checkin', compact('agenda', 'absensi'));
    }

    public function checkout($agenda_id)
    {
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::find($agenda_id);
        $absensi = DB::table('absensi')
            ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
            ->join('acara_user', 'acara_user.user_id', '=', 'users.id')
            ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
            ->where('agenda_id', '=', $agenda_id)->get();

        return view('absensi.checkout', compact('agenda', 'absensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'checkin' => 'required',
            'batas_checkin' => 'required',
            'checkout' => 'required',
            'batas_checkout' => 'required',
            'acara_id' => 'required',

        ]);

        $completed_payload = array_merge($data, [
            'checkin' => Carbon::parse($request->input('checkin')),
            'batas_checkin' => Carbon::parse($request->input('batas_checkin')),
            'checkout' => Carbon::parse($request->input('checkout')),
            'batas_checkout' => Carbon::parse($request->input('batas_checkout')),

        ]);

        Agenda::create($completed_payload);

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($request->input('acara_id'))]);

    }

    public function checkin_panitia(Request $request, $id_agenda)
    {
        $nowImmutable = new DateTimeImmutable;
        $jam = $nowImmutable->format('Y-m-d H:i:s');
        $rfid = $request->input('rfid');
        $user = DB::table('users')->where('rfid_uid', $rfid)->first();
        
        // Cek apakah user terdaftar
        if (!$user) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kartu RFID tidak terdaftar!',
                ]);
            }
            return redirect()->route('checkin', $id_agenda)->with('error', 'Kartu RFID tidak terdaftar!');
        }

        $cek_panitia = DB::table('acara_user')->where('user_id', '=', $user->id)->first();
        if(!$cek_panitia){
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia tidak terdaftar di acara ini!',
                ]);
            }
        }
        $cek_absensi = Absensi::where('rfid_uid', '=', $rfid)
                        ->where('agenda_id', '=', $id_agenda)
                        ->first();
        if($cek_absensi){
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia sudah melakukan absensi!',
                 ]);
                }
            }
        Absensi::create([
            'agenda_id' => $id_agenda,
            'rfid_uid' => $rfid,
            'waktu_masuk' => $jam,
            'status' => 'hadir',
            'keterangan' => '-',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            try {
                $absensi = DB::table('absensi')
                ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
                ->join('acara_user', 'acara_user.user_id', '=', 'users.id')
                ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
                ->where('agenda_id', '=', $id_agenda)->orderBy('waktu_masuk', 'desc')->get();
                $agenda = DB::table('agenda')->where('id', '=', $id_agenda)->first();

                $htmlTabel = view('partials.tabel_absensi', compact('absensi', 'agenda'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil scan!',
                    'html' => $htmlTabel,
                    'nama' => $user->name,
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error PHP: '.$e->getMessage().' di baris '.$e->getLine(),
                ], 200);
            }
        }

        return redirect()->route('checkin', $id_agenda);
    }
    public function checkout_panitia(Request $request, $id_agenda)
    {
        $nowImmutable = new DateTimeImmutable;
        $jam = $nowImmutable->format('Y-m-d H:i:s');
        $rfid = $request->input('rfid');
        $user = DB::table('users')->where('rfid_uid', $rfid)->first();
        
        // Cek apakah user terdaftar
        if (!$user) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kartu RFID tidak terdaftar!',
                ]);
            }
            return redirect()->route('checkin', $id_agenda)->with('error', 'Kartu RFID tidak terdaftar!');
        }

        $cek_panitia = DB::table('acara_user')->where('user_id', '=', $user->id)->first();
        if(!$cek_panitia){
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia tidak terdaftar di acara ini!',
                ]);
            }
        }
        $cek_absensi = Absensi::where('rfid_uid', '=', $rfid)
                        ->where('agenda_id', '=', $id_agenda)
                        ->where('waktu_pulang', '!=', null)
                        ->first();
        if($cek_absensi){
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panitia sudah checkout!',
                 ]);
                }
        }
        $abs = Absensi::where('rfid_uid', '=', $rfid)->first();
        $abs->update(['waktu_pulang' => $jam]);

        if ($request->wantsJson() || $request->ajax()) {
            try {
                $absensi = DB::table('absensi')
                ->join('users', 'absensi.rfid_uid', '=', 'users.rfid_uid')
                ->join('acara_user', 'acara_user.user_id', '=', 'users.id')
                ->join('divisi', 'divisi.id', '=', 'acara_user.divisi_id')
                ->where('agenda_id', '=', $id_agenda)->orderBy('waktu_masuk', 'desc')->get();
                $agenda = DB::table('agenda')->where('id', '=', $id_agenda)->first();

                $htmlTabel = view('partials.tabel_checkout', compact('absensi', 'agenda'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil scan!',
                    'html' => $htmlTabel,
                    'nama' => $user->name,
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error PHP: '.$e->getMessage().' di baris '.$e->getLine(),
                ], 200);
            }
        }

        return redirect()->route('checkout', $id_agenda);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'checkin' => 'required',
            'batas_checkin' => 'required',
            'checkout' => 'required',
            'batas_checkout' => 'required',
        ]);

        $acara = Agenda::findOrFail($id);
        $acara->update($data);

        return redirect()->route('acara.agenda', ['acara_id' => encrypt($request->input('acara_id'))]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function daftarAbsensi($agenda_id){
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::find($agenda_id);
        $acara = Acara::find($agenda->acara_id);
        $absensi = DB::table('acara_user')
                    ->join('users', 'users.id', '=', 'acara_user.user_id')
                    ->leftJoin('absensi', function ($join) use ($agenda_id) {
                        $join->on('users.rfid_uid', '=', 'absensi.rfid_uid')
                             ->where('absensi.agenda_id', '=', $agenda_id);
                    })
                    ->join('divisi', 'acara_user.divisi_id', '=', 'divisi.id')
                    ->where('acara_user.acara_id', '=', $agenda->acara_id)
                    ->select(
                        'users.id as user_id',
                        'users.name as name',
                        'users.rfid_uid as rfid_uid',
                        'users.email as email',
                        'divisi.nama as nama_divisi',
                        'absensi.status',
                        'absensi.waktu_masuk',
                        'absensi.waktu_pulang',
                        'absensi.keterangan'
                    )
                    ->orderBy('acara_user.divisi_id', 'asc')
                    ->get();

        $stats = [
            'total' => $absensi->count(),
            'hadir' => $absensi->whereIn('status', ['hadir', 'terlambat'])->count(),
            'tidak_hadir' => $absensi->filter(fn($x) => is_null($x->status) || $x->status === 'tidak_hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
        ];

        $data = [
            'title' => 'Laporan Absensi - ' . $agenda->nama,
            'agenda' => $agenda->nama, 
            'acara' => $acara->nama, 
            'absensi' => $absensi,
            'stats' => $stats,
            'date' => date('d-m-Y H:i:s'),
        ];
        
        return view('absensi.absensi', compact('absensi', 'acara', 'agenda', 'stats'));
    }

    public function updateAbsensi(Request $request)
    {
        $request->validate([
            'agenda_id' => 'required|exists:agenda,id',
            'rfid_uid' => 'required|string',
            'status' => 'required|in:hadir,terlambat,izin,tidak_hadir',
            'waktu_masuk' => 'nullable|string',
            'waktu_pulang' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $agenda = Agenda::findOrFail($request->agenda_id);
        $dateStr = $agenda->checkin ? $agenda->checkin->format('Y-m-d') : now()->format('Y-m-d');

        $waktu_masuk = null;
        if ($request->status === 'hadir' || $request->status === 'terlambat') {
            if ($request->waktu_masuk) {
                $waktu_masuk = $dateStr . ' ' . $request->waktu_masuk . ':00';
            } else {
                $waktu_masuk = now()->format('Y-m-d H:i:s');
            }
        }

        $waktu_pulang = null;
        if ($request->status === 'hadir' || $request->status === 'terlambat') {
            if ($request->waktu_pulang) {
                $waktu_pulang = $dateStr . ' ' . $request->waktu_pulang . ':00';
            }
        }

        Absensi::updateOrCreate(
            [
                'agenda_id' => $request->agenda_id,
                'rfid_uid' => $request->rfid_uid,
            ],
            [
                'waktu_masuk' => $waktu_masuk,
                'waktu_pulang' => $waktu_pulang,
                'status' => $request->status,
                'keterangan' => $request->keterangan ?? '-',
            ]
        );

        return redirect()->back()->with('success', 'Kehadiran berhasil diperbarui!');
    }
}
