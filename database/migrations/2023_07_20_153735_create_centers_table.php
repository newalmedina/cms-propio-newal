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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();

            $table->string("name");
            $table->text("description");
            $table->string("address");
            $table->string("phone");
            $table->string("email");
            $table->text("specialities");
            $table->string("schedule");

            $table->unsignedBigInteger("province_id")->nullable();
            $table->unsignedBigInteger("municipio_id")->nullable();
            $table->string("active")->default(0);
            $table->timestamps();
            $table->softDeletes();

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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centers');
    }
};
