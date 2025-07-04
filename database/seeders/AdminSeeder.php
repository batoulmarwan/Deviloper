<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name'=>'Batoul',
            'email'=>'btwl46693@gmail.com',
            'password'=>Hash::make('1234gdQ5#'),
        ]);
    }
}
