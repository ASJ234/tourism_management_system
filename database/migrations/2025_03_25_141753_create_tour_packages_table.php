<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id('package_id');
            $table->foreignId('destination_id')->constrained('destinations', 'destination_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('duration_days');
            $table->decimal('price', 10, 2);
            $table->integer('max_capacity');
            $table->enum('difficulty_level', ['Easy', 'Moderate', 'Challenging']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_available_slots');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users', 'user_id');
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
        Schema::dropIfExists('tour_packages');
    }
}
