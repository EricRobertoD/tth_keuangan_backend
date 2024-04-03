<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index(){
        $divisi = Divisi::orderBy('created_at', 'asc')->get();

        if(count($divisi) > 0){
            return response([
                'data' => $divisi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); 
    }
}
