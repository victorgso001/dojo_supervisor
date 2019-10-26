<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('student_id');
            $table->integer('graduation_id');
            $table->bigInteger('jkc_registry');
            $table->bigInteger('fbk_registry');
            $table->bigInteger('cbk_registry');
            $table->string('photo_path', 255);
            $table->string('rg', 255);
            $table->string('cpf', 255);
            $table->name('name', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
