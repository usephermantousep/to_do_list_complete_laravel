<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthlies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('task');
            $table->integer('bulan');
            $table->string('tipe');
            $table->integer('value_plan');
            $table->integer('value_actual')->default(0);
            $table->integer('status_non')->default(0);
            $table->double('status_result')->default(0);
            $table->double('value')->default(0);
            $table->integer('is_update')->default(0);
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
        Schema::dropIfExists('monthlies');
    }
}
