<?php

use Illuminate\Database\Seeder;

use App\Student;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Student::class, 60)->create();
    }
}
