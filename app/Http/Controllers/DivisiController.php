<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        'deskripsi'        => 'required',
        ]);
        $completed_payload = array_merge($data, [ 'acara_id' => $request->input('acara_id')]);
        Divisi::create($completed_payload);
        return redirect()->route('acara.agenda', $request->input('acara_id'));

    }

    public function divisiAgenda($divisi_id){
        $panitia = DB::table('acara_user')
                    ->join('users', 'acara_user.user_id', '=', 'users.id')
                    ->where('acara_user.divisi_id', '=', $divisi_id)->get();
        return view('absensi.panitia', compact('panitia'));
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
