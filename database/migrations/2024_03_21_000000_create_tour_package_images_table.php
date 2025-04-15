<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tour_package_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->unsignedBigInteger('package_id');
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('package_id')
                  ->references('package_id')
                  ->on('tour_packages')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tour_package_images');
    }
}; 