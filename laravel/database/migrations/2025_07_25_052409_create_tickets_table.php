<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tiket')->unique(); // Kolom untuk menyimpan kode tiket yang unik
            $table->string('hadiah')->nullable(); // Kolom untuk menyimpan hadiah yang terkait dengan tiket
            $table->boolean('is_used')->default(false); // Kolom untuk status apakah tiket sudah digunakan
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
        Schema::dropIfExists('tickets');
    }
}
