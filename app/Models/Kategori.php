<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];

    public function Transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_kategori');
    }

    public function TransaksiAgregat()
    {
        return $this->hasMany(TransaksiAgregat::class, 'id_kategori');
    }
}
