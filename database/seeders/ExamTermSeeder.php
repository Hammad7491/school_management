<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamTerm;

class ExamTermSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Mid Term', 'Second Term', 'Final Term'] as $name) {
            ExamTerm::firstOrCreate(['name' => $name]);
        }
    }
}
