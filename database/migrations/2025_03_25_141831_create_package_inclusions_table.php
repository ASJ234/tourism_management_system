<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageInclusionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_inclusions', function (Blueprint $table) {
            $table->id('inclusion_id');
            $table->foreignId('package_id')->constrained('tour_packages', 'package_id')->onDelete('cascade');
            $table->enum('inclusion_type', ['Accommodation', 'Transportation', 'Meals', 'Guide', 'Equipment']);
            $table->string('description', 255);
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
        Schema::dropIfExists('package_inclusions');
    }
}
