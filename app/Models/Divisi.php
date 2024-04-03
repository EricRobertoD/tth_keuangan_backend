<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    protected $keyType = 'string';
    protected $fillable = [
        'id_divisi',
        'nama_divisi',
    ];

    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_divisi');
    }
    
    public function TransaksiAgregat()
    {
        return $this->hasMany(TransaksiAgregat::class, 'id_divisi');
    }
}
