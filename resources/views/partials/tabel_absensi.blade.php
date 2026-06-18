@forelse($absensi as $idx => $mhs)
    <tr>
        <td>{{ $idx + 1 }}</td>
        <td>{{ $mhs->name }}</td>
        <td>{{ $mhs->nama }}</td>
        <td>{{ $mhs->waktu_masuk }}</td>
        <td>
            @if (\Carbon\Carbon::parse($mhs->waktu_masuk)->greaterThan(\Carbon\Carbon::parse($agenda->batas_checkin)))
                <span class="badge badge-orange text-xs">Terlambat</span>
            @else
                <span class="badge badge-green text-xs">Tepat Waktu</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="p-3 text-center text-slate-500">Belum ada data absensi.</td>
    </tr>
@endforelse
