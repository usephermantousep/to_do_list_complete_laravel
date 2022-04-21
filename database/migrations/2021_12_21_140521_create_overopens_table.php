<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOveropensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overopens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('week');
            $table->integer('year');
            $table->integer('daily')->default(0);
            $table->integer('weekly')->default(0);
            $table->integer('monthly')->default(0);
            $table->integer('point');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overopens');
    }
}
