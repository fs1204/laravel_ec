<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            [
                'owner_id' => 1,    // ownersテーブルにあるidに限定される
                'filename' => 'sample1.jpg',
                'title' => null,
            ],
            [
                'owner_id' => 1,    // ownersテーブルにあるidに限定される
                'filename' => 'sample2.jpg',
                'title' => null,
            ],
            [
                'owner_id' => 1,    // ownersテーブルにあるidに限定される
                'filename' => 'sample3.jpg',
                'title' => null,
            ],
            [
                'owner_id' => 1,    // ownersテーブルにあるidに限定される
                'filename' => 'sample4.jpg',
                'title' => null,
            ],
            [
                'owner_id' => 1,    // ownersテーブルにあるidに限定される
                'filename' => 'sample5.jpg',
                'title' => null,
            ],
            [
                'owner_id' => 1,    // ownersテーブルにあるidに限定される
                'filename' => 'sample6.jpg',
                'title' => null,
            ],
        ]);
    }
}
