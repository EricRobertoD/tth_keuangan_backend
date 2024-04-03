<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiAgregat extends Model
{
    use HasFactory;
    protected $table = 'transaksi_agregat';
    protected $primaryKey = 'id_transaksi_agregat';
    protected $fillable = [
        'id_transaksi_agregat',
        'id_kategori',
        'id_divisi',
        'bulan',
        'tahun',
        'release_tth',
        'realisasi',
        'saldo_akhir',
    ];
    
    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function Kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
