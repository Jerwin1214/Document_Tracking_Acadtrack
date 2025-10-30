<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $docs = [
            '2x2 Picture',
            'PSA',
            'Photocopy and Original Card',
            'Baptismal Certificate',
            'Good Moral'
        ];

        foreach($docs as $doc){
            Document::firstOrCreate(['name' => $doc]);
        }
    }
}
