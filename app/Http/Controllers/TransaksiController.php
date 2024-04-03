<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $transaksi = Transaksi::when($bulan, function ($query) use ($bulan) {
            return $query->whereMonth('date', $bulan);
        })->when($tahun, function ($query) use ($tahun) {
            return $query->whereYear('date', $tahun);
        })->with('Divisi', 'Kategori')->orderby('date', 'DESC')->get();

        if (count($transaksi) > 0) {
            return response([
                'status' => 'success',
                'data' => $transaksi
            ], 200);
        }

        return response([
            'status' => 'error',
            'message' => 'Empty',
            'data' => null
        ], 400);
    }


    public function getTotalSum()
    {
        $totals = \DB::table('transaksi_agregat')
            ->select(
                'bulan',
                'tahun',
                'id_divisi',
                \DB::raw('SUM(release_tth) AS total_release'),
                \DB::raw('SUM(realisasi) AS total_realisasi'),
                \DB::raw('SUM(saldo_akhir) AS total_saldo')
            )
            ->groupBy('bulan', 'tahun', 'id_divisi')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $totals,
        ], 200);
    }
    
    public function getTotalPenyerapan()
{
    $totals = \DB::table('transaksi_agregat')
        ->select(
            \DB::raw('CONCAT_WS("-", 
                CASE 
                    WHEN bulan >= 1 AND bulan <= 3 THEN "January-March"
                    WHEN bulan >= 4 AND bulan <= 6 THEN "April-June"
                    WHEN bulan >= 7 AND bulan <= 9 THEN "July-September"
                    WHEN bulan >= 10 AND bulan <= 12 THEN "October-December"
                END
            ) AS periode'),
            'tahun',
            \DB::raw('SUM(release_tth) AS total_release'),
            \DB::raw('SUM(realisasi) AS total_realisasi')
        )
        ->groupBy('tahun', \DB::raw('(bulan-1) DIV 3'), 'periode')
        ->get();

    $result = [];

    foreach ($totals as $total) {
        if ($total->total_release != 0) {
            $total_penyerapan = $total->total_realisasi / $total->total_release * 100;
            $result[] = [
                'bulan' => $total->periode,
                'tahun' => $total->tahun,
                'total_penyerapan' => round($total_penyerapan, 2) . '%',
            ];
        } else {
            $result[] = [
                'bulan' => $total->periode,
                'tahun' => $total->tahun,
                'total_penyerapan' => 'N/A',
            ];
        }
    }

    return response()->json([
        'status' => 'success',
        'data' => $result,
    ], 200);
}

    



    public function release(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'input_value' => 'required|numeric|min:0',
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1970',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $idKategori = $request->input('id_kategori');
        $inputValue = $request->input('input_value');
        $idDivisi = $request->input('id_divisi');
        $uraian = $request->input('uraian');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $date = new DateTime("$tahun-$bulan-01");

        $transaksiData = [
            'id_kategori' => $idKategori,
            'id_divisi' => $idDivisi,
            'uraian' => $uraian,
            'harga' => $inputValue,
            'jenis' => 'release',
            'date' => $date->format('Y-m-d H:i:s'),
        ];

        Transaksi::insert($transaksiData);

        return response([
            'status' => 'success',
            'data' => $transaksiData,
        ], 200);
    }

    // private function getDivisiPercentages($idKategori)
    // {
    //     switch ($idKategori) {
    //         case '51332004':
    //             return [
    //                 'BAN' => 0.1205083260297984,
    //                 'DEQA' => 0.1511831726555653,
    //                 'FMC' => 0.1205083260297984,
    //                 'IQA' => 0.2267747589833479,
    //                 'IRA' => 0,
    //                 'ISR' => 0.1205083260297984,
    //                 'SIR' => 0.1205083260297984,
    //                 'UREL' => 0.1400087642418931,
    //             ];
    //         case '51351001':
    //             return [
    //                 'BAN' => 0.078125,
    //                 'DEQA' => 0.15625,
    //                 'FMC' => 0.078125,
    //                 'IQA' => 0.28125,
    //                 'IRA' => 0.171875,
    //                 'ISR' => 0.078125,
    //                 'SIR' => 0.078125,
    //                 'UREL' => 0.078125,
    //             ];
    //         case '51508001':
    //             return [
    //                 'BAN' => 0.1250289084181314,
    //                 'DEQA' => 0.1582735892691952,
    //                 'FMC' => 0.1250289084181314,
    //                 'IQA' => 0.1582735892691952,
    //                 'IRA' => 0.0333747687326549,
    //                 'ISR' => 0.1250289084181314,
    //                 'SIR' => 0.1167177382053654,
    //                 'UREL' => 0.1582735892691952,
    //             ];
    //         default:
    //             return null;
    //     }
    // }

    public function realisasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'input_value' => 'required|numeric|min:0',
            'id_divisi' => in_array($request->input('id_kategori'), ['51332004', '51346003', '51508001']) ? 'required|exists:divisi,id_divisi' : '',
            'uraian' => 'nullable',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1970',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $idKategori = $request->input('id_kategori');
        $inputValue = $request->input('input_value');
        $idDivisi = $request->input('id_divisi');
        $uraian = $request->input('uraian');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $date = new DateTime("$tahun-$bulan-01");

        $transaksiData = [
            'id_kategori' => $idKategori,
            'id_divisi' => $idDivisi,
            'uraian' => $uraian,
            'harga' => $inputValue,
            'jenis' => 'realisasi',
            'date' => $date->format('Y-m-d H:i:s'),
        ];

        Transaksi::insert($transaksiData);

        return response([
            'status' => 'success',
            'data' => $transaksiData,
        ], 200);
    }



    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return response([
            'status' => 'success',
            'message' => 'Transaksi deleted successfully',
            'data' => $transaksi
        ], 200);
    }
}
