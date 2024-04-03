<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = '{
            "data": [
                {
                    "id_divisi": "IRA",
                    "nama_divisi": "IRA"
                },
                {
                    "id_divisi": "UREL",
                    "nama_divisi": "UREL"
                },
                {
                    "id_divisi": "IQA",
                    "nama_divisi": "IQA"
                },
                {
                    "id_divisi": "DEQA",
                    "nama_divisi": "DEQA"
                },
                {
                    "id_divisi": "SIR",
                    "nama_divisi": "SIR"
                },
                {
                    "id_divisi": "BAN",
                    "nama_divisi": "BAN"
                },
                {
                    "id_divisi": "ISR",
                    "nama_divisi": "ISR"
                },
                {
                    "id_divisi": "FMC",
                    "nama_divisi": "FMC"
                }
            ]
        }';

        $data = json_decode($json, true);

        foreach ($data['data'] as $item) {
            Divisi::updateOrCreate(
                ['id_divisi' => $item['id_divisi']],
                ['nama_divisi' => $item['nama_divisi']]
            );
        }
    }
}
