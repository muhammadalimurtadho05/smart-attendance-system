<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Models\Acara;
use App\Models\Agenda;
use Illuminate\Support\Facades\DB;

date_default_timezone_set('Asia/Jakarta');

class PDFreportingController extends Controller
{
    public function generatePDF($agenda_id)
    {   
        $agenda_id = decrypt($agenda_id);
        $agenda = Agenda::find($agenda_id);
        $acara = Acara::find($agenda->acara_id);
        $absensi = DB::table('acara_user')
                    ->join('users', 'users.id', '=', 'acara_user.user_id')
                    ->rightJoin('absensi', 'acara_user.user_id', '=', 'absensi.id')
                    ->join('divisi', 'acara_user.divisi_id', '=', 'divisi.id')
                    ->where('absensi.agenda_id', '=',$agenda_id)
                    ->orderBy('acara_user.divisi_id', 'asc')->get()
                    ;

        $data = [
            'title' => 'Daftar Absensi',
            'agenda' => $agenda->nama, 
            'acara' => $acara->nama, 
            'absensi' => $absensi,
            'date' => date('d-m-Y H:i:s'),
        ];
        
        // Load the blade view and pass data
        $pdf = Pdf::loadView('report.absensi', $data);
        
        // Optional: Set paper size and orientation (e.g., portrait or landscape)
        $pdf->setPaper('A4', 'landscape');

        // Stream the PDF directly in the browser
        return $pdf->stream('document.pdf');
        
        // OR: Force a file download instead
        // return $pdf->download('invoice.pdf');
    }
}
