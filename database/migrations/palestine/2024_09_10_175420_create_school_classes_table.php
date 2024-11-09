<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolClassesTable extends Migration
{
    public function up()
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('grade_name');
            $table->integer('grade_level'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_classes');
    }
}
