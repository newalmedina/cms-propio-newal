<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table): void {
            $table->id();

            $table->string("site_name");
            $table->string("site_active")->default(1);
            // $table->string("image")->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();

            $table->unsignedBigInteger("province_id")->nullable();
            $table->unsignedBigInteger("municipio_id")->nullable();

            $table->timestamps();

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onDelete('cascade');

            $table->foreign('municipio_id')
                ->references('id')
                ->on('municipios')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
