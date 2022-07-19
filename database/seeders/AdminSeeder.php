<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            // idは自動生成なので、設定する必要がない
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
            'created_at' => '2021/01/01 11:11:11' // 練習がてら

            // マイグレーションファイルを参照
            // $table->timestamps(); は created_atとupdated_at の2つ列が作成される。
        ]);
    }
}
