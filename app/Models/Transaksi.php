<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'id_transaksi',
        'id_kategori',
        'id_divisi',
        'uraian',
        'harga',
        'jenis',
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
