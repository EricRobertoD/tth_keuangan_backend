<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = '{
            "data": [
                {
                    "id_kategori": 51332004,
                    "nama_kategori": "BODP alat Ukur Jaringan"
                },
                {
                    "id_kategori": 51351001,
                    "nama_kategori": "BODP PD Dalam Negeri"
                },
                {
                    "id_kategori": 51508001,
                    "nama_kategori": "Beban Penelitian\/Riset"
                },
                {
                    "id_kategori": 51346003,
                    "nama_kategori": "BODP Peralatan Kantor"
                },
                {
                    "id_kategori": 51367118,
                    "nama_kategori": "Beban Extra Voeding & Preventif Kesehatan Operasional"
                },
                {
                    "id_kategori": 51505011,
                    "nama_kategori": "Beban Quality Management"
                },
                {
                    "id_kategori": 51506004,
                    "nama_kategori": "Beban Pembelian Buku dan Langganan Media Masa"
                },
                {
                    "id_kategori": 51508006,
                    "nama_kategori": "Beban Jasa Uji Mutu"
                },
                {
                    "id_kategori": 51512005,
                    "nama_kategori": "Beban Rapat"
                }
            ]
        }';

        $data = json_decode($json, true);

        foreach ($data['data'] as $item) {
            Kategori::updateOrCreate(
                ['id_kategori' => $item['id_kategori']],
                ['nama_kategori' => $item['nama_kategori']]
            );
        }
    }
}
