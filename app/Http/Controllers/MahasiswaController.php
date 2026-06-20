<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = User::query()
            ->when($request->search, fn ($q, $v) => $q->where('name', 'like', "%$v%")
                ->orWhere('email', 'like', "%$v%")
                ->orWhere('rfid_uid', 'like', "%$v%")
            )
            ->when($request->divisi, fn ($q, $v) => $q->whereHas('acara', fn ($q) => $q->wherePivot('divisi_id', $v)
            )
            )
            ->paginate(20);

        return view('absensi.mahasiswa', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'rfid_uid' => 'required|string|unique:users',
        ]);
        $completed_payload = array_merge($data, [
            'password' => '123',
            'is_active' => '1',
        ]);

        User::create($completed_payload);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);
        $completed_payload = array_merge($data, [
            'is_active' => $request->input('status')
        ]);

        $mhs = User::findOrFail($id);
        $mhs->update($completed_payload);
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa diperbarui.');
    }
}
