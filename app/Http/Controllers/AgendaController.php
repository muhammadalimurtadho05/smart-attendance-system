<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index($acara_id)
    {
        $agenda = DB::table('agenda')->where('acara_id','=', $acara_id)->get();
        $namaacara = Acara::find($acara_id, ['nama','id']);
        return view('absensi.agenda', compact('agenda', 'namaacara'));

    }
    public function checkin($agenda_id){
        $agenda = Agenda::find($agenda_id);
        return view('absensi.checkin', compact('agenda'));
    }
    public function checkout($agenda_id){
        $agenda = Agenda::find($agenda_id);
        return view('absensi.checkout', compact('agenda'));
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
        'nama'             => 'required|string|max:255',
        'checkin'        => 'required',
        'batas_checkin'    => 'required',
        'checkout'    => 'required',
        'batas_checkout'    => 'required',
        'acara_id'    => 'required',

        ]);

        $completed_payload = array_merge($data, [
            'checkin'        => \Carbon\Carbon::parse($request->input('checkin')),
            'batas_checkin'  => \Carbon\Carbon::parse($request->input('batas_checkin')),
            'checkout'       => \Carbon\Carbon::parse($request->input('checkout')),
            'batas_checkout' => \Carbon\Carbon::parse($request->input('batas_checkout')),

        ]);

        // echo $completed_payload['checkin'];
        Agenda::create($completed_payload);
        return redirect()->route('acara.agenda', $request->input('acara_id'));

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
