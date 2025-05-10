<?php

namespace App\Http\Controllers;
use App\Models\EligibleMipa;
use App\Models\EligibleIps;
use App\Exports\EligibleMipaExport;
use App\Exports\EligibleIpsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


use Illuminate\Http\Request;

class EligibleController extends Controller
{
    public function exportMipaExcel()
    {
        return Excel::download(new EligibleMipaExport, 'eligible_mipa.xlsx');
    }

    public function exportIpsExcel()
    {
        return Excel::download(new EligibleIpsExport, 'eligible_ips.xlsx');
    }

    public function exportMipaPdf()
    {
        $data = EligibleMipa::with('siswa.kelas')->get();
        $pdf = Pdf::loadView('pdf.eligible', ['data' => $data, 'jurusan' => 'MIPA']);
        return $pdf->download('eligible_mipa.pdf');
    }

    public function exportIpsPdf()
    {
        $data = EligibleIps::with('siswa.kelas')->get();
        $pdf = Pdf::loadView('pdf.eligible', ['data' => $data, 'jurusan' => 'IPS']);
        return $pdf->download('eligible_ips.pdf');
    }
}
