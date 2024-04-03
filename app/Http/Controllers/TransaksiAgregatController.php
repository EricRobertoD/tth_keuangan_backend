<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiAgregat;
use App\Models\Transaksi;
use DB;
use Carbon\Carbon;

class TransaksiAgregatController extends Controller
{
    
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $transaksiAgregat = TransaksiAgregat::when($bulan, function ($query) use ($bulan) {
            return $query->where('bulan', $bulan);
        })->when($tahun, function ($query) use ($tahun) {
            return $query->where('tahun', $tahun);
        })->with('Divisi', 'Kategori')->get();

        $totals = DB::table('transaksi_agregat')
            ->select('id_kategori', 'bulan', 'tahun', 
                     DB::raw('SUM(release_tth) as total_release'), 
                     DB::raw('SUM(realisasi) as total_realisasi'), 
                     DB::raw('SUM(saldo_akhir) as total_saldo_akhir'))
            ->when($bulan, function ($query) use ($bulan) {
                return $query->where('bulan', $bulan);
            })
            ->when($tahun, function ($query) use ($tahun) {
                return $query->where('tahun', $tahun);
            })
            ->groupBy('id_kategori', 'bulan', 'tahun')
            ->get();

        if (count($transaksiAgregat) > 0) {
            return response([
                'status' => 'success',
                'data' => $transaksiAgregat,
                'totals' => $totals,
            ], 200);
        }

        return response([
            'status' => 'error',
            'message' => 'Empty',
            'data' => null,
            'totals' => null,
        ], 400);
    }
}
