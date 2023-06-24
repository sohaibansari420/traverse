<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unprocessed_data', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('method');
            $table->longText('data');
            $table->tinyInteger('is_processed')->default(0);
            $table->bigInteger('time_period_hours')->default(24);
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
        Schema::dropIfExists('unprocessed_data');
    }
};
