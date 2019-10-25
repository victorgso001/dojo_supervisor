<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('jkc_registry');
            $table->integer('fbk_registry');
            $table->integer('cbk_registry');
            $table->integer('class_id');
            $table->integer('graduation_id');
            $table->string('name', 255);
            $table->date('birth_date');
            $table->string('father_name', 255);
            $table->string('mother_name', 255);
            $table->string('photo_path', 255);
            $table->stirng('birth_place', 255);
            $table->string('contact_phone', 255);
            $table->string('address', 255);
            $table->integer('house_number', 255);
            $table->string('state', 255);
            $table->string('city', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
